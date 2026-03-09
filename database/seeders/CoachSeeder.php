<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Coach;
use App\Models\Cabor;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class CoachSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $cabors = Cabor::all();

        if ($cabors->isEmpty()) {
            $this->command->error('Cabor data empty. Please run CaborSeeder first.');
            return;
        }

        $targetCount = 20;

        for ($i = 1; $i <= $targetCount; $i++) {
            $username = 'coach_' . $i;

            // Skip if user already exists
            if (User::where('username', $username)->exists()) {
                if (Coach::whereHas('user', fn($q) => $q->where('username', $username))->exists()) {
                    continue;
                }
                $user = User::where('username', $username)->first();
            } else {
                $name = $faker->name;
                $user = User::create([
                    'name' => $name,
                    'username' => $username,
                    'email' => $faker->unique()->safeEmail,
                    'password' => Hash::make('123123123'),
                    'email_verified_at' => now(),
                    'is_active' => 1,
                ]);
                $user->assignRole('Pelatih');
            }

            Coach::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'cabor_id' => $cabors->random()->id,
                    'name' => $user->name,
                    'nik' => $faker->numerify('################'),
                    'birth_place' => $faker->city,
                    'birth_date' => $faker->date('Y-m-d', '1990-01-01'),
                    'religion' => $faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu']),
                    'gender' => $faker->randomElement(['L', 'P']),
                    'address' => $faker->address,
                    'blood_type' => $faker->randomElement(['A', 'B', 'AB', 'O']),
                    'last_education' => $faker->randomElement(['S1 Pendidikan Olahraga', 'S2 Kepelatihan', 'Diploma Olahraga']),
                    'is_active' => 1,
                ]
            );
        }

        $this->command->info('Coach expansion seeded successfully.');
    }
}
