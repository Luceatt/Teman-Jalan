<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\UserPlaceVisit;
use App\Models\Friendship;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil upcoming events yang user ikuti atau buat
        $upcomingEvents = Event::where(function($query) use ($user) {
                $query->where('creator_id', $user->id)
                      ->orWhereHas('participants', function($q) use ($user) {
                          $q->where('user_id', $user->id);
                      });
            })
            ->where('event_date', '>=', now()->format('Y-m-d'))
            ->whereIn('status', [Event::STATUS_PLANNED, Event::STATUS_CONFIRMED, Event::STATUS_PUBLISHED])
            ->orderBy('event_date', 'asc')
            ->with(['creator', 'participants.user'])
            ->get();
        
        // Ambil favorite places (tempat yang paling sering dikunjungi)
        $favoritePlaces = UserPlaceVisit::where('user_id', $user->id)
            ->with('place')
            ->orderBy('visit_count', 'desc')
            ->orderBy('last_visit_date', 'desc')
            ->take(4)
            ->get();
        
        // Ambil favorite friends (teman yang paling sering jalan bareng)
        $favoriteFriends = Friendship::where('user_id', $user->id)
            ->where('status', 'accepted')
            ->with('friend')
            ->orderBy('times_together', 'desc')
            ->take(4)
            ->get();

        $totalFriends = Friendship::where('user_id', $user->id)
            ->where('status', 'accepted')
            ->count();

        $totalPlacesVisited = UserPlaceVisit::where('user_id', $user->id)
            ->count();
        
        return view('dashboard', compact('upcomingEvents', 'favoritePlaces', 'favoriteFriends','totalFriends', 'totalPlacesVisited'));
    }
}