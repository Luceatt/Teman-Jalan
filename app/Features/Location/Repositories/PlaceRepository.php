<?php

namespace App\Features\Location\Repositories;

use App\Features\Location\Models\Place;
use App\Features\Location\Repositories\Contracts\PlaceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PlaceRepository implements PlaceRepositoryInterface
{
    /**
     * The Place model instance.
     */
    protected $model;

    /**
     * Create a new repository instance.
     */
    public function __construct(\App\Features\Location\Models\Place $model)
    {
        $this->model = $model;
    }

    /**
     * Get all active places with pagination.
     */
    public function getAllActivePaginated(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->active()
                         ->with('category')
                         ->orderBy('name')
                         ->paginate($perPage);
    }

    /**
     * Get all active places without pagination.
     */
    public function getAllActive(): Collection
    {
        return $this->model->active()
                         ->with('category')
                         ->orderBy('name')
                         ->get();
    }

    /**
     * Find a place by ID.
     */
    public function findById(int $id): ?Place
    {
        return $this->model->with('category')->find($id);
    }

    /**
     * Find places by category.
     */
    public function findByCategory(int $categoryId): Collection
    {
        return $this->model->where('category_id', $categoryId)
                         ->active()
                         ->with('category')
                         ->orderBy('name')
                         ->get();
    }

    /**
     * Search places by name or description.
     */
    public function search(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->search($query)
                         ->active()
                         ->with('category')
                         ->paginate($perPage);
    }

    /**
     * Create a new place.
     */
    public function create(array $data): Place
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing place.
     */
    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data) > 0;
    }

    /**
     * Delete a place.
     */
    public function delete(int $id): bool
    {
        return $this->model->destroy($id) > 0;
    }

    /**
     * Get places near a specific location.
     */
    public function getNearby(float $latitude, float $longitude, float $radiusKm = 10): Collection
    {
        return $this->model->active()
                         ->with('category')
                         ->get()
                         ->filter(function ($place) use ($latitude, $longitude, $radiusKm) {
                             $distance = $this->calculateDistance($latitude, $longitude, $place->latitude, $place->longitude);
                             $place->distance = $distance;
                             return $distance <= $radiusKm;
                         })
                         ->sortBy('distance');
    }

    /**
     * Calculate distance between two coordinates using Haversine formula.
     */
    private function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}