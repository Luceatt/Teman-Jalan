<?php

namespace App\Repositories\EventPlanning\Contracts;

use App\Models\EventPlanning\Rundown;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface RundownRepositoryInterface
{
    public function getAll(int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): ?Rundown;

    public function create(array $data): Rundown;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function getByDate(string $date): Collection;

    public function getStats(): array;

    public function search(string $query, int $limit = 20): Collection;

    public function getUpcoming(int $limit = 10): Collection;
}
