<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kompetisi;
use App\Models\Atlet;
use App\Models\User;
use App\Models\Cabor;
use Faker\Factory as Faker;

class KompetisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $atlets = Atlet::with('cabor')->get();
        $adminId = User::where('username', 'superadmin')->first()?->id ?? 1;

        if ($atlets->isEmpty()) {
            $this->command->warn('No athletes found. Please seed athletes first.');
            return;
        }

        // Truncate existing competitions for a clean seed with Indonesian names
        Kompetisi::truncate();

        $tingkatans = ['Internasional', 'Nasional', 'Daerah'];
        $medalis = ['emas', 'perak', 'perunggu', 'tanpa_medali'];

        $kompetisiTemplates = [
            'Peparnas [Year]',
            'Kejurnas Para [Cabor] [Year]',
            'Peparpeda [Location] [Year]',
            'ASEAN Para Games [Location] [Year]',
            'Asian Para Games [Location] [Year]',
            'Paralympic Games [Location] [Year]',
            'Open Championship [Cabor] [Location] [Year]',
            'Piala Gubernur [Cabor] [Year]',
            'Walikota Cup [Location] [Year]'
        ];

        foreach ($atlets as $atlet) {
            // Create 3-10 competitions per athlete for "seeder yg banyak"
            $count = $faker->numberBetween(3, 10);
            $caborName = str_replace(['Para ', ' (', ')'], '', $atlet->cabor->name);
            $caborName = explode(' ', $caborName)[0]; // Just use the first part for variety

            for ($i = 0; $i < $count; $i++) {
                $template = $faker->randomElement($kompetisiTemplates);
                $name = str_replace(
                    ['[Year]', '[Cabor]', '[Location]'],
                    [$faker->dateTimeBetween('-4 years', 'now')->format('Y'), $caborName, $faker->city],
                    $template
                );

                Kompetisi::create([
                    'cabor_id' => $atlet->cabor_id,
                    'atlet_id' => $atlet->id,
                    'nama_kompetisi' => $name,
                    'tingkatan' => $faker->randomElement($tingkatans),
                    'tempat_pelaksanaan' => $faker->city,
                    'waktu_pelaksanaan' => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
                    'hasil_peringkat' => (string) $faker->numberBetween(1, 10),
                    'hasil_medali' => $faker->randomElement($medalis),
                    'kesimpulan_evaluasi' => $faker->paragraph,
                    'dicatat_oleh' => $adminId,
                ]);
            }
        }

        $this->command->info('Kompetisi seeded successfully with Indonesian names!');
    }
}
