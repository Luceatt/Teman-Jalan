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
            ->with('success', 'Rundown berhasil dibuat!');
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
            ->with('success', 'Rundown berhasil diperbarui!');
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
            ->with('success', 'Rundown berhasil dihapus!');
    }

    /**
     * Publish the specified rundown.
     */
    public function publish($id)
    {
        $rundown = Event::where('event_id', $id)->firstOrFail();
        $rundown->update(['status' => Event::STATUS_PUBLISHED]);

        return back()->with('success', 'Rundown berhasil diterbitkan!');
    }

    /**
     * Complete the specified rundown.
     */
    public function complete($id)
    {
        $rundown = Event::where('event_id', $id)->firstOrFail();
        $rundown->update(['status' => Event::STATUS_COMPLETED]);

        return back()->with('success', 'Rundown berhasil diselesaikan!');
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
            return response()->json(['error' => 'Rundown tidak ditemukan'], 404);
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
                    'category' => $activity->place->category ?? 'Uncategorized',
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
