<?php

namespace App\Models\EventPlanning;

use App\Models\Location\Place;
use Database\Factories\EventPlanning\RundownFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rundown extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date',
        'status',
        'notes',
        'metadata',
        'is_public',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'metadata' => 'array',
        'is_public' => 'boolean',
    ];

    protected static function newFactory(): RundownFactory
    {
        return RundownFactory::new();
    }

    /**
     * Get the activities for the rundown.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class)->ordered();
    }

    /**
     * Get the places associated with this rundown through activities.
     */
    public function places()
    {
        return $this->hasManyThrough(
            Place::class,
            Activity::class,
            'rundown_id',
            'id',
            'id',
            'place_id'
        );
    }

    /**
     * Scope a query to only include rundowns with a specific status.
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include public rundowns.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope a query to only include rundowns for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    /**
     * Get the total duration of all activities in minutes.
     */
    public function getTotalDurationInMinutes(): int
    {
        return $this->activities->sum('duration_in_minutes');
    }

    /**
     * Get the formatted total duration.
     */
    public function getFormattedTotalDuration(): string
    {
        $totalMinutes = $this->getTotalDurationInMinutes();

        if ($totalMinutes < 60) {
            return $totalMinutes . ' menit';
        }

        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        if ($minutes === 0) {
            return $hours . ' jam';
        }

        return $hours . ' jam ' . $minutes . ' menit';
    }

    /**
     * Get the start time of the first activity.
     */
    public function getStartTime()
    {
        $firstActivity = $this->activities()->orderBy('start_time')->first();
        return $firstActivity?->start_time;
    }

    /**
     * Get the end time of the last activity.
     */
    public function getEndTime()
    {
        $lastActivity = $this->activities()->orderBy('end_time', 'desc')->first();
        return $lastActivity?->end_time;
    }

    /**
     * Check if the rundown is currently ongoing.
     */
    public function isOngoing(): bool
    {
        $now = now();
        $startTime = $this->getStartTime();
        $endTime = $this->getEndTime();

        if (!$startTime || !$endTime) {
            return false;
        }

        return $startTime <= $now && $endTime >= $now;
    }

    /**
     * Check if the rundown is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the rundown is published.
     */
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Get the status badge color for UI.
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            'draft' => 'gray',
            'published' => 'green',
            'completed' => 'blue',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get the status label in Indonesian.
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'published' => 'Diterbitkan',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Tidak Diketahui',
        };
    }
}
