<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RundownController extends Controller
{
    /**
     * Display a listing of rundowns.
     */
    public function index()
    {
        $userId = Auth::id();
        
        $rundowns = Event::where(function($query) use ($userId) {
                $query->where('creator_id', $userId)
                      ->orWhereHas('participants', function($q) use ($userId) {
                          $q->where('user_id', $userId);
                      });
            })
            ->whereIn('status', [
                Event::STATUS_DRAFT, 
                Event::STATUS_PLANNED, 
                Event::STATUS_CONFIRMED, 
                Event::STATUS_PUBLISHED
            ])
            ->with(['activities', 'participants'])
            ->orderBy('event_date', 'desc')
            ->paginate(10);
        
        $stats = [
            'total' => Event::where(function($q) use ($userId) {
                $q->where('creator_id', $userId)->orWhereHas('participants', fn($p) => $p->where('user_id', $userId));
            })->count(),
            'published' => Event::where(function($q) use ($userId) {
                $q->where('creator_id', $userId)->orWhereHas('participants', fn($p) => $p->where('user_id', $userId));
            })->where('status', Event::STATUS_PUBLISHED)->count(),
            'completed' => Event::where(function($q) use ($userId) {
                $q->where('creator_id', $userId)->orWhereHas('participants', fn($p) => $p->where('user_id', $userId));
            })->where('status', Event::STATUS_COMPLETED)->count(),
            'today' => Event::where(function($q) use ($userId) {
                $q->where('creator_id', $userId)->orWhereHas('participants', fn($p) => $p->where('user_id', $userId));
            })->whereDate('event_date', today())->count(),
        ];

        return view('rundowns.index', compact('rundowns', 'stats'));
    }

    /**
     * Show the form for creating a new rundown.
     */
    public function create()
    {
        return view('rundowns.create');
    }

    /**
     * Store a newly created rundown.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'status' => 'nullable|string|in:draft,planned,confirmed,published',
            'notes' => 'nullable|string',
            'is_public' => 'nullable|boolean',
        ]);

        $event = Event::create([
            'event_name' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'event_date' => $validated['date'],
            'creator_id' => Auth::id(),
            'status' => $validated['status'] ?? Event::STATUS_DRAFT,
        ]);

        return redirect()
            ->route('rundowns.show', $event->event_id)
            ->with('success', __('Rundown created successfully!'));
    }

    /**
     * Display the specified rundown.
     */
    public function show($id)
    {
        $rundown = Event::with(['activities.place', 'participants.user'])
            ->where('event_id', $id)
            ->firstOrFail();
        
        $activities = $rundown->activities()->orderBy('order_number')->get();
        
        // Get places through activities
        $placeIds = $activities->pluck('place_id')->unique()->filter();
        $places = Place::whereIn('place_id', $placeIds)->get();

        return view('rundowns.show', compact('rundown', 'activities', 'places'));
    }

    /**
     * Show the form for editing the specified rundown.
     */
    public function edit($id)
    {
        $rundown = Event::where('event_id', $id)->firstOrFail();

        return view('rundowns.edit', compact('rundown'));
    }

    /**
     * Update the specified rundown.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'status' => 'nullable|string|in:draft,planned,confirmed,published,completed,cancelled',
            'notes' => 'nullable|string',
            'is_public' => 'nullable|boolean',
        ]);

        $rundown = Event::where('event_id', $id)->firstOrFail();
        
        $rundown->update([
            'event_name' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'event_date' => $validated['date'],
            'status' => $validated['status'] ?? $rundown->status,
        ]);

        return redirect()
            ->route('rundowns.show', $id)
            ->with('success', __('Rundown updated successfully!'));
    }

    /**
     * Remove the specified rundown.
     */
    public function destroy($id)
    {
        $rundown = Event::where('event_id', $id)->firstOrFail();
        $rundown->delete();

        return redirect()
            ->route('rundowns.index')
            ->with('success', __('Rundown deleted successfully!'));
    }

    /**
     * Publish the specified rundown.
     */
    public function publish($id)
    {
        $rundown = Event::where('event_id', $id)->firstOrFail();
        $rundown->update(['status' => Event::STATUS_PUBLISHED]);

        return back()->with('success', __('Rundown published successfully!'));
    }

    /**
     * Complete the specified rundown.
     */
    public function complete($id)
    {
        $rundown = Event::where('event_id', $id)->with(['activities', 'participants'])->firstOrFail();
        $rundown->update(['status' => Event::STATUS_COMPLETED]);

        // Track place visits for all participants
        $placeIds = $rundown->activities()
            ->whereNotNull('place_id')
            ->distinct()
            ->pluck('place_id');

        $participantIds = $rundown->participants()->pluck('user_id')->toArray();
        
        // Add creator to participants if not already included
        if (!in_array($rundown->creator_id, $participantIds)) {
            $participantIds[] = $rundown->creator_id;
        }

        // Update UserPlaceVisit for each participant and place combination
        foreach ($participantIds as $userId) {
            foreach ($placeIds as $placeId) {
                $visit = \App\Models\UserPlaceVisit::firstOrNew([
                    'user_id' => $userId,
                    'place_id' => $placeId,
                ]);
                
                $visit->visit_count = ($visit->visit_count ?? 0) + 1;
                $visit->last_visit_date = $rundown->event_date;
                $visit->save();
            }
        }

        // Update times_together for all friendship combinations
        if (count($participantIds) > 1) {
            for ($i = 0; $i < count($participantIds); $i++) {
                for ($j = $i + 1; $j < count($participantIds); $j++) {
                    $user1 = $participantIds[$i];
                    $user2 = $participantIds[$j];
                    
                    // Find the friendship (bidirectional check)
                    $friendship = \App\Models\Friendship::where(function($query) use ($user1, $user2) {
                        $query->where('user_id', $user1)->where('friend_id', $user2);
                    })->orWhere(function($query) use ($user1, $user2) {
                        $query->where('user_id', $user2)->where('friend_id', $user1);
                    })->where('status', \App\Models\Friendship::STATUS_ACCEPTED)->first();
                    
                    if ($friendship) {
                        $friendship->increment('times_together');
                    }
                }
            }
        }

        return back()->with('success', __('Rundown completed successfully!'));
    }

    /**
     * Get rundown data for map display.
     */
    public function getMapData($id)
    {
        $rundown = Event::with('activities.place')
            ->where('event_id', $id)
            ->first();

        if (!$rundown) {
            return response()->json(['error' => __('Rundown not found')], 404);
        }

        $places = $rundown->activities
            ->filter(fn($a) => $a->place)
            ->map(function ($activity) {
                return [
                    'id' => $activity->place->place_id,
                    'name' => $activity->place->name,
                    'latitude' => $activity->place->latitude,
                    'longitude' => $activity->place->longitude,
                    'address' => $activity->place->address,
                    'category' => $activity->place->category ?? '',
                ];
            });

        // Calculate center
        $center = ['lat' => -6.2088, 'lng' => 106.8456]; // Jakarta default
        if ($places->count() > 0) {
            $center = [
                'lat' => $places->avg('latitude'),
                'lng' => $places->avg('longitude'),
            ];
        }

        return response()->json([
            'rundown' => [
                'id' => $rundown->event_id,
                'title' => $rundown->event_name,
                'date' => $rundown->event_date,
                'status' => $rundown->status,
            ],
            'places' => $places->values(),
            'center' => $center,
        ]);
    }

    /**
     * Get rundowns for a specific date.
     */
    public function getByDate(Request $request)
    {
        $date = $request->get('date', today()->toDateString());
        $userId = Auth::id();
        
        $rundowns = Event::where('creator_id', $userId)
            ->whereDate('event_date', $date)
            ->with('activities.place')
            ->get();

        return response()->json($rundowns);
    }
}
