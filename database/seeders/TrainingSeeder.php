<?php

namespace Database\Seeders;

use App\Models\Cabor;
use App\Models\TrainingType;
use App\Models\TrainingTypeComponent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks for truncation to allow a clean rebuild
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('training_type_component_scores')->truncate();
        TrainingTypeComponent::truncate();
        TrainingType::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $cabors = Cabor::all();

        $genericFisik = ['Daya Tahan', 'Kekuatan', 'Kecepatan', 'Kelincahan', 'Kelenturan'];
        $genericMental = ['Fokus', 'Motivasi', 'Regulasi Emosi', 'Visualisasi', 'Kepercayaan Diri'];

        foreach ($cabors as $cabor) {
            $caborName = strtolower($cabor->name);

            // Define Cabor Specific Components
            $specificTeknik = [];
            $specificTaktik = [];
            $specificFisik = $genericFisik;

            if (str_contains($caborName, 'renang')) {
                $specificTeknik = ['Streamline', 'Catch & Pull', 'Kick Technique', 'Flip Turn', 'Underwater Phase'];
                $specificTaktik = ['Pacing Strategy', 'Split Time Management', 'Finish Touch'];
                $specificFisik = ['Aerobic Endurance', 'Anaerobic Threshold', 'VO2 Max', 'Muscle Power'];
            } elseif (str_contains($caborName, 'bulutangkis')) {
                $specificTeknik = ['Serve (High/Low)', 'Forehand/Backhand Clear', 'Drop Shot', 'Smash', 'Netting', 'Footwork'];
                $specificTaktik = ['Attack Strategy', 'Deception', 'Placement', 'Rally Management'];
                $specificFisik = ['Agility', 'Explosive Power', 'Hand-Eye Coordination', 'Speed Endurance'];
            } elseif (str_contains($caborName, 'atletik')) {
                $specificTeknik = ['Start Block Phase', 'Reaction Time', 'Stretching & Mobility', 'Hand-over (Relay)', 'Landing Technique'];
                $specificTaktik = ['Running Lane Strategy', 'Energy Distribution', 'Head-to-head Strategy'];
            } elseif (str_contains($caborName, 'angkat berat')) {
                $specificTeknik = ['Grip Technique', 'Setup Phase', 'Ascent Phase', 'Lockout', 'Stability'];
                $specificTaktik = ['Attempt Strategy', 'Weight Progression', 'Psych-up Process'];
                $specificFisik = ['Max Strength', 'Core Stability', 'Muscle Hypertrophy'];
            } elseif (str_contains($caborName, 'tenis meja')) {
                $specificTeknik = ['Grip', 'Spin (Top/Back)', 'Loop', 'Block', 'Short Game/Touch'];
                $specificTaktik = ['Serve & Third Ball Attack', 'Variasi Spin', 'Placement Defense'];
            } elseif (str_contains($caborName, 'panahan')) {
                $specificTeknik = ['Stance', 'Nocking', 'Drawing', 'Anchoring', 'Aims & Release', 'Follow Through'];
                $specificTaktik = ['Wind Correction', 'Target Selection', 'Timing Management'];
                $specificFisik = ['Upper Body Stability', 'Static Endurance', 'Fine Motor Control'];
            } elseif (str_contains($caborName, 'catur')) {
                $specificTeknik = ['Opening Theory', 'Middle Game Calculation', 'End Game Technique', 'Puzzle Solving'];
                $specificTaktik = ['Time Management', 'Positional Play', 'Psychological Pressure'];
                $specificFisik = ['Brain Focus Endurance', 'Sitting Posture Stability'];
            } else {
                // Fallback for others
                $specificTeknik = ['Teknik Dasar', 'Teknik Lanjutan', 'Koordinasi'];
                $specificTaktik = ['Strategi Umum', 'Analisis Lawan', 'Transisi'];
            }

            $typesMap = [
                'Fisik' => $specificFisik,
                'Teknik' => $specificTeknik,
                'Taktik' => $specificTaktik,
                'Mental' => $genericMental,
            ];

            foreach ($typesMap as $typeName => $comps) {
                $type = TrainingType::create([
                    'cabor_id' => $cabor->id,
                    'name' => $typeName,
                ]);

                foreach ($comps as $compName) {
                    $comp = TrainingTypeComponent::create([
                        'training_type_id' => $type->id,
                        'name' => $compName,
                    ]);

                    $this->seedCriteria($comp);
                }
            }
        }
    }

    /**
     * Helper to seed criteria for a component based on its name.
     */
    private function seedCriteria(TrainingTypeComponent $comp)
    {
        $name = strtolower($comp->name);

        // FISIK - Endurance (Distance based)
        if (str_contains($name, 'endurance') || str_contains($name, 'daya tahan') || str_contains($name, 'aerobic')) {
            $comp->scores()->create(['min_value' => 2400, 'label' => 'Elite', 'score' => 4]);
            $comp->scores()->create(['min_value' => 2000, 'max_value' => 2399, 'label' => 'Sangat Baik', 'score' => 3]);
            $comp->scores()->create(['min_value' => 1600, 'max_value' => 1999, 'label' => 'Baik', 'score' => 2]);
            $comp->scores()->create(['max_value' => 1599, 'label' => 'Cukup', 'score' => 1]);
        }
        // FISIK - Strength (Weight/Kg based)
        elseif (str_contains($name, 'strength') || str_contains($name, 'kekuatan') || str_contains($name, 'power') || str_contains($name, 'hypertrophy')) {
            $comp->scores()->create(['min_value' => 120, 'label' => 'Elite', 'score' => 4]);
            $comp->scores()->create(['min_value' => 90, 'max_value' => 119, 'label' => 'Sangat Baik', 'score' => 3]);
            $comp->scores()->create(['min_value' => 60, 'max_value' => 89, 'label' => 'Baik', 'score' => 2]);
            $comp->scores()->create(['max_value' => 59, 'label' => 'Cukup', 'score' => 1]);
        }
        // FISIK - Agility/Speed (Time based - Inverse logic: lower time = higher score)
        // Note: For now we keep higher value = higher score because of controller logic, 
        // but we can adjust labels to be neutral.
        elseif (str_contains($name, 'agility') || str_contains($name, 'kelincahan') || str_contains($name, 'speed') || str_contains($name, 'kecepatan') || str_contains($name, 'reaction')) {
            $comp->scores()->create(['min_value' => 95, 'label' => 'Sangat Cepat', 'score' => 4]);
            $comp->scores()->create(['min_value' => 80, 'max_value' => 94, 'label' => 'Cepat', 'score' => 3]);
            $comp->scores()->create(['min_value' => 65, 'max_value' => 79, 'label' => 'Rata-rata', 'score' => 2]);
            $comp->scores()->create(['max_value' => 64, 'label' => 'Lambat', 'score' => 1]);
        }
        // TEKNIK - Accuracy / success rate (Percentage 0-100)
        elseif (str_contains($name, 'serve') || str_contains($name, 'smash') || str_contains($name, 'shot') || str_contains($name, 'aim') || str_contains($name, 'accuracy') || str_contains($name, 'technique')) {
            $comp->scores()->create(['min_value' => 90, 'label' => 'Sempurna', 'score' => 4]);
            $comp->scores()->create(['min_value' => 75, 'max_value' => 89, 'label' => 'Sangat Baik', 'score' => 3]);
            $comp->scores()->create(['min_value' => 60, 'max_value' => 74, 'label' => 'Baik', 'score' => 2]);
            $comp->scores()->create(['max_value' => 59, 'label' => 'Perlu Perbaikan', 'score' => 1]);
        }
        // MENTAL - Focus / confidence (Rating 0-100)
        elseif (str_contains($name, 'focus') || str_contains($name, 'fokus') || str_contains($name, 'confidence') || str_contains($name, 'mental') || str_contains($name, 'motivasi')) {
            $comp->scores()->create(['min_value' => 90, 'label' => 'Sangat Tangguh', 'score' => 4]);
            $comp->scores()->create(['min_value' => 80, 'max_value' => 89, 'label' => 'Tangguh', 'score' => 3]);
            $comp->scores()->create(['min_value' => 70, 'max_value' => 79, 'label' => 'Cukup', 'score' => 2]);
            $comp->scores()->create(['max_value' => 69, 'label' => 'Kurang', 'score' => 1]);
        }
        // TAKTIK - Strategy / awareness (0-100)
        elseif (str_contains($name, 'strategy') || str_contains($name, 'taktik') || str_contains($name, 'awareness') || str_contains($name, 'management')) {
            $comp->scores()->create(['min_value' => 85, 'label' => 'Master', 'score' => 4]);
            $comp->scores()->create(['min_value' => 70, 'max_value' => 84, 'label' => 'Expert', 'score' => 3]);
            $comp->scores()->create(['min_value' => 55, 'max_value' => 69, 'label' => 'Competent', 'score' => 2]);
            $comp->scores()->create(['max_value' => 54, 'label' => 'Novice', 'score' => 1]);
        }
        // OTHERS - General fallback (Scale 1-10)
        else {
            $comp->scores()->create(['min_value' => 9, 'label' => 'Luar Biasa', 'score' => 4]);
            $comp->scores()->create(['min_value' => 7, 'max_value' => 8.9, 'label' => 'Bagus', 'score' => 3]);
            $comp->scores()->create(['min_value' => 5, 'max_value' => 6.9, 'label' => 'Cukup', 'score' => 2]);
            $comp->scores()->create(['max_value' => 4.9, 'label' => 'Kurang', 'score' => 1]);
        }
    }
}
