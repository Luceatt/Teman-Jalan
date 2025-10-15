<?php

namespace App\Features\Location\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlaceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'color_code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the places for the category.
     */
    public function places(): HasMany
    {
        return $this->hasMany(\App\Features\Location\Models\Place::class, 'category_id');
    }

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}