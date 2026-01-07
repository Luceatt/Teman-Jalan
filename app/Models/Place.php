<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $primaryKey = 'place_id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
        'description',
        'category',
        'is_active',
        'image'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the available categories.
     * 
     * @return array
     */
    public static function getCategories()
    {
        return ['Nature', 'Restaurant', 'Hotel', 'Public', 'Other'];
    }



    /**
     * Accessor for views that use 'id' instead of 'place_id'.
     */
    public function getIdAttribute()
    {
        return $this->place_id;
    }

    /**
     * Get the activities at this place.
     */
    public function activities()
    {
        return $this->hasMany(Activity::class, 'place_id');
    }

    /**
     * Get the events at this place through activities.
     */
    public function events()
    {
        return $this->hasManyThrough(
            Event::class,
            Activity::class,
            'place_id',
            'event_id',
            'place_id',
            'event_id'
        );
    }
}