<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $primaryKey = 'activity_id';
    public $timestamps = false;

    protected $fillable = [
        'event_id',
        'place_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'order_number'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime'
    ];

    // Accessor for views that use 'name' instead of 'title'
    public function getNameAttribute()
    {
        return $this->title;
    }

    // Accessor for views that use 'id' instead of 'activity_id'
    public function getIdAttribute()
    {
        return $this->activity_id;
    }

    // Accessor for views using 'rundown_id'
    public function getRundownIdAttribute()
    {
        return $this->event_id;
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    // Alias for event() - views might use 'rundown'
    public function rundown()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'activity_id');
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
        if (!$this->start_time || !$this->end_time) {
            return false;
        }

        $now = now();
        return $this->start_time <= $now && $this->end_time >= $now;
    }

    /**
     * Check if the activity is in the past.
     */
    public function isPast(): bool
    {
        if (!$this->end_time) {
            return false;
        }
        return $this->end_time < now();
    }

    /**
     * Check if the activity is in the future.
     */
    public function isUpcoming(): bool
    {
        if (!$this->start_time) {
            return false;
        }
        return $this->start_time > now();
    }
}
