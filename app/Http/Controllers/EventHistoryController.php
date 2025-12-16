<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventHistoryController extends Controller
{
    public function show($eventId)
    {
        $userId = Auth::id();
       
        // Ambil data event dengan participants
        $event = Event::with(['participants.user', 'creator'])
            ->where('event_id', $eventId)
            ->firstOrFail();
       
        // Ambil semua places yang dikunjungi dalam event ini
        $places = DB::table('activities')
            ->join('places', 'activities.place_id', '=', 'places.place_id')
            ->where('activities.event_id', $eventId)
            ->select(
                'places.place_id',
                'places.name as place_name',
                'places.address',
                DB::raw('MIN(activities.start_time) as visit_start'),
                DB::raw('MAX(activities.end_time) as visit_end')
            )
            ->groupBy('places.place_id', 'places.name', 'places.address')
            ->orderBy('visit_start')
            ->get();
       
        // Untuk setiap place, ambil activities dan expenses
        $placesData = [];
        $totalUserExpense = 0;
       
        foreach ($places as $place) {
            $placeData = [
                'place_id' => $place->place_id,
                'place_name' => $place->place_name,
                'address' => $place->address,
                'visit_start' => $place->visit_start,
                'visit_end' => $place->visit_end,
                'image_url' => 'https://picsum.photos/seed/place' . $place->place_id . '/400/400',
                'activities' => [],
                'total_user_expense' => 0
            ];
           
            // Ambil activities untuk place ini
            $activities = DB::table('activities')
                ->where('event_id', $eventId)
                ->where('place_id', $place->place_id)
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
                    $placeData['total_user_expense'] += $userAmountPaid;
                }
               
                $placeData['activities'][] = $activityData;
            }
           
            $placesData[] = $placeData;
            $totalUserExpense += $placeData['total_user_expense'];
        }
       
        return view('history.show.events-content', compact(
            'event',
            'placesData',
            'totalUserExpense'
        ));
    }
}