<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventParticipantController extends Controller
{
    /**
     * Store a newly created participant in the event.
     */
    public function store(Request $request, $eventId)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $event = Event::findOrFail($eventId);
        $userId = Auth::id();

        // Check if user is the creator of the event (only creator can add participants for now)
        if ($event->creator_id !== $userId) {
            return back()->with('error', __('Only the event creator can add participants.'));
        }

        $newParticipantId = $validated['user_id'];

        // Verify friendship using shared logic or direct query
        // We can reuse FriendController logic but for now direct query is faster/simpler here
        $isFriend = Friendship::where(function($q) use ($userId, $newParticipantId) {
                $q->where('user_id', $userId)->where('friend_id', $newParticipantId);
            })->orWhere(function($q) use ($userId, $newParticipantId) {
                $q->where('user_id', $newParticipantId)->where('friend_id', $userId);
            })
            ->where('status', Friendship::STATUS_ACCEPTED)
            ->exists();

        if (!$isFriend && $userId != $newParticipantId) { // Allow adding self if not already added (though creator is implied)
            return back()->with('error', __('You can only add friends to your rundown.'));
        }

        // Check if already a participant
        $exists = EventParticipant::where('event_id', $eventId)
            ->where('user_id', $newParticipantId)
            ->exists();

        if ($exists) {
            return back()->with('error', __('User is already a participant.'));
        }

        EventParticipant::create([
            'event_id' => $eventId,
            'user_id' => $newParticipantId,
            'joined_at' => now(),
        ]);

        return back()->with('success', __('Participant added successfully.'));
    }

    /**
     * Remove the specified participant from the event.
     */
    public function destroy($eventId, $participantId)
    {
        $event = Event::findOrFail($eventId);
        
        // Only creator can remove participants
        if ($event->creator_id !== Auth::id()) {
            return back()->with('error', __('Unauthorized action.'));
        }

        $participant = EventParticipant::where('event_id', $eventId)
            ->where('participant_id', $participantId)
            ->firstOrFail();

        // Cannot remove the creator from the event participants (if we decide to add creator as a participant automatically)
        // But logic might differ. For now, let's assume creator is always "there" but maybe not in this table?
        // Actually, for split bill, creator should also be in this table OR handled separately.
        // Let's assume we WANT creator in this table for uniformity in Expenses.
        
        if ($participant->user_id == $event->creator_id) {
             return back()->with('error', __('Cannot remove the event creator.'));
        }

        $participant->delete();

        return back()->with('success', __('Participant removed successfully.'));
    }
}
