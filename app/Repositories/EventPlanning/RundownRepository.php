<?php

namespace App\Repositories\EventPlanning;

use App\Models\EventPlanning\Rundown;
use App\Repositories\EventPlanning\Contracts\RundownRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class RundownRepository implements RundownRepositoryInterface
{
    protected Rundown $model;

    public function __construct(Rundown $model)
    {
        $this->model = $model;
    }

    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with(['activities', 'places'])
                           ->orderBy('date', 'desc')
                           ->orderBy('created_at', 'desc')
                           ->paginate($perPage);
    }

    public function findById(int $id): ?Rundown
    {
        return $this->model->with(['activities.place'])->find($id);
    }

    public function create(array $data): Rundown
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

    public function getByDate(string $date): Collection
    {
        return $this->model->with(['activities.place'])
                           ->forDate($date)
                           ->orderBy('date')
                           ->get();
    }

    public function getStats(): array
    {
        return [
            'total' => $this->model->count(),
            'published' => $this->model->where('status', 'published')->count(),
            'completed' => $this->model->where('status', 'completed')->count(),
            'draft' => $this->model->where('status', 'draft')->count(),
            'today' => $this->model->forDate(today())->count(),
        ];
    }

    public function search(string $query, int $limit = 20): Collection
    {
        return $this->model->where('title', 'like', "%{$query}%")
                           ->orWhere('description', 'like', "%{$query}%")
                           ->with(['activities', 'places'])
                           ->limit($limit)
                           ->get();
    }

    public function getUpcoming(int $limit = 10): Collection
    {
        return $this->model->where('date', '>=', today())
                           ->where('status', 'published')
                           ->with(['activities.place'])
                           ->orderBy('date')
                           ->limit($limit)
                           ->get();
    }
}
