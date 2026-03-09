<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CekKesehatan;
use App\Models\Atlet;
use App\Models\Coach;
use App\Models\User;
use Faker\Factory as Faker;

class CekKesehatanSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $atlets = Atlet::all();
        $coaches = Coach::all();
        $adminId = User::where('username', 'superadmin')->first()?->id ?? 1;

        // Seed for Athletes
        foreach ($atlets as $atlet) {
            $count = $faker->numberBetween(3, 8);
            for ($i = 0; $i < $count; $i++) {
                $kondisi = $faker->randomElement(['sehat', 'lelah', 'cidera']);
                CekKesehatan::create([
                    'person_type' => 'atlet',
                    'person_id' => $atlet->id,
                    'cabor_id' => $atlet->cabor_id,
                    'tanggal' => $faker->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
                    'kondisi_harian' => $kondisi,
                    'tingkat_cedera' => $kondisi === 'cidera' ? $faker->randomElement(['ringan', 'sedang', 'berat']) : 'tidak_cidera',
                    'riwayat_medis' => $faker->optional(0.3)->sentence,
                    'kesimpulan' => $faker->randomElement(['baik', 'sedang', 'berat']),
                    'catatan' => $faker->optional(0.5)->sentence,
                    'dibuat_oleh' => $adminId,
                    'is_active' => 1,
                ]);
            }
        }

        // Seed for Coaches
        foreach ($coaches as $coach) {
            $count = $faker->numberBetween(1, 3);
            for ($i = 0; $i < $count; $i++) {
                CekKesehatan::create([
                    'person_type' => 'pelatih',
                    'person_id' => $coach->id,
                    'cabor_id' => $coach->cabor_id,
                    'tanggal' => $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
                    'kondisi_harian' => $faker->randomElement(['sehat', 'lelah']),
                    'tingkat_cedera' => 'tidak_cidera',
                    'riwayat_medis' => null,
                    'kesimpulan' => 'baik',
                    'catatan' => 'Pemeriksaan rutin pelatih',
                    'dibuat_oleh' => $adminId,
                    'is_active' => 1,
                ]);
            }
        }
    }
}
