<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Http\Requests\Location\StorePlaceRequest;
use App\Http\Requests\Location\UpdatePlaceRequest;
use App\Services\Location\PlaceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PlaceController extends Controller
{
    /**
     * The place service instance.
     */
    protected PlaceService $placeService;

    /**
     * Create a new controller instance.
     */
    public function __construct(PlaceService $placeService)
    {
        $this->placeService = $placeService;
    }

    /**
     * Display a listing of places.
     */
    public function index(): View
    {
        $places = $this->placeService->getAllActivePlaces(12);
        $categories = $this->placeService->getActiveCategories();

        return view('locations.index', compact('places', 'categories'));
    }

    /**
     * Show the form for creating a new place.
     */
    public function create(): View
    {
        $categories = $this->placeService->getActiveCategories();

        return view('locations.create', compact('categories'));
    }

    /**
     * Display the specified place.
     */
    public function show(int $id): View
    {
        $place = $this->placeService->findPlaceById($id);

        if (!$place) {
            abort(404);
        }

        return view('locations.show', compact('place'));
    }

    /**
     * Show the form for editing the specified place.
     */
    public function edit(int $id): View
    {
        $place = $this->placeService->findPlaceById($id);

        if (!$place) {
            abort(404);
        }

        $categories = $this->placeService->getActiveCategories();

        return view('locations.edit', compact('place', 'categories'));
    }

    /**
     * Store a newly created place.
     */
    public function store(StorePlaceRequest $request): RedirectResponse
    {
        try {
            $place = $this->placeService->createPlace(
                $request->validated(),
                $request->file('image')
            );

            return redirect()->route('places.show', $place->id)
                           ->with('success', __('Place created successfully'));
        } catch (\Exception $e) {
            return back()->withInput()
                        ->with('error', __('Failed to create place: :message', ['message' => $e->getMessage()]));
        }
    }

    /**
     * Update the specified place.
     */
    public function update(UpdatePlaceRequest $request, int $id): RedirectResponse
    {
        try {
            $this->placeService->updatePlace(
                $id,
                $request->validated(),
                $request->file('image')
            );

            return redirect()->route('places.show', $id)
                           ->with('success', __('Place updated successfully'));
        } catch (\Exception $e) {
            return back()->withInput()
                        ->with('error', __('Failed to update place: :message', ['message' => $e->getMessage()]));
        }
    }

    /**
     * Remove the specified place.
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->placeService->deletePlace($id);

            return redirect()->route('places.index')
                           ->with('success', __('Place deleted successfully'));
        } catch (\Exception $e) {
            return back()->with('error', __('Failed to delete place: :message', ['message' => $e->getMessage()]));
        }
    }

    /**
     * Search places by query.
     */
    public function search(): JsonResponse
    {
        $query = request('query', '');
        $places = $this->placeService->searchPlaces($query, 20);

        return response()->json([
            'places' => $places->items(),
            'hasMorePages' => $places->hasMorePages(),
        ]);
    }

    /**
     * Get places near a specific location.
     */
    public function nearby(): JsonResponse
    {
        $latitude = request('latitude', 0);
        $longitude = request('longitude', 0);
        $radius = request('radius', 10);

        $places = $this->placeService->getNearbyPlaces($latitude, $longitude, $radius);

        return response()->json($places);
    }
}
