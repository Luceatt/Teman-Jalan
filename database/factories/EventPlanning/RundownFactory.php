<?php

namespace Database\Factories\EventPlanning;

use App\Models\EventPlanning\Rundown;
use Illuminate\Database\Eloquent\Factories\Factory;

class RundownFactory extends Factory
{
    protected $model = Rundown::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'date' => $this->faker->date(),
            'status' => 'draft',
        ];
    }
}
