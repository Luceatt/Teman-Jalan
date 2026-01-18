<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Event;
use App\Models\Place;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Display a listing of activities for a rundown.
     */
    public function index($rundownId)
    {
        $rundown = Event::where('event_id', $rundownId)->firstOrFail();
        $activities = Activity::where('event_id', $rundownId)
            ->with('place')
            ->orderBy('order_number')
            ->get();

        return view('activities.index', compact('rundown', 'activities'));
    }

    /**
     * Show the form for creating a new activity.
     */
    public function create($rundownId)
    {
        $rundown = Event::where('event_id', $rundownId)->firstOrFail();
        $places = Place::orderBy('name')->get();

        return view('activities.create', compact('rundown', 'places'));
    }

    /**
     * Store a newly created activity.
     */
    public function store(Request $request, $rundownId)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'place_id' => 'required|exists:places,place_id',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        // Get the next order number
        $maxOrder = Activity::where('event_id', $rundownId)->max('order_number') ?? 0;

        Activity::create([
            'event_id' => $rundownId,
            'place_id' => $validated['place_id'],
            'title' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'order_number' => $maxOrder + 1,
        ]);

        return redirect()
            ->route('rundowns.show', $rundownId)
            ->with('success', __('Activity added successfully!'));
    }

    /**
     * Display the specified activity.
     */
    public function show($id)
    {
        $activity = Activity::with(['event', 'place'])
            ->where('activity_id', $id)
            ->firstOrFail();

        return view('activities.show', compact('activity'));
    }

    /**
     * Show the form for editing the specified activity.
     */
    public function edit($id)
    {
        $activity = Activity::with('event')
            ->where('activity_id', $id)
            ->firstOrFail();
        $places = Place::orderBy('name')->get();

        return view('activities.edit', compact('activity', 'places'));
    }

    /**
     * Update the specified activity.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'place_id' => 'required|exists:places,place_id',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $activity = Activity::where('activity_id', $id)->firstOrFail();
        
        $activity->update([
            'place_id' => $validated['place_id'],
            'title' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
        ]);

        return redirect()
            ->route('rundowns.show', $activity->event_id)
            ->with('success', __('Activity updated successfully!'));
    }

    /**
     * Remove the specified activity.
     */
    public function destroy($id)
    {
        $activity = Activity::where('activity_id', $id)->firstOrFail();
        $rundownId = $activity->event_id;
        $activity->delete();

        return redirect()
            ->route('rundowns.show', $rundownId)
            ->with('success', __('Activity deleted successfully!'));
    }

    /**
     * Reorder activities in a rundown.
     */
    public function reorder(Request $request)
    {
        $activityOrders = $request->get('activities', []);

        foreach ($activityOrders as $item) {
            Activity::where('activity_id', $item['id'])
                ->update(['order_number' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get available places for activity creation/editing.
     */
    public function getAvailablePlaces(Request $request)
    {
        $search = $request->get('search', '');
        
        $query = Place::query();
        
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }
        
        $places = $query->orderBy('name')->get();

        return response()->json($places);
    }

    /**
     * Get activity timeline data for visualization.
     */
    public function getTimeline($rundownId)
    {
        $activities = Activity::where('event_id', $rundownId)
            ->with('place')
            ->orderBy('order_number')
            ->orderBy('start_time')
            ->get();

        $timeline = $activities->map(function ($activity) {
            $startTime = \Carbon\Carbon::parse($activity->start_time);
            $endTime = \Carbon\Carbon::parse($activity->end_time);
            
            return [
                'id' => $activity->activity_id,
                'name' => $activity->title,
                'start_time' => $startTime->toISOString(),
                'end_time' => $endTime->toISOString(),
                'duration_minutes' => $startTime->diffInMinutes($endTime),
                'place' => $activity->place ? [
                    'id' => $activity->place->place_id,
                    'name' => $activity->place->name,
                    'address' => $activity->place->address,
                    'latitude' => $activity->place->latitude,
                    'longitude' => $activity->place->longitude,
                ] : null,
                'is_past' => $endTime->isPast(),
                'is_ongoing' => $startTime->isPast() && $endTime->isFuture(),
                'is_upcoming' => $startTime->isFuture(),
            ];
        });

        return response()->json($timeline);
    }
}
