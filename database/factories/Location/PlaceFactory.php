<?php

namespace Database\Factories\Location;

use App\Models\Location\Place;
use App\Models\Location\PlaceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlaceFactory extends Factory
{
    protected $model = Place::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        // Jakarta coordinates for testing
        $jakartaLatitude = -6.2088;
        $jakartaLongitude = 106.8456;

        return [
            'name' => $this->faker->company . ' ' . $this->faker->randomElement(['Restaurant', 'Cafe', 'Shop', 'Center', 'Plaza']),
            'description' => $this->faker->paragraph(3),
            'address' => $this->faker->address,
            'latitude' => $this->faker->latitude($jakartaLatitude - 0.1, $jakartaLatitude + 0.1),
            'longitude' => $this->faker->longitude($jakartaLongitude - 0.1, $jakartaLongitude + 0.1),
            'category_id' => PlaceCategory::factory(),
            'image' => null, // Will be set in specific tests if needed
            'is_active' => $this->faker->boolean(95), // 95% chance of being active
        ];
    }

    /**
     * Indicate that the place is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the place is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Create a place with a specific category.
     */
    public function forCategory(PlaceCategory $category): static
    {
        return $this->state(fn (array $attributes) => [
            'category_id' => $category->id,
        ]);
    }

    /**
     * Create a place in Jakarta city center.
     */
    public function inJakarta(): static
    {
        return $this->state(fn (array $attributes) => [
            'latitude' => $this->faker->latitude(-6.2, -6.1),
            'longitude' => $this->faker->longitude(106.8, 106.9),
            'address' => $this->faker->streetAddress . ', Jakarta, Indonesia',
        ]);
    }

    /**
     * Create a place with an image.
     */
    public function withImage(): static
    {
        return $this->state(fn (array $attributes) => [
            'image' => 'places/' . $this->faker->uuid . '.jpg',
        ]);
    }
}