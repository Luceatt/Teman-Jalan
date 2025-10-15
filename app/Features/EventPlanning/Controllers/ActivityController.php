<?php

namespace App\Features\EventPlanning\Controllers;

use App\Features\EventPlanning\Models\Activity;
use App\Features\EventPlanning\Models\Rundown;
use App\Features\EventPlanning\Services\ActivityService;
use App\Features\EventPlanning\Requests\StoreActivityRequest;
use App\Features\EventPlanning\Requests\UpdateActivityRequest;
use App\Features\Location\Services\PlaceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ActivityController
{
    /**
     * The activity service instance.
     */
    protected ActivityService $activityService;
    protected PlaceService $placeService;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        ActivityService $activityService,
        PlaceService $placeService
    ) {
        $this->activityService = $activityService;
        $this->placeService = $placeService;
    }

    /**
     * Display a listing of activities for a rundown.
     */
    public function index(Rundown $rundown): View
    {
        $activities = $this->activityService->getActivitiesByRundown($rundown->id);

        return view('activities.index', compact('rundown', 'activities'));
    }

    /**
     * Show the form for creating a new activity.
     */
    public function create(Rundown $rundown): View
    {
        $places = $this->placeService->getAllActivePlacesList();
        return view('activities.create', compact('rundown', 'places'));
    }

    /**
     * Display the specified activity.
     */
    public function show(Activity $activity): View
    {
        return view('activities.show', compact('activity'));
    }

    /**
     * Show the form for editing the specified activity.
     */
    public function edit(Activity $activity): View
    {
        return view('activities.edit', compact('activity'));
    }

    /**
     * Store a newly created activity.
     */
    public function store(StoreActivityRequest $request, Rundown $rundown): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['rundown_id'] = $rundown->id;
            $this->activityService->createActivity($data);

            return redirect()->route('rundowns.show', $rundown->id)
                            ->with('success', __('Aktivitas berhasil ditambahkan'));
        } catch (\Exception $e) {
            return back()->withInput()
                         ->with('error', __('Gagal menambahkan aktivitas: :message', ['message' => $e->getMessage()]));
        }
    }

    /**
     * Update the specified activity.
     */
    public function update(UpdateActivityRequest $request, Activity $activity): RedirectResponse
    {
        try {
            $this->activityService->updateActivity($activity->id, $request->validated());

            return redirect()->route('rundowns.show', $activity->rundown_id)
                            ->with('success', __('Aktivitas berhasil diperbarui'));
        } catch (\Exception $e) {
            return back()->withInput()
                         ->with('error', __('Gagal memperbarui aktivitas: :message', ['message' => $e->getMessage()]));
        }
    }

    /**
     * Remove the specified activity.
     */
    public function destroy(Activity $activity): RedirectResponse
    {
        try {
            $rundownId = $activity->rundown_id;
            $this->activityService->deleteActivity($activity->id);

            return redirect()->route('rundowns.show', $rundownId)
                            ->with('success', __('Aktivitas berhasil dihapus'));
        } catch (\Exception $e) {
            return back()->with('error', __('Gagal menghapus aktivitas: :message', ['message' => $e->getMessage()]));
        }
    }

    /**
     * Reorder activities in a rundown.
     */
    public function reorder(): JsonResponse
    {
        $activityOrders = request('activities', []);

        try {
            $this->activityService->reorderActivities($activityOrders);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available places for activity creation/editing.
     */
    public function getAvailablePlaces(): JsonResponse
    {
        $search = request('search', '');
        $places = $this->activityService->getAvailablePlaces($search);

        return response()->json($places);
    }

    /**
     * Get activity timeline data for visualization.
     */
    public function getTimeline(Rundown $rundown): JsonResponse
    {
        $timeline = $this->activityService->getActivityTimeline($rundown->id);

        return response()->json($timeline);
    }
}