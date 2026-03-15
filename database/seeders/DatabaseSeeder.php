<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CaborSeeder::class,
            CoachSeeder::class,
            KlasifikasiDisabilitasSeeder::class,
            JenisDisabilitasSeeder::class,
            MedisSeeder::class,
            TrainingSeeder::class,
            AtletSeeder::class,
            KompetisiSeeder::class,
            PembinaanPrestasiSeeder::class,
            CekKesehatanSeeder::class,
            MonitoringLatihanSeeder::class,
            PhysicalTestSeeder::class,
            PerformanceTestSeeder::class,
        ]);
    }
}
