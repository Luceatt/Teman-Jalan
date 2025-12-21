<?php

namespace App\Models\EventPlanning;

use App\Models\Location\Place;
use Database\Factories\EventPlanning\ActivityFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_time',
        'end_time',
        'duration',
        'place_id',
        'rundown_id',
        'order',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected static function newFactory(): ActivityFactory
    {
        return ActivityFactory::new();
    }

    /**
     * Get the place that owns the activity.
     */
    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class, 'place_id');
    }

    /**
     * Get the rundown that owns the activity.
     */
    public function rundown(): BelongsTo
    {
        return $this->belongsTo(Rundown::class, 'rundown_id');
    }

    /**
     * Scope a query to only include active activities.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order activities by their order field.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('start_time');
    }

    /**
     * Get the duration in minutes.
     */
    public function getDurationInMinutes(): int
    {
        if (!$this->start_time || !$this->end_time) {
            return 0;
        }

        return $this->start_time->diffInMinutes($this->end_time);
    }

    /**
     * Check if the activity is currently ongoing.
     */
    public function isOngoing(): bool
    {
        $now = now();
        return $this->start_time <= $now && $this->end_time >= $now;
    }

    /**
     * Check if the activity is in the past.
     */
    public function isPast(): bool
    {
        return $this->end_time < now();
    }

    /**
     * Check if the activity is in the future.
     */
    public function isUpcoming(): bool
    {
        return $this->start_time > now();
    }
}
