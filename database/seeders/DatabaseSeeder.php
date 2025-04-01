<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Asset;
use App\Models\Location;
use App\Models\AssetHistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create super admin if not exists
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'password' => Hash::make('password'),
                'employee_id' => 'ADMIN001',
                'hire_date' => now(),
                'position' => 'System Administrator',
                'country_code' => 'US',
                'email_verified_at' => now(),
            ]
        );

        // Create regular users if none exist
        if (User::count() <= 1) { // 1 for the admin we just created
            User::factory()->count(10)->create();
        }

        // Create locations if none exist
        if (Location::count() === 0) {
            Location::factory()->count(5)->create(['country_code' => 'US']);
            Location::factory()->count(5)->create(['country_code' => 'UK']);
            Location::factory()->count(5)->create(['country_code' => 'IN']);
            Location::factory()->count(5)->create(['country_code' => 'CA']);
        }

        // Create assets if none exist
        if (Asset::count() === 0) {
            Asset::factory()->count(50)->create();
        }

        // Create asset histories if none exist
        if (AssetHistory::count() === 0) {
            Asset::all()->each(function ($asset) {
                AssetHistory::factory()->create([
                    'asset_id' => $asset->id,
                    'user_id' => User::inRandomOrder()->first()->id,
                    'action' => 'created',
                    'new_data' => $asset->toArray(),
                ]);

                if (rand(0, 1)) {
                    AssetHistory::factory()->count(rand(1, 3))->create([
                        'asset_id' => $asset->id,
                        'user_id' => User::inRandomOrder()->first()->id,
                        'action' => 'updated',
                        'old_data' => $asset->toArray(),
                        'new_data' => $asset->toArray(),
                    ]);
                }

                if (rand(0, 1)) {
                    $newLocation = Location::where('id', '!=', $asset->location_id)
                        ->inRandomOrder()
                        ->first();
                    
                    AssetHistory::factory()->create([
                        'asset_id' => $asset->id,
                        'user_id' => User::inRandomOrder()->first()->id,
                        'action' => 'moved',
                        'from_location_id' => $asset->location_id,
                        'to_location_id' => $newLocation->id,
                    ]);
                }
            });
        }
    }
}