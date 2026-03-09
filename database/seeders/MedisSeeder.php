<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Medis;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class MedisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $targetCount = 10;

        for ($i = 1; $i <= $targetCount; $i++) {
            $username = 'medis_' . $i;

            // Skip if user already exists
            if (User::where('username', $username)->exists()) {
                // If medis profile also exists, we're done for this index
                if (Medis::whereHas('user', fn($q) => $q->where('username', $username))->exists()) {
                    continue;
                }
                // If user exists but no medis profile, we'll use this user
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
                $user->assignRole('Medis');
            }

            $klasifikasi = $faker->randomElement(['dokter', 'perawat', 'masseur', 'fisioterapi', 'nutrisionis', 'psikolog']);

            Medis::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $user->name,
                    'klasifikasi' => $klasifikasi,
                    'nik' => $faker->numerify('################'),
                    'birth_place' => $faker->city,
                    'birth_date' => $faker->date('Y-m-d', '1995-12-31'),
                    'religion' => $faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu']),
                    'gender' => $faker->randomElement(['L', 'P']),
                    'address' => $faker->address,
                    'blood_type' => $faker->randomElement(['A', 'B', 'AB', 'O']),
                    'last_education' => $faker->randomElement(['S1 Kedokteran', 'S1 Keperawatan', 'S1 Psikiater']),
                    'is_active' => 1,
                ]
            );
        }

        $this->command->info('Medis expansion seeded successfully.');
    }
}
