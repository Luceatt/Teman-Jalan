<?php

namespace App\Features\Location\Repositories\Contracts;

use App\Features\Location\Models\Place;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface PlaceRepositoryInterface
{
    /**
     * Get all active places with pagination.
     */
    public function getAllActivePaginated(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get all active places without pagination.
     */
    public function getAllActive(): Collection;

    /**
     * Find a place by ID.
     */
    public function findById(int $id): ?Place;

    /**
     * Find places by category.
     */
    public function findByCategory(int $categoryId): Collection;

    /**
     * Search places by name or description.
     */
    public function search(string $query, int $perPage = 15): LengthAwarePaginator;

    /**
     * Create a new place.
     */
    public function create(array $data): Place;

    /**
     * Update an existing place.
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete a place.
     */
    public function delete(int $id): bool;

    /**
     * Get places near a specific location.
     */
    public function getNearby(float $latitude, float $longitude, float $radiusKm = 10): Collection;
}