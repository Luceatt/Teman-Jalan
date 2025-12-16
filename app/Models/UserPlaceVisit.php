<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPlaceVisit extends Model
{
    use HasFactory;

    protected $primaryKey = 'visit_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'place_id',
        'visit_count',
        'last_visit_date'
    ];

    protected $casts = [
        'last_visit_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }
}