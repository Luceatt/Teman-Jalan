<?php

namespace Database\Factories\EventPlanning;

use App\Models\EventPlanning\Activity;
use App\Models\EventPlanning\Rundown;
use App\Models\Location\Place;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition(): array
    {
        return [
            'rundown_id' => Rundown::factory(),
            'place_id' => Place::factory(),
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->sentence,
            'start_time' => $this->faker->dateTimeBetween('now', '+1 day'),
            'end_time' => $this->faker->dateTimeBetween('+1 day', '+2 days'),
        ];
    }
}
