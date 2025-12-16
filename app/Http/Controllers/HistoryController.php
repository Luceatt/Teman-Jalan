<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    public function index($tab = 'events')
    {
        $userId = auth()->id() ?? 1; // user login untuk dev mode

        // TAB EVENTS
        $events = Event::with([
            'participants.user:id,name,profile_picture_url'
        ])
        ->whereHas('participants', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
        ->where('status', 'completed')
        ->withSum('expenses', 'amount')
        ->get()
        ->map(function ($event) use ($userId) {
            $placesVisited = DB::table('activities')
                ->where('event_id', $event->event_id)
                ->whereNotNull('place_id')
                ->distinct('place_id')
                ->count('place_id');

            // Hitung total pengeluaran user untuk event ini (bukan total event)
            $userSpent = DB::table('expense_shares')
                ->join('expenses', 'expense_shares.expense_id', '=', 'expenses.expense_id')
                ->where('expenses.event_id', $event->event_id)
                ->where('expense_shares.user_id', $userId)
                ->sum('expense_shares.amount_owed');

            return [
                'event_id' => $event->event_id,
                'title' => $event->event_name,
                'date' => $event->event_date,
                'places_visited' => $placesVisited,
                'spent' => $userSpent ?? 0,
                'friends' => $event->participants
                    ->where('user_id', '!=', $userId)
                    ->map(function ($p) {
                        return [
                            'name' => $p->user->name,
                            'profile_picture_url' => $p->user->profile_picture_url
                                ?? 'https://i.pravatar.cc/50',
                        ];
                    }),
            ];
        });

        // TAB PLACES
        // Ambil place_id yang pernah dikunjungi user dari event completed
        $visitedPlaceIds = DB::table('activities')
            ->join('events', 'activities.event_id', '=', 'events.event_id')
            ->join('event_participants', 'events.event_id', '=', 'event_participants.event_id')
            ->where('event_participants.user_id', $userId)
            ->where('events.status', 'completed')
            ->whereNotNull('activities.place_id')
            ->distinct()
            ->pluck('activities.place_id');

        $places = Place::join('user_place_visits', 'places.place_id', '=', 'user_place_visits.place_id')
            ->where('user_place_visits.user_id', $userId)
            ->whereIn('places.place_id', $visitedPlaceIds)
            ->select(
                'places.place_id',
                'places.name',
                'places.address',
                'user_place_visits.visit_count',
                'user_place_visits.last_visit_date'
            )
            ->get()
            ->map(function ($place) use ($userId) {

                $activities = DB::table('activities')
                    ->join('events', 'activities.event_id', '=', 'events.event_id')
                    ->join('event_participants', 'events.event_id', '=', 'event_participants.event_id')
                    ->where('event_participants.user_id', $userId)
                    ->where('events.status', 'completed')
                    ->where('activities.place_id', $place->place_id)
                    ->distinct()
                    ->pluck('activities.title')
                    ->values();

                return [
                    'place_id' => $place->place_id,
                    'name' => $place->name,
                    'address' => $place->address,
                    'image_url' => 'https://picsum.photos/seed/place' . $place->place_id . '/400/400',
                    'activities' => $activities,
                    'total_visits' => $place->visit_count,
                    'last_visit' => $place->last_visit_date,
                ];
            });

        return view('history.index', compact('tab', 'events', 'places'));
    }
}