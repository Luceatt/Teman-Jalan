<?php

namespace Database\Factories\Features\Location\Models;

use App\Features\Location\Models\PlaceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlaceCategoryFactory extends Factory
{
    protected $model = \App\Features\Location\Models\PlaceCategory::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $categories = [
            'Restaurant',
            'Cafe',
            'Entertainment',
            'Shopping',
            'Outdoor',
            'Education',
            'Healthcare',
            'Sports',
            'Religious',
            'Transportation',
        ];

        $icons = ['🍽️', '☕', '🎭', '🛍️', '🏞️', '📚', '🏥', '⚽', '⛪', '🚗'];
        $colors = ['#EF4444', '#8B5CF6', '#F59E0B', '#10B981', '#3B82F6', '#6366F1', '#EC4899', '#F97316', '#6B7280', '#059669'];

        $name = $this->faker->randomElement($categories);
        $key = array_search($name, $categories);

        return [
            'name' => $name,
            'icon' => $icons[$key] ?? '📍',
            'color_code' => $colors[$key] ?? '#6B7280',
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
        ];
    }

    /**
     * Indicate that the category is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the category is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}