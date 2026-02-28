<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Medis;
use Illuminate\Support\Facades\Hash;

class MedisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create User for Medis - Dokter
        $userDokter = User::create([
            'name' => 'Dr. Andi Santoso',
            'username' => 'dokter',
            'email' => 'dokter@npci.or.id',
            'password' => Hash::make('123123123'),
            'email_verified_at' => now(),
        ]);
        $userDokter->assignRole('Medis');

        Medis::create([
            'user_id' => $userDokter->id,
            'name' => 'Dr. Andi Santoso',
            'klasifikasi' => 'dokter',
            'nik' => '3201222222222222',
            'birth_place' => 'Bandung',
            'birth_date' => '1985-05-10',
            'religion' => 'Islam',
            'gender' => 'L',
            'address' => 'Jl. Kesehatan No. 1, Bandung',
            'blood_type' => 'AB',
            'last_education' => 'S1 Kedokteran Umum',
        ]);

        // Create User for Medis - Perawat
        $userPerawat = User::create([
            'name' => 'Siti Aminah, S.Kep',
            'username' => 'perawat',
            'email' => 'perawat@npci.or.id',
            'password' => Hash::make('123123123'),
            'email_verified_at' => now(),
        ]);
        $userPerawat->assignRole('Medis');

        Medis::create([
            'user_id' => $userPerawat->id,
            'name' => 'Siti Aminah, S.Kep',
            'klasifikasi' => 'perawat',
            'nik' => '3201333333333333',
            'birth_place' => 'Surabaya',
            'birth_date' => '1990-11-20',
            'religion' => 'Islam',
            'gender' => 'P',
            'address' => 'Jl. Perawat No. 2, Surabaya',
            'blood_type' => 'A',
            'last_education' => 'S1 Keperawatan',
        ]);

        $this->command->info('Medis seeded successfully. Check users: dokter / perawat with password 123123123');
    }
}
