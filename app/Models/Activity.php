<?php

namespace App\Models;

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

    public function event()
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
}
