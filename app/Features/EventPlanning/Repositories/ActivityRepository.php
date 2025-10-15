<?php

namespace App\Features\EventPlanning\Repositories;

use App\Features\EventPlanning\Models\Activity;
use App\Features\EventPlanning\Repositories\Contracts\ActivityRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ActivityRepository implements ActivityRepositoryInterface
{
    protected Activity $model;

    public function __construct(Activity $model)
    {
        $this->model = $model;
    }

    public function findById(int $id): ?Activity
    {
        return $this->model->with(['place', 'rundown'])->find($id);
    }

    public function create(array $data): Activity
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->model->find($id)->update($data);
    }

    public function delete(int $id): bool
    {
        return $this->model->find($id)->delete();
    }

    public function getByRundownId(int $rundownId): Collection
    {
        return $this->model->where('rundown_id', $rundownId)
                           ->with(['place', 'rundown'])
                           ->ordered()
                           ->get();
    }

    public function reorder(array $activityOrders): void
    {
        DB::transaction(function () use ($activityOrders) {
            foreach ($activityOrders as $order) {
                $this->model->where('id', $order['id'])
                            ->update(['order' => $order['order']]);
            }
        });
    }

    public function getTimeline(int $rundownId): Collection
    {
        return $this->getByRundownId($rundownId);
    }

    public function getByDateRange(string $startDate, string $endDate): Collection
    {
        return $this->model->whereBetween('start_time', [$startDate, $endDate])
                           ->with(['place', 'rundown'])
                           ->orderBy('start_time')
                           ->get();
    }

    public function getByPlaceId(int $placeId): Collection
    {
        return $this->model->where('place_id', $placeId)
                           ->with(['rundown'])
                           ->orderBy('start_time')
                           ->get();
    }

    public function getTotalDuration(int $rundownId): int
    {
        return $this->model->where('rundown_id', $rundownId)->sum(DB::raw('TIMESTAMPDIFF(MINUTE, start_time, end_time)'));
    }

    public function getNextActivity(int $rundownId): ?Activity
    {
        return $this->model->where('rundown_id', $rundownId)
                           ->where('start_time', '>', now())
                           ->with(['place'])
                           ->orderBy('start_time')
                           ->first();
    }

    public function getCurrentActivity(int $rundownId): ?Activity
    {
        return $this->model->where('rundown_id', $rundownId)
                           ->where('start_time', '<=', now())
                           ->where('end_time', '>=', now())
                           ->with(['place'])
                           ->first();
    }
}