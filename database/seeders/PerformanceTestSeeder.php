<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Atlet;
use App\Models\Cabor;
use App\Models\PerformanceTest;
use App\Models\PerformanceTestResult;
use App\Models\PhysicalTestCategory;
use App\Models\PhysicalTestItemScore;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PerformanceTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Clean existing data to avoid duplication and ensure clean stats
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        PerformanceTestResult::truncate();
        PerformanceTest::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Get all active athletes
        $atlets = Atlet::where('is_active', 1)->with(['cabor', 'jenisDisabilitas', 'klasifikasi_disabilitas'])->get();
        if ($atlets->isEmpty()) {
            $this->command->info('No active athletes found. Skipping PerformanceTestSeeder.');
            return;
        }

        $statusOptions = ['fit', 'cidera', 'rehabilitasi'];
        $alatBantuOptions = ['prothesis', 'orthosis', 'wheelchair_manual', 'wheelchair_racing', null];
        $pengujiOptions = [
            'Dr. Budi Santoso, Sp.KO', 
            'Dra. Sari Dewi, M.Pd', 
            'Dr. Agus Wahyono, M.Or', 
            'Tim Medis NPCI Pusat', 
            'Lembaga Performa Atlet Nasional'
        ];

        $this->command->info('Seeding Performance Tests for ' . $atlets->count() . ' athletes...');

        // 3. Create test sessions for each athlete
        foreach ($atlets as $atlet) {
            // Get categories and items applicable for this athlete's cabor and disabilitas
            $categories = PhysicalTestCategory::where('cabor_id', $atlet->cabor_id)
                ->where('is_active', 1)
                ->with(['items' => function ($q) use ($atlet) {
                    $q->where('is_active', 1)
                        ->where(function ($q2) use ($atlet) {
                            $q2->whereNull('jenis_disabilitas_id')
                              ->orWhere('jenis_disabilitas_id', $atlet->jenis_disabilitas_id);
                        })
                        ->with(['scores' => fn($s) => $s->where('is_active', 1)]);
                }])
                ->get();

            if ($categories->isEmpty()) continue;

            // Determine number of test sessions (2-4 sessions in the last 6 months)
            $sessionCount = rand(2, 4);
            
            for ($i = 0; $i < $sessionCount; $i++) {
                // Ensure dates are chronological if multiple sessions
                $date = Carbon::now()->subMonths($sessionCount - $i)->subDays(rand(1, 25));
                
                // Status tends to be 'fit' more often (80% chance)
                $statusRoll = rand(1, 100);
                $status = 'fit';
                if ($statusRoll > 90) $status = 'cidera';
                elseif ($statusRoll > 80) $status = 'rehabilitasi';

                $test = PerformanceTest::create([
                    'atlet_id' => $atlet->id,
                    'cabor_id' => $atlet->cabor_id,
                    'klasifikasi_disabilitas_id' => $atlet->klasifikasi_disabilitas_id,
                    'jenis_disabilitas_id' => $atlet->jenis_disabilitas_id,
                    'alat_bantu' => ($atlet->jenis_disabilitas_id && rand(0, 1)) ? $alatBantuOptions[array_rand($alatBantuOptions)] : null,
                    'status_kesehatan' => $status,
                    'tanggal_pelaksanaan' => $date,
                    'spesialisasi' => $atlet->cabor?->name . ' - Kelas ' . ($atlet->klasifikasi_disabilitas?->kode_klasifikasi ?? 'Umum'),
                    'penguji' => $pengujiOptions[array_rand($pengujiOptions)],
                    'is_active' => 1,
                ]);

                // 4. Generate results for each applicable item
                foreach ($categories as $category) {
                    foreach ($category->items as $item) {
                        $scores = $item->scores;
                        if ($scores->isEmpty()) {
                            // Default value if no scoring rules exist
                            PerformanceTestResult::create([
                                'performance_test_id' => $test->id,
                                'physical_test_item_id' => $item->id,
                                'nilai' => rand(10, 90),
                                'is_active' => 1,
                            ]);
                            continue;
                        }

                        // Pick a score rule (favoring better scores for 'fit' athletes)
                        $randomScore = $scores->random();
                        if ($status == 'fit' && rand(1, 10) > 3) {
                            // Find highest score if athlete is fit
                            $randomScore = $scores->sortByDesc('score')->first();
                        }

                        // Generate realistic value within the score range
                        $nilai = $this->generateRealisticValue($randomScore);

                        PerformanceTestResult::create([
                            'performance_test_id' => $test->id,
                            'physical_test_item_id' => $item->id,
                            'nilai' => $nilai,
                            'physical_test_item_score_id' => $randomScore->id,
                            'is_active' => 1,
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Helper to generate a value within the min/max range of a score rule
     */
    private function generateRealisticValue($scoreRule)
    {
        $min = $scoreRule->min_value;
        $max = $scoreRule->max_value;

        if ($min !== null && $max !== null) {
            // Handle precision if it looks like float (e.g. 4.5)
            if (is_float($min) || is_float($max) || (rand(0, 1) && $max - $min < 10)) {
                return round($min + ($max - $min) * (rand(0, 100) / 100), 2);
            }
            return rand((int)$min, (int)$max);
        } elseif ($min !== null) {
            return $min + rand(1, 20);
        } elseif ($max !== null) {
            return max(0, $max - rand(1, 20));
        }
        
        return rand(1, 100);
    }
}
