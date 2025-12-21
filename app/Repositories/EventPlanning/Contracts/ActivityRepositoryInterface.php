<?php

namespace App\Repositories\EventPlanning\Contracts;

use App\Models\EventPlanning\Activity;
use Illuminate\Database\Eloquent\Collection;

interface ActivityRepositoryInterface
{
    public function findById(int $id): ?Activity;

    public function create(array $data): Activity;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function getByRundownId(int $rundownId): Collection;

    public function reorder(array $activityOrders): void;

    public function getTimeline(int $rundownId): Collection;

    public function getByDateRange(string $startDate, string $endDate): Collection;

    public function getByPlaceId(int $placeId): Collection;

    public function getTotalDuration(int $rundownId): int;

    public function getNextActivity(int $rundownId): ?Activity;

    public function getCurrentActivity(int $rundownId): ?Activity;
}
