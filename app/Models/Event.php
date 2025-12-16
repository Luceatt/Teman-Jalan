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
}
