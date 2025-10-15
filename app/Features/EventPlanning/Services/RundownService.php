<?php

namespace App\Features\EventPlanning\Services;

use App\Features\EventPlanning\Models\Rundown;
use App\Features\EventPlanning\Repositories\Contracts\RundownRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class RundownService
{
    protected RundownRepositoryInterface $rundownRepository;

    public function __construct(RundownRepositoryInterface $rundownRepository)
    {
        $this->rundownRepository = $rundownRepository;
    }

    public function getAllRundowns(int $perPage = 15): LengthAwarePaginator
    {
        return $this->rundownRepository->getAll($perPage);
    }

    public function getRundownsByDate(string $date): Collection
    {
        return $this->rundownRepository->getByDate($date);
    }

    public function findRundownById(int $id): ?Rundown
    {
        return $this->rundownRepository->findById($id);
    }

    public function createRundown(array $data): Rundown
    {
        return $this->rundownRepository->create($data);
    }

    public function updateRundown(int $id, array $data): Rundown
    {
        $this->rundownRepository->update($id, $data);
        return $this->findRundownById($id);
    }

    public function deleteRundown(int $id): void
    {
        $this->rundownRepository->delete($id);
    }

    public function publishRundown(int $id): Rundown
    {
        $this->rundownRepository->update($id, ['status' => 'published']);
        return $this->findRundownById($id);
    }

    public function completeRundown(int $id): Rundown
    {
        $this->rundownRepository->update($id, ['status' => 'completed']);
        return $this->findRundownById($id);
    }

    public function getRundownStats(): array
    {
        return $this->rundownRepository->getStats();
    }

    public function getRundownMapData(int $id): array
    {
        $rundown = $this->findRundownById($id);
        if (!$rundown) {
            throw new \Exception('Rundown tidak ditemukan');
        }

        $places = $rundown->places->map(function ($place) {
            return [
                'id' => $place->id,
                'name' => $place->name,
                'latitude' => $place->latitude,
                'longitude' => $place->longitude,
                'address' => $place->address,
                'category' => $place->category->name ?? 'Uncategorized',
            ];
        });

        return [
            'rundown' => [
                'id' => $rundown->id,
                'title' => $rundown->title,
                'date' => $rundown->date,
                'status' => $rundown->status,
            ],
            'places' => $places,
            'center' => $this->calculateMapCenter($rundown->places),
        ];
    }

    private function calculateMapCenter(Collection $places): array
    {
        if ($places->isEmpty()) {
            return [
                'lat' => -6.2088, // Jakarta coordinates as default
                'lng' => 106.8456,
            ];
        }

        $latitudes = $places->pluck('latitude');
        $longitudes = $places->pluck('longitude');

        return [
            'lat' => $latitudes->avg(),
            'lng' => $longitudes->avg(),
        ];
    }

    public function searchRundowns(string $query, int $limit = 20): Collection
    {
        return $this->rundownRepository->search($query, $limit);
    }

    public function getUpcomingRundowns(int $limit = 10): Collection
    {
        return $this->rundownRepository->getUpcoming($limit);
    }
}