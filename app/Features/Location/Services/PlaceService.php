<?php

namespace App\Features\Location\Services;

use App\Features\Location\Models\Place;
use App\Features\Location\Models\PlaceCategory;
use App\Features\Location\Repositories\Contracts\PlaceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PlaceService
{
    /**
     * The place repository instance.
     */
    protected $placeRepository;

    /**
     * Create a new service instance.
     */
    public function __construct(PlaceRepositoryInterface $placeRepository)
    {
        $this->placeRepository = $placeRepository;
    }

    /**
     * Get all active places with pagination.
     */
    public function getAllActivePlaces(int $perPage = 15): LengthAwarePaginator
    {
        return $this->placeRepository->getAllActivePaginated($perPage);
    }

    /**
     * Get all active places without pagination.
     */
    public function getAllActivePlacesList(): Collection
    {
        return $this->placeRepository->getAllActive();
    }

    /**
     * Find a place by ID.
     */
    public function findPlaceById(int $id): ?Place
    {
        return $this->placeRepository->findById($id);
    }

    /**
     * Get places by category.
     */
    public function getPlacesByCategory(int $categoryId): Collection
    {
        return $this->placeRepository->findByCategory($categoryId);
    }

    /**
     * Search places by query.
     */
    public function searchPlaces(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return $this->placeRepository->search($query, $perPage);
    }

    /**
     * Create a new place with optional image upload.
     */
    public function createPlace(array $data, ?UploadedFile $image = null): Place
    {
        try {
            DB::beginTransaction();

            // Handle image upload if provided and GD extension is available
            if ($image && extension_loaded('gd')) {
                $imagePath = $this->handleImageUpload($image);
                $data['image'] = $imagePath;
            }

            $place = $this->placeRepository->create($data);

            DB::commit();

            return $place;
        } catch (\Exception $e) {
            DB::rollBack();

            // Clean up uploaded image if transaction failed
            if (isset($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            throw $e;
        }
    }

    /**
     * Update an existing place.
     */
    public function updatePlace(int $id, array $data, ?UploadedFile $image = null): bool
    {
        try {
            DB::beginTransaction();

            $place = $this->placeRepository->findById($id);

            if (!$place) {
                throw new \Exception('Place not found');
            }

            // Handle image upload if provided and GD extension is available
            if ($image && extension_loaded('gd')) {
                $imagePath = $this->handleImageUpload($image, $place->image);
                $data['image'] = $imagePath;
            }

            $updated = $this->placeRepository->update($id, $data);

            DB::commit();

            return $updated;
        } catch (\Exception $e) {
            DB::rollBack();

            // Clean up uploaded image if transaction failed
            if (isset($imagePath) && $imagePath !== ($place->image ?? null)) {
                Storage::disk('public')->delete($imagePath);
            }

            throw $e;
        }
    }

    /**
     * Delete a place and its associated image.
     */
    public function deletePlace(int $id): bool
    {
        try {
            DB::beginTransaction();

            $place = $this->placeRepository->findById($id);

            if (!$place) {
                throw new \Exception('Place not found');
            }

            // Delete associated image if exists
            if ($place->image) {
                Storage::disk('public')->delete($place->image);
            }

            $deleted = $this->placeRepository->delete($id);

            DB::commit();

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get places near a specific location.
     */
    public function getNearbyPlaces(float $latitude, float $longitude, float $radiusKm = 10): Collection
    {
        return $this->placeRepository->getNearby($latitude, $longitude, $radiusKm);
    }

    /**
     * Get all active place categories.
     */
    public function getActiveCategories(): Collection
    {
        return PlaceCategory::active()->orderBy('name')->get();
    }

    /**
     * Handle image upload for places.
     */
    protected function handleImageUpload(UploadedFile $image, ?string $existingImage = null): string
    {
        // Delete existing image if it exists
        if ($existingImage) {
            Storage::disk('public')->delete($existingImage);
        }

        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

        // Store in places directory
        return $image->storeAs('places', $filename, 'public');
    }
}