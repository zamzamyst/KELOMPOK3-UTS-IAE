<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BusRoute>
 */
class BusRouteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->regexify('[A-Z]{2}-[0-9]{2}'),
            'origin' => $this->faker->city,
            'destination' => $this->faker->city,
            'stops' => json_encode([$this->faker->city, $this->faker->city]),
        ];
    }
}
