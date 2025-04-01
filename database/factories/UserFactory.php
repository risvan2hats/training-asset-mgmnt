<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'employee_id' => 'EMP' . $this->faker->unique()->numberBetween(1000, 9999),
            'hire_date' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'position' => $this->faker->jobTitle(),
            'country_code' => $this->faker->randomElement(['US', 'UK', 'IN', 'CA']),
        ];
    }
}