<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cabor;
use App\Models\JenisDisabilitas;
use App\Models\PhysicalTestCategory;
use App\Models\PhysicalTestItem;
use App\Models\PhysicalTestItemScore;

class PhysicalTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // For demonstration, we will attach these tests to the first 2 Cabors (e.g., Atletik, Renang)
        $cabors = Cabor::take(2)->get();
        if ($cabors->isEmpty()) return;

        // Fetch some disabilitas IDs from what is actually available in the Master Jenis Disabilitas
        $disabilitas_ids = JenisDisabilitas::pluck('id')->toArray();

        // Helper to get a random disabilitas ID safely
        $getRandomDisabilitasId = function () use ($disabilitas_ids) {
            if (empty($disabilitas_ids)) return null;
            return $disabilitas_ids[array_rand($disabilitas_ids)];
        };

        $id_amputasi = $getRandomDisabilitasId();
        $id_cp = $getRandomDisabilitasId();
        $id_intelektual = $getRandomDisabilitasId();
        $id_wheelchair = $getRandomDisabilitasId();

        $categories = [
            [
                'name' => 'A. Endurance (25%)',
                'items' => [
                    [
                        'name' => '6 Minute Walk Test',
                        'jenis_disabilitas_id' => $id_amputasi, // Amputasi / CP ringan
                        'satuan' => 'meter',
                        'scores' => [
                            ['label' => 'Sangat Baik', 'min' => 550, 'max' => null, 'score' => 4],
                            ['label' => 'Baik', 'min' => 450, 'max' => 549, 'score' => 3],
                            ['label' => 'Cukup', 'min' => 350, 'max' => 449, 'score' => 2],
                            ['label' => 'Kurang', 'min' => null, 'max' => 349, 'score' => 1],
                        ]
                    ],
                    [
                        'name' => '6 Minute Push Test',
                        'jenis_disabilitas_id' => $id_wheelchair, // wheelchair
                        'satuan' => 'meter',
                        'scores' => [
                            ['label' => 'Sangat Baik', 'min' => 1500, 'max' => null, 'score' => 4],
                            ['label' => 'Baik', 'min' => 1200, 'max' => 1499, 'score' => 3],
                            ['label' => 'Cukup', 'min' => 900, 'max' => 1199, 'score' => 2],
                            ['label' => 'Kurang', 'min' => null, 'max' => 899, 'score' => 1],
                        ]
                    ],
                    [
                        'name' => 'Arm Ergometer Test',
                        'jenis_disabilitas_id' => $id_amputasi, // Spinal Injury
                        'satuan' => 'Vo2max estimation',
                        'scores' => [] // Disesuaikan norma kualifikasi
                    ],
                ]
            ],
            [
                'name' => 'B. Kekuatan Otot (Strength) (25%)',
                'items' => [
                    [
                        'name' => 'Handgrip Dynamometer',
                        'jenis_disabilitas_id' => null, // Semua
                        'satuan' => 'kg',
                        'scores' => [
                            ['label' => 'Sangat Baik', 'min' => 50, 'max' => null, 'score' => 4],
                            ['label' => 'Baik', 'min' => 40, 'max' => 49, 'score' => 3],
                            ['label' => 'Cukup', 'min' => 30, 'max' => 39, 'score' => 2],
                            ['label' => 'Kurang', 'min' => null, 'max' => 29, 'score' => 1],
                        ]
                    ],
                    [
                        'name' => 'Manual Muscle Test (MMT)',
                        'jenis_disabilitas_id' => $id_cp, // CP / Spinal Injury
                        'satuan' => 'skala 1-5',
                        'scores' => [
                            ['label' => 'Normal', 'min' => 5, 'max' => 5, 'score' => 5],
                            ['label' => 'Baik', 'min' => 4, 'max' => 4, 'score' => 4],
                            ['label' => 'Cukup Baik', 'min' => 3, 'max' => 3, 'score' => 3],
                            ['label' => 'Buruk', 'min' => 2, 'max' => 2, 'score' => 2],
                            ['label' => 'Sangat Buruk', 'min' => 1, 'max' => 1, 'score' => 1],
                        ]
                    ],
                    [
                        'name' => '1RM Upper Body',
                        'jenis_disabilitas_id' => $id_wheelchair,
                        'satuan' => 'kg',
                        'scores' => []
                    ],
                    [
                        'name' => 'Medicine Ball Throw',
                        'jenis_disabilitas_id' => null, // Upper body
                        'satuan' => 'meter',
                        'scores' => [
                            ['label' => 'Sangat Baik', 'min' => 5, 'max' => null, 'score' => 4],
                            ['label' => 'Baik', 'min' => 4, 'max' => 4.9, 'score' => 3],
                            ['label' => 'Cukup', 'min' => 3, 'max' => 3.9, 'score' => 2],
                            ['label' => 'Kurang', 'min' => null, 'max' => 2.9, 'score' => 1],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'C. Daya Ledak (Power) (15%)',
                'items' => [
                    [
                        'name' => 'Seated Medicine Ball Throw',
                        'jenis_disabilitas_id' => $id_wheelchair,
                        'satuan' => 'meter',
                        'scores' => [
                            ['label' => 'Sangat Baik', 'min' => 5, 'max' => null, 'score' => 4],
                            ['label' => 'Baik', 'min' => 4, 'max' => 4.9, 'score' => 3],
                            ['label' => 'Cukup', 'min' => 3, 'max' => 3.9, 'score' => 2],
                            ['label' => 'Kurang', 'min' => null, 'max' => 2.9, 'score' => 1],
                        ]
                    ],
                    [
                        'name' => 'Vertical Jump (prosthesis)',
                        'jenis_disabilitas_id' => $id_amputasi,
                        'satuan' => 'cm',
                        'scores' => []
                    ],
                    [
                        'name' => 'Arm Power Test',
                        'jenis_disabilitas_id' => null, // Upper body dominant
                        'satuan' => 'meter',
                        'scores' => [
                            ['label' => 'Sangat Baik', 'min' => 5, 'max' => null, 'score' => 4],
                            ['label' => 'Baik', 'min' => 4, 'max' => 4.9, 'score' => 3],
                            ['label' => 'Cukup', 'min' => 3, 'max' => 3.9, 'score' => 2],
                            ['label' => 'Kurang', 'min' => null, 'max' => 2.9, 'score' => 1],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'D. Kecepatan (Speed) (15%)',
                'items' => [
                    [
                        'name' => 'Sprint 20 m',
                        'jenis_disabilitas_id' => $id_amputasi, // Amputasi / CP
                        'satuan' => 'detik',
                        'scores' => [
                            ['label' => 'Sangat Baik', 'min' => null, 'max' => 4, 'score' => 4],
                            ['label' => 'Baik', 'min' => 4.1, 'max' => 5, 'score' => 3],
                            ['label' => 'Cukup', 'min' => 5.1, 'max' => 6, 'score' => 2],
                            ['label' => 'Kurang', 'min' => 6.1, 'max' => null, 'score' => 1], // Notice logic inverted for speed (lower is better)
                        ]
                    ],
                    [
                        'name' => 'Wheelchair Sprint 20 m',
                        'jenis_disabilitas_id' => $id_wheelchair,
                        'satuan' => 'detik',
                        'scores' => [
                            ['label' => 'Sangat Baik', 'min' => null, 'max' => 4, 'score' => 4],
                            ['label' => 'Baik', 'min' => 4.1, 'max' => 5, 'score' => 3],
                            ['label' => 'Cukup', 'min' => 5.1, 'max' => 6, 'score' => 2],
                            ['label' => 'Kurang', 'min' => 6.1, 'max' => null, 'score' => 1],
                        ]
                    ],
                    [
                        'name' => 'Reaction Time Test',
                        'jenis_disabilitas_id' => $id_intelektual, // Visual/Intelektual
                        'satuan' => 'mili detik',
                        'scores' => []
                    ],
                ]
            ],
            [
                'name' => 'E. Kelincahan (Agility) (10%)',
                'items' => [
                    [
                        'name' => 'T-Test Modified',
                        'jenis_disabilitas_id' => $id_amputasi,
                        'satuan' => 'detik',
                        'scores' => []
                    ],
                    [
                        'name' => 'Wheelchair Agility Course',
                        'jenis_disabilitas_id' => $id_wheelchair,
                        'satuan' => 'detik',
                        'scores' => []
                    ],
                    [
                        'name' => 'Cone Maneuver Test',
                        'jenis_disabilitas_id' => $id_cp,
                        'satuan' => 'detik',
                        'scores' => []
                    ],
                ]
            ],
            [
                'name' => 'F. Fleksibilitas (ROM) (5%)',
                'items' => [
                    [
                        'name' => 'Sit and Reach',
                        'jenis_disabilitas_id' => $id_amputasi,
                        'satuan' => 'cm',
                        'scores' => [
                            ['label' => 'Baik', 'min' => 25, 'max' => null, 'score' => 3],
                        ]
                    ],
                    [
                        'name' => 'Shoulder ROM',
                        'jenis_disabilitas_id' => null,
                        'satuan' => 'derajat',
                        'scores' => [] // Normal / Limited
                    ],
                ]
            ],
            [
                'name' => 'G. Keseimbangan & Kontrol Postur (5%)',
                'items' => [
                    [
                        'name' => 'Seated Balance Test',
                        'jenis_disabilitas_id' => $id_wheelchair,
                        'satuan' => 'detik',
                        'scores' => []
                    ],
                    [
                        'name' => 'Stork Stand Modified',
                        'jenis_disabilitas_id' => $id_amputasi,
                        'satuan' => 'detik',
                        'scores' => []
                    ],
                    [
                        'name' => 'Functional Reach Test',
                        'jenis_disabilitas_id' => null,
                        'satuan' => 'cm',
                        'scores' => []
                    ],
                ]
            ],
        ];

        foreach ($cabors as $cabor) {
            foreach ($categories as $catData) {
                // Create Category
                $category = PhysicalTestCategory::create([
                    'cabor_id' => $cabor->id,
                    'name' => $catData['name']
                ]);

                // Create Items
                foreach ($catData['items'] as $itemData) {
                    $item = PhysicalTestItem::create([
                        'physical_test_category_id' => $category->id,
                        'name' => $itemData['name'],
                        'jenis_disabilitas_id' => $itemData['jenis_disabilitas_id'],
                        'satuan' => $itemData['satuan'],
                        'is_active' => 1
                    ]);

                    // Create Scores
                    foreach ($itemData['scores'] as $scoreData) {
                        PhysicalTestItemScore::create([
                            'physical_test_item_id' => $item->id,
                            'label' => $scoreData['label'],
                            'score' => $scoreData['score'],
                            'min_value' => $scoreData['min'],
                            'max_value' => $scoreData['max'],
                            'is_active' => 1
                        ]);
                    }
                }
            }
        }
    }
}
