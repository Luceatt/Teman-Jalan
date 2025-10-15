<?php

namespace App\Features\EventPlanning\Controllers;

use App\Features\EventPlanning\Models\Rundown;
use App\Features\EventPlanning\Models\Activity;
use App\Features\EventPlanning\Services\RundownService;
use App\Features\EventPlanning\Requests\StoreRundownRequest;
use App\Features\EventPlanning\Requests\UpdateRundownRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RundownController
{
    /**
     * The rundown service instance.
     */
    protected RundownService $rundownService;

    /**
     * Create a new controller instance.
     */
    public function __construct(RundownService $rundownService)
    {
        $this->rundownService = $rundownService;
    }

    /**
     * Display a listing of rundowns.
     */
    public function index(): View
    {
        $rundowns = $this->rundownService->getAllRundowns();
        $stats = $this->rundownService->getRundownStats();

        return view('rundowns.index', compact('rundowns', 'stats'));
    }

    /**
     * Show the form for creating a new rundown.
     */
    public function create(): View
    {
        return view('rundowns.create');
    }

    /**
     * Display the specified rundown.
     */
    public function show(int $id): View
    {
        $rundown = $this->rundownService->findRundownById($id);

        if (!$rundown) {
            abort(404);
        }

        $activities = $rundown->activities()->with('place')->get();
        $places = $rundown->places;

        return view('rundowns.show', compact('rundown', 'activities', 'places'));
    }

    /**
     * Show the form for editing the specified rundown.
     */
    public function edit(int $id): View
    {
        $rundown = $this->rundownService->findRundownById($id);

        if (!$rundown) {
            abort(404);
        }

        return view('rundowns.edit', compact('rundown'));
    }

    /**
     * Store a newly created rundown.
     */
    public function store(StoreRundownRequest $request): RedirectResponse
    {
        try {
            $rundown = $this->rundownService->createRundown($request->validated());

            return redirect()->route('rundowns.show', $rundown->id)
                            ->with('success', __('Rundown berhasil dibuat'));
        } catch (\Exception $e) {
            return back()->withInput()
                         ->with('error', __('Gagal membuat rundown: :message', ['message' => $e->getMessage()]));
        }
    }

    /**
     * Update the specified rundown.
     */
    public function update(UpdateRundownRequest $request, int $id): RedirectResponse
    {
        try {
            $this->rundownService->updateRundown($id, $request->validated());

            return redirect()->route('rundowns.show', $id)
                            ->with('success', __('Rundown berhasil diperbarui'));
        } catch (\Exception $e) {
            return back()->withInput()
                         ->with('error', __('Gagal memperbarui rundown: :message', ['message' => $e->getMessage()]));
        }
    }

    /**
     * Remove the specified rundown.
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->rundownService->deleteRundown($id);

            return redirect()->route('rundowns.index')
                            ->with('success', __('Rundown berhasil dihapus'));
        } catch (\Exception $e) {
            return back()->with('error', __('Gagal menghapus rundown: :message', ['message' => $e->getMessage()]));
        }
    }

    /**
     * Publish the specified rundown.
     */
    public function publish(int $id): RedirectResponse
    {
        try {
            $this->rundownService->publishRundown($id);

            return back()->with('success', __('Rundown berhasil diterbitkan'));
        } catch (\Exception $e) {
            return back()->with('error', __('Gagal menerbitkan rundown: :message', ['message' => $e->getMessage()]));
        }
    }

    /**
     * Complete the specified rundown.
     */
    public function complete(int $id): RedirectResponse
    {
        try {
            $this->rundownService->completeRundown($id);

            return back()->with('success', __('Rundown berhasil diselesaikan'));
        } catch (\Exception $e) {
            return back()->with('error', __('Gagal menyelesaikan rundown: :message', ['message' => $e->getMessage()]));
        }
    }

    /**
     * Get rundown data for map display.
     */
    public function getMapData(int $id): JsonResponse
    {
        $rundown = $this->rundownService->findRundownById($id);

        if (!$rundown) {
            return response()->json(['error' => 'Rundown tidak ditemukan'], 404);
        }

        $mapData = $this->rundownService->getRundownMapData($id);

        return response()->json($mapData);
    }

    /**
     * Get rundowns for a specific date.
     */
    public function getByDate(): JsonResponse
    {
        $date = request('date', today()->toDateString());
        $rundowns = $this->rundownService->getRundownsByDate($date);

        return response()->json($rundowns);
    }
}