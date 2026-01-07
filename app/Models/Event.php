<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $primaryKey = 'event_id';
    public $timestamps = false;

    protected $fillable = [
        'event_name',
        'description',
        'event_date',
        'creator_id',
        'status'
    ];

    protected $casts = [
        'event_date' => 'date'
    ];

    // Accessors for Rundown compatibility (views use these names)
    public function getIdAttribute()
    {
        return $this->event_id;
    }

    public function getTitleAttribute()
    {
        return $this->event_name;
    }

    public function getDateAttribute()
    {
        return $this->event_date;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function participants()
    {
        return $this->hasMany(EventParticipant::class, 'event_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'event_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'event_id');
    }

    /**
     * Get the places associated with this event through activities.
     */
    public function places()
    {
        return $this->hasManyThrough(
            Place::class,
            Activity::class,
            'event_id',
            'place_id',
            'event_id',
            'place_id'
        );
    }

    /**
     * Get the total duration of all activities in minutes.
     */
    public function getTotalDurationInMinutes(): int
    {
        $total = 0;
        foreach ($this->activities as $activity) {
            if ($activity->start_time && $activity->end_time) {
                $start = \Carbon\Carbon::parse($activity->start_time);
                $end = \Carbon\Carbon::parse($activity->end_time);
                $total += $start->diffInMinutes($end);
            }
        }
        return $total;
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
     * Get the status label in Indonesian.
     */
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PLANNED = 'planned';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the status label in Indonesian.
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PLANNED => 'Direncanakan',
            self::STATUS_CONFIRMED => 'Dikonfirmasi',
            self::STATUS_PUBLISHED => 'Diterbitkan',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Get the status badge color for UI.
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            self::STATUS_DRAFT => 'gray',
            self::STATUS_PLANNED => 'yellow',
            self::STATUS_CONFIRMED => 'blue',
            self::STATUS_PUBLISHED => 'green',
            self::STATUS_COMPLETED => 'purple',
            self::STATUS_CANCELLED => 'red',
            default => 'gray',
        };
    }

    /**
     * Scope a query to only include events with a specific status.
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include events for the current user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('creator_id', $userId);
    }

    /**
     * Scope a query to only include events for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('event_date', $date);
    }
}
