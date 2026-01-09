<?php

namespace App\Http\Controllers;

use App\Models\Friendship;

class FriendController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Accepted friends
        $friends = Friendship::where(function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->orWhere('friend_id', $userId);
            })
            ->where('status', Friendship::STATUS_ACCEPTED)
            ->with(['user', 'friend'])
            ->get()
            ->map(function($friendship) use ($userId) {
                // Return the other user with friendship info attached
                $friend = $friendship->user_id == $userId ? $friendship->friend : $friendship->user;
                $friend->friendship_id = $friendship->friendship_id;
                $friend->times_together = $friendship->times_together;
                return $friend;
            });

        // Pending friend requests (incoming)
        $pendingRequests = Friendship::where('friend_id', $userId)
            ->where('status', Friendship::STATUS_PENDING)
            ->with('user')
            ->get();
            
        // Sent friend requests (outgoing) - optional but good to have
        $sentRequests = Friendship::where('user_id', $userId)
            ->where('status', Friendship::STATUS_PENDING)
            ->with('friend')
            ->get();

        return view('friends.index', compact('friends', 'pendingRequests', 'sentRequests'));
    }

    public function search()
    {
        $query = request('query');
        $userId = auth()->id();

        // Reuse index logic to keep context (optional, but good for UX)
        // Accepted friends
        $friends = Friendship::where(function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->orWhere('friend_id', $userId);
            })
            ->where('status', Friendship::STATUS_ACCEPTED)
            ->with(['user', 'friend'])
            ->get()
            ->map(function($friendship) use ($userId) {
                $friend = $friendship->user_id == $userId ? $friendship->friend : $friendship->user;
                $friend->friendship_id = $friendship->friendship_id;
                $friend->times_together = $friendship->times_together;
                return $friend;
            });

        $pendingRequests = Friendship::where('friend_id', $userId)
            ->where('status', Friendship::STATUS_PENDING)
            ->with('user')
            ->get();
            
        $sentRequests = Friendship::where('user_id', $userId)
            ->where('status', Friendship::STATUS_PENDING)
            ->with('friend')
            ->get();

        $searchResults = [];

        if ($query) {
             // Get IDs of current friends or pending requests to exclude them
            $existingFriendships = Friendship::where(function($q) use ($userId) {
                    $q->where('user_id', $userId)
                    ->orWhere('friend_id', $userId);
                })
                ->get();
                
            $excludedUserIds = $existingFriendships->pluck('user_id')
                ->merge($existingFriendships->pluck('friend_id'))
                ->push($userId) // Exclude self
                ->unique();

            $searchResults = \App\Models\User::where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->whereNotIn('id', $excludedUserIds)
                ->take(20)
                ->get();
        }

        return view('friends.index', compact('friends', 'pendingRequests', 'sentRequests', 'searchResults'));
    }

    public function store()
    {
        $validated = request()->validate([
            'friend_id' => 'required|exists:users,id'
        ]);

        $userId = auth()->id();
        $friendId = $validated['friend_id'];

        if ($userId == $friendId) {
            return back()->with('error', __('You cannot add yourself.'));
        }

        // Check if friendship already exists
        $exists = Friendship::where(function($q) use ($userId, $friendId) {
            $q->where('user_id', $userId)->where('friend_id', $friendId);
        })->orWhere(function($q) use ($userId, $friendId) {
            $q->where('user_id', $friendId)->where('friend_id', $userId);
        })->exists();

        if ($exists) {
            return back()->with('error', __('Friendship request already exists or you are already friends.'));
        }

        Friendship::create([
            'user_id' => $userId,
            'friend_id' => $friendId,
            'status' => Friendship::STATUS_PENDING
        ]);

        return back()->with('success', __('Friend request sent!'));
    }

    public function update($id)
    {
        $friendship = Friendship::findOrFail($id);

        // Only the recipient can accept
        if ($friendship->friend_id !== auth()->id()) {
            return abort(403);
        }

        $friendship->update([
            'status' => Friendship::STATUS_ACCEPTED
        ]);

        return back()->with('success', __('Friend request accepted!'));
    }

    public function destroy($id)
    {
        $friendship = Friendship::findOrFail($id);
        
        // Allow deletion if user is either the sender or receiver
        if ($friendship->user_id !== auth()->id() && $friendship->friend_id !== auth()->id()) {
            return abort(403);
        }

        $friendship->delete();

        return back()->with('success', __('Friend removed/request cancelled.'));
    }
}
