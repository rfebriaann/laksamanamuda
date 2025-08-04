<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'event_name' => fake()->sentence(3),
            'event_description' => fake()->paragraph(),
            'venue_name' => fake()->company() . ' Hall',
            'venue_address' => fake()->address(),
            'event_date' => fake()->dateTimeBetween('now', '+3 months'),
            'start_time' => fake()->time('H:i:s'),
            'end_time' => fake()->time('H:i:s'),
            'event_image' => null,
            'is_active' => true,
            'created_by' => User::factory(),
        ];
    }
}