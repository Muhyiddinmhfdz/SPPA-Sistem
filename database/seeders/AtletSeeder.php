<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Atlet;
use Illuminate\Support\Facades\Hash;
use App\Models\Cabor;
use App\Models\JenisDisabilitas;
use App\Models\KlasifikasiDisabilitas;

class AtletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        $cabors = Cabor::pluck('id')->toArray();
        $jenisDisabilitas = JenisDisabilitas::pluck('id')->toArray();
        $klasifikasi = KlasifikasiDisabilitas::pluck('id')->toArray();

        if (empty($cabors)) {
            $this->command->error('Cabor data empty. Please run CaborSeeder first.');
            return;
        }

        $targetCount = 30;
        $currentCount = Atlet::count();

        for ($i = $currentCount + 1; $i <= $targetCount; $i++) {
            $name = $faker->name;
            $username = strtolower(str_replace(' ', '_', $name)) . '_' . $i;

            $user = User::create([
                'name' => $name,
                'username' => $username,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('123123123'),
                'email_verified_at' => now(),
            ]);
            $user->assignRole('Atlet');

            Atlet::create([
                'user_id' => $user->id,
                'cabor_id' => $faker->randomElement($cabors),
                'jenis_disabilitas_id' => $faker->randomElement($jenisDisabilitas),
                'klasifikasi_disabilitas_id' => $faker->randomElement($klasifikasi),
                'name' => $name,
                'jenis_disabilitas' => 'Tuna ' . $faker->word,
                'nik' => $faker->numerify('################'),
                'birth_place' => $faker->city,
                'birth_date' => $faker->date('Y-m-d', '2005-12-31'),
                'religion' => $faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu']),
                'gender' => $faker->randomElement(['L', 'P']),
                'address' => $faker->address,
                'blood_type' => $faker->randomElement(['A', 'B', 'AB', 'O']),
                'last_education' => $faker->randomElement(['SMP', 'SMA', 'S1', 'Diploma']),
                'is_active' => 1,
            ]);
        }
    }
}
