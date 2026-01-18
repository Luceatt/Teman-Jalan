<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlaceHistoryController extends Controller
{
    public function show($placeId)
    {
        $userId = Auth::id();
       
        // Ambil data place
        $place = Place::where('place_id', $placeId)->firstOrFail();
        
        // Hitung total visit ke place ini oleh user
        $totalVisits = DB::table('activities')
            ->join('events', 'activities.event_id', '=', 'events.event_id')
            ->leftJoin('event_participants', 'events.event_id', '=', 'event_participants.event_id')
            ->where('activities.place_id', $placeId)
            ->where(function($q) use ($userId) {
                $q->where('events.creator_id', $userId)
                  ->orWhere('event_participants.user_id', $userId);
            })
            ->distinct('events.event_id')
            ->count('events.event_id');
       
        // Ambil semua events yang memiliki activities di place ini
        $events = DB::table('activities')
            ->join('events', 'activities.event_id', '=', 'events.event_id')
            ->leftJoin('event_participants', 'events.event_id', '=', 'event_participants.event_id')
            ->where('activities.place_id', $placeId)
            ->where(function($q) use ($userId) {
                $q->where('events.creator_id', $userId)
                  ->orWhere('event_participants.user_id', $userId);
            })
            ->select(
                'events.event_id',
                'events.event_name as event_name',
                'events.description as event_description',
                'events.event_date',
                DB::raw('MIN(activities.start_time) as visit_start'),
                DB::raw('MAX(activities.end_time) as visit_end')
            )
            ->groupBy('events.event_id', 'events.event_name', 'events.description', 'events.event_date')
            ->orderBy('events.event_date', 'desc')
            ->get();
       
        // Untuk setiap event, ambil activities dan expenses
        $eventsData = [];
        $totalUserExpense = 0;
       
        foreach ($events as $event) {
            $eventData = [
                'event_id' => $event->event_id,
                'event_name' => $event->event_name,
                'event_description' => $event->event_description,
                'event_date' => $event->event_date,
                'visit_start' => $event->visit_start,
                'visit_end' => $event->visit_end,
                'activities' => [],
                'total_user_expense' => 0
            ];
           
            // Ambil activities untuk event ini di place ini
            $activities = DB::table('activities')
                ->where('event_id', $event->event_id)
                ->where('place_id', $placeId)
                ->orderBy('order_number')
                ->get();
           
            foreach ($activities as $activity) {
                $activityData = [
                    'activity_id' => $activity->activity_id,
                    'title' => $activity->title,
                    'description' => $activity->description,
                    'expenses' => []
                ];
               
                // Ambil expenses untuk activity ini
                $expenses = DB::table('expenses')
                    ->where('activity_id', $activity->activity_id)
                    ->get();
               
                foreach ($expenses as $expense) {
                    // Ambil siapa saja yang berbagi expense ini
                    $shares = DB::table('expense_shares')
                        ->join('users', 'expense_shares.user_id', '=', 'users.id')
                        ->where('expense_shares.expense_id', $expense->expense_id)
                        ->select(
                            'users.id',
                            'users.name',
                            'expense_shares.amount_owed'
                        )
                        ->get();
                   
                    // Hitung berapa yang user keluarkan untuk expense ini
                    $userAmountPaid = 0;
                    if ($expense->paid_by_user_id == $userId) {
                        // User tsb yang bayar
                        $userShare = $shares->firstWhere('id', $userId);
                        if ($userShare) {
                            $userAmountPaid = $userShare->amount_owed;
                        }
                    }
                   
                    $expenseData = [
                        'expense_id' => $expense->expense_id,
                        'description' => $expense->description,
                        'total_amount' => $expense->amount,
                        'paid_by_user_id' => $expense->paid_by_user_id,
                        'shares' => $shares,
                        'user_amount_paid' => $userAmountPaid
                    ];
                   
                    $activityData['expenses'][] = $expenseData;
                    $eventData['total_user_expense'] += $userAmountPaid;
                }
               
                $activityData['total_activity_expense'] = collect($activityData['expenses'])->sum('user_amount_paid');
                $eventData['activities'][] = $activityData;
            }
           
            $eventsData[] = $eventData;
            $totalUserExpense += $eventData['total_user_expense'];
        }
        
        // Dummy image URL untuk place
        $placeData = [
            'place_id' => $place->place_id,
            'place_name' => $place->name,
            'address' => $place->address,
            'image_url' => 'https://picsum.photos/seed/place' . $place->place_id . '/400/400',
            'total_visits' => $totalVisits
        ];
       
        return view('history.show.places-content', compact(
            'placeData',
            'eventsData',
            'totalUserExpense'
        ));
    }
}