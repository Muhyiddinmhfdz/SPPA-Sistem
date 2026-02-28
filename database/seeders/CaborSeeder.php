<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cabor;

class CaborSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cabors = [
            'Para Atletik (AT)',
            'Para Bulutangkis (BA)',
            'Para Renang (SW)',
            'Para Angkat Berat (PO)',
            'Para Tenis Meja (TT)',
            'Boccia (BO)',
            'Para Panahan (AR)',
            'Para Balap Sepeda (CY)',
            'Para Catur (CH)',
            'Sepakbola CP (FT)',
            'Goalboal (GB)',
            'Judo Tunanetra (JU)',
            'Para Menembak (SH)',
            'Voli Duduk (SV)',
            'Para Taekwondo (TK)',
            'Para Ten-pin Bowling (TPB)',
            'Para E-sport',
            'Basket Kursi Roda (WB)',
            'Tenis Kursi Roda (WT)'
        ];

        foreach ($cabors as $cabor) {
            Cabor::create([
                'name' => $cabor
            ]);
        }
    }
}
