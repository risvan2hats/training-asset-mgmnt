<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetHistoryFactory extends Factory
{
    public function definition()
    {
        $action = $this->faker->randomElement(['created', 'updated', 'moved']);
        
        $data = [
            'asset_id' => Asset::factory(),
            'user_id' => User::factory(),
            'action' => $action,
        ];

        if ($action === 'moved') {
            $data['from_location_id'] = Location::factory();
            $data['to_location_id'] = Location::factory();
        } else {
            $data['old_data'] = json_encode(['sample' => 'old data']);
            $data['new_data'] = json_encode(['sample' => 'new data']);
        }

        return $data;
    }
}