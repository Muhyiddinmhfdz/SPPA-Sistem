<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Atlet;
use App\Models\PembinaanPrestasi;
use App\Models\PembinaanPrestasiDetail;
use App\Models\TrainingType;
use App\Models\TrainingTypeComponent;
use Illuminate\Support\Facades\DB;

class PembinaanPrestasiSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks for truncation to allow a clean rebuild
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        PembinaanPrestasiDetail::truncate();
        PembinaanPrestasi::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = \Faker\Factory::create('id_ID');
        $atlets = Atlet::all();

        if ($atlets->isEmpty()) {
            return;
        }

        foreach ($atlets as $atlet) {
            // Create 3-5 programs per athlete for more complexity
            $programCount = $faker->numberBetween(3, 5);

            for ($i = 0; $i < $programCount; $i++) {
                $pembinaan = PembinaanPrestasi::create([
                    'atlet_id' => $atlet->id,
                    'tanggal' => $faker->dateTimeBetween('-3 months', 'now'),
                    'periodesasi_latihan' => $faker->randomElement(['harian', 'mingguan', 'bulanan']),
                    'intensitas_latihan' => $faker->randomElement(['ringan', 'sedang', 'berat']),
                    'target_performa' => $faker->randomElement([
                        "Peningkatan " . $faker->word() . " sebesar 10%",
                        "Stabilisasi teknik pada fase " . $faker->word(),
                        "Pencapaian limit nasional untuk event " . $faker->word(),
                        "Pemulihan kondisi fisik pasca kompetisi",
                        "Persiapan taktik menghadapi lawan tipe " . $faker->word()
                    ]),
                ]);

                // Find all training types for this athlete's cabor
                $types = TrainingType::with('components.scores')->where('cabor_id', $atlet->cabor_id)->get();

                foreach ($types as $type) {
                    // Pick 2-3 random components from each type
                    $components = $type->components->random(min(3, $type->components->count()));

                    foreach ($components as $comp) {
                        $name = strtolower($comp->name);

                        // Realistic value generation based on component name
                        if (str_contains($name, 'endurance') || str_contains($name, 'daya tahan') || str_contains($name, 'aerobic')) {
                            $value = $faker->numberBetween(1500, 2600); // meters or scale
                        } elseif (str_contains($name, 'strength') || str_contains($name, 'kekuatan') || str_contains($name, 'power')) {
                            $value = $faker->numberBetween(50, 150); // kg or scale
                        } elseif (str_contains($name, 'speed') || str_contains($name, 'kecepatan') || str_contains($name, 'agility')) {
                            $value = $faker->numberBetween(60, 100); // scale 0-100
                        } elseif (str_contains($name, 'percentage') || str_contains($name, 'accuracy') || str_contains($name, 'teknik') || str_contains($name, 'taktik') || str_contains($name, 'mental')) {
                            $value = $faker->numberBetween(50, 98); // percentage 0-100
                        } else {
                            $value = $faker->randomFloat(2, 4, 10); // scale 1-10
                        }

                        // Calculate score based on actual criteria
                        $score = null;
                        $criteria = $comp->scores->sortByDesc('score');
                        foreach ($criteria as $criterion) {
                            $min = $criterion->min_value;
                            $max = $criterion->max_value;

                            if (($min === null || $value >= $min) && ($max === null || $value <= $max)) {
                                $score = $criterion->score;
                                break;
                            }
                        }

                        PembinaanPrestasiDetail::create([
                            'pembinaan_prestasi_id' => $pembinaan->id,
                            'training_type_component_id' => $comp->id,
                            'value' => $value,
                            'score' => $score,
                        ]);
                    }
                }
            }
        }
    }
}
