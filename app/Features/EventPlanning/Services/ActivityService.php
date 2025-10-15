<?php

namespace App\Features\EventPlanning\Services;

use App\Features\EventPlanning\Models\Activity;
use App\Features\EventPlanning\Repositories\Contracts\ActivityRepositoryInterface;
use App\Features\Location\Services\PlaceService;
use Illuminate\Database\Eloquent\Collection;

class ActivityService
{
    protected ActivityRepositoryInterface $activityRepository;
    protected PlaceService $placeService;

    public function __construct(
        ActivityRepositoryInterface $activityRepository,
        PlaceService $placeService
    ) {
        $this->activityRepository = $activityRepository;
        $this->placeService = $placeService;
    }

    public function getActivitiesByRundown(int $rundownId): Collection
    {
        return $this->activityRepository->getByRundownId($rundownId);
    }

    public function findActivityById(int $id): ?Activity
    {
        return $this->activityRepository->findById($id);
    }

    public function createActivity(array $data): Activity
    {
        if (!isset($data['order'])) {
            $maxOrder = $this->activityRepository->getByRundownId($data['rundown_id'])->max('order') ?? 0;
            $data['order'] = $maxOrder + 1;
        }

        return $this->activityRepository->create($data);
    }

    public function updateActivity(int $id, array $data): Activity
    {
        $this->activityRepository->update($id, $data);
        return $this->findActivityById($id);
    }

    public function deleteActivity(int $id): void
    {
        $this->activityRepository->delete($id);
    }

    public function reorderActivities(array $activityOrders): void
    {
        $this->activityRepository->reorder($activityOrders);
    }

    public function getAvailablePlaces(string $search = ''): Collection
    {
        // TODO: search is not implemented in placeService
        return $this->placeService->getAllActivePlacesList();
    }

    public function getActivityTimeline(int $rundownId): array
    {
        $activities = $this->activityRepository->getTimeline($rundownId);

        return $activities->map(function ($activity) {
            return [
                'id' => $activity->id,
                'name' => $activity->name,
                'start_time' => $activity->start_time->toISOString(),
                'end_time' => $activity->end_time->toISOString(),
                'duration_minutes' => $activity->duration_in_minutes,
                'place' => [
                    'id' => $activity->place->id,
                    'name' => $activity->place->name,
                    'address' => $activity->place->address,
                    'latitude' => $activity->place->latitude,
                    'longitude' => $activity->place->longitude,
                ],
                'status' => $this->getActivityStatus($activity),
                'is_ongoing' => $activity->isOngoing(),
                'is_past' => $activity->isPast(),
                'is_upcoming' => $activity->isUpcoming(),
            ];
        })->toArray();
    }

    private function getActivityStatus(Activity $activity): string
    {
        if ($activity->isOngoing()) {
            return 'ongoing';
        } elseif ($activity->isPast()) {
            return 'completed';
        } else {
            return 'upcoming';
        }
    }

    public function validateActivityTime(int $rundownId, ?int $activityId, string $startTime, string $endTime): array
    {
        // This logic can be moved to a custom validation rule for better reusability
        $query = $this->activityRepository->getByRundownId($rundownId)
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime);

        if ($activityId) {
            $query = $query->where('id', '!=', $activityId);
        }

        $conflicts = $query;

        $errors = [];
        foreach ($conflicts as $conflict) {
            $errors[] = "Konflik waktu dengan aktivitas '{$conflict->name}' di tempat '{$conflict->place->name}'";
        }

        return $errors;
    }

    public function getActivitiesByDateRange(string $startDate, string $endDate): Collection
    {
        return $this->activityRepository->getByDateRange($startDate, $endDate);
    }

    public function getActivitiesByPlace(int $placeId): Collection
    {
        return $this->activityRepository->getByPlaceId($placeId);
    }

    public function getTotalDuration(int $rundownId): int
    {
        return $this->activityRepository->getTotalDuration($rundownId);
    }

    public function getNextActivity(int $rundownId): ?Activity
    {
        return $this->activityRepository->getNextActivity($rundownId);
    }

    public function getCurrentActivity(int $rundownId): ?Activity
    {
        return $this->activityRepository->getCurrentActivity($rundownId);
    }

    public function suggestDuration(int $placeId, string $activityType = null): int
    {
        $baseDurations = [
            'restaurant' => 90,
            'museum' => 120,
            'park' => 60,
            'shopping' => 180,
            'default' => 60,
        ];

        $place = $this->placeRepository->findById($placeId);
        if (!$place || !$place->category) {
            return $baseDurations['default'];
        }

        $categoryName = strtolower($place->category->name);

        foreach ($baseDurations as $key => $duration) {
            if ($key !== 'default' && str_contains($categoryName, $key)) {
                return $duration;
            }
        }

        return $baseDurations['default'];
    }
}