<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MonitoringLatihan;
use App\Models\Atlet;
use App\Models\Coach;
use App\Models\User;
use Faker\Factory as Faker;

class MonitoringLatihanSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $atlets = Atlet::all();
        $coaches = Coach::all();
        $adminId = User::where('username', 'superadmin')->first()?->id ?? 1;

        // Seed for Athletes
        foreach ($atlets as $atlet) {
            // Seed for the last 30 days
            for ($i = 0; $i < 30; $i++) {
                $date = now()->subDays($i);
                // 80% chance of having a record
                if ($faker->boolean(80)) {
                    $kehadiran = $faker->randomElement(['hadir', 'hadir', 'hadir', 'hadir', 'tidak_hadir', 'izin', 'sakit']);
                    MonitoringLatihan::create([
                        'person_type' => 'atlet',
                        'person_id' => $atlet->id,
                        'cabor_id' => $atlet->cabor_id,
                        'tanggal' => $date->format('Y-m-d'),
                        'kehadiran' => $kehadiran,
                        'durasi_latihan' => $kehadiran === 'hadir' ? $faker->randomElement(['01:30', '02:00', '02:30', '03:00']) : '00:00',
                        'beban_latihan' => $faker->randomElement(['ringan', 'sedang', 'berat']),
                        'denyut_nadi_rpe' => $kehadiran === 'hadir' ? $faker->numberBetween(120, 180) . ' bpm / RPE ' . $faker->numberBetween(6, 10) : null,
                        'catatan_pelatih' => $faker->optional(0.4)->sentence,
                        'kesimpulan' => $faker->randomElement(['ya', 'tidak']),
                        'dicatat_oleh' => $adminId,
                        'is_active' => 1,
                    ]);
                }
            }
        }

        // Seed for Coaches
        foreach ($coaches as $coach) {
            for ($i = 0; $i < 10; $i++) {
                $date = now()->subDays($i);
                MonitoringLatihan::create([
                    'person_type' => 'pelatih',
                    'person_id' => $coach->id,
                    'cabor_id' => $coach->cabor_id,
                    'tanggal' => $date->format('Y-m-d'),
                    'kehadiran' => 'hadir',
                    'durasi_latihan' => '04:00',
                    'beban_latihan' => 'sedang',
                    'denyut_nadi_rpe' => 'N/A',
                    'catatan_pelatih' => 'Melatih sesi rutin',
                    'kesimpulan' => 'ya',
                    'dicatat_oleh' => $adminId,
                    'is_active' => 1,
                ]);
            }
        }
    }
}
