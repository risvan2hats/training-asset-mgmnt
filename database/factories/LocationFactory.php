<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->company(),
            'address' => $this->faker->address(),
            'floor_number' => $this->faker->randomElement([null, $this->faker->buildingNumber()]),
            'country_code' => $this->faker->randomElement(['US', 'UK', 'IN', 'CA']),
        ];
    }
}