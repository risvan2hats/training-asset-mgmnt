<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetFactory extends Factory
{
    public function definition()
    {
        return [
            'serial_no' => $this->faker->unique()->bothify('SN-####-????'),
            'asset_type' => $this->faker->randomElement(['Laptop', 'Desktop', 'Monitor', 'Printer']),
            'hardware_standard' => $this->faker->randomElement(['Standard', 'Premium', 'Enterprise']),
            'location_id' => Location::factory(),
            'asset_value' => $this->faker->randomFloat(2, 100, 5000),
            'assigned_to' => $this->faker->randomElement([null, User::factory()]),
            'country_code' => $this->faker->randomElement(['US', 'UK', 'IN', 'CA']),
        ];
    }
}