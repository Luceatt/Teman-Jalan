<?php

namespace App\Http\Controllers;

use App\Models\Friendship;

class FriendController extends Controller
{
    public function index()
    {
        $friends = Friendship::where('user_id', auth()->id())
            ->where('status', 'accepted')
            ->with('friend')
            ->get();

        return view('friends.index', compact('friends'));
    }
}
