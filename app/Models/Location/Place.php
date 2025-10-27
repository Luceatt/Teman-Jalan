<?php

namespace App\Models\Location;

use App\Models\EventPlanning\Activity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address',
        'latitude',
        'longitude',
        'category_id',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    /**
     * Get the category that owns the place.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(PlaceCategory::class, 'category_id');
    }

    /**
     * Get the activities for the place.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Scope a query to only include active places.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to search places by name or description.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }
}
