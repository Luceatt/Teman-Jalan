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
    protected Place $model;

    /**
     * Create a new repository instance.
     */
    public function __construct(Place $model)
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
        // Using Haversine formula for distance calculation
        $haversine = "(6371 * acos(cos(radians($latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians($longitude)) + sin(radians($latitude)) * sin(radians(latitude))))";

        return $this->model->select('*')
                         ->selectRaw("{$haversine} AS distance")
                         ->having('distance', '<', $radiusKm)
                         ->active()
                         ->with('category')
                         ->orderBy('distance')
                         ->get();
    }
}