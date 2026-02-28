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

        $faker = \Faker\Factory::create('id_ID');

        foreach ($cabors as $cabor) {
            Cabor::create([
                'name' => $cabor,
                'sk_start_date' => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
                'sk_end_date' => $faker->dateTimeBetween('now', '+3 years')->format('Y-m-d'),
                'chairman_name' => $faker->name,
                'secretary_name' => $faker->name,
                'treasurer_name' => $faker->name,
                'secretariat_address' => $faker->address,
                'phone_number' => $faker->phoneNumber,
                'email' => $faker->safeEmail,
                'npwp' => $faker->numerify('##.###.###.#-###.###'),
                'active_athletes_count' => $faker->numberBetween(5, 50),
                'active_coaches_count' => $faker->numberBetween(1, 10),
                'active_medics_count' => $faker->numberBetween(0, 5),
            ]);
        }
    }
}
