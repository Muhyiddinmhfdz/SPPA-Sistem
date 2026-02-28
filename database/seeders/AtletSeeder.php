<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Atlet;
use Illuminate\Support\Facades\Hash;

class AtletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        // Create User for Atlet 1
        $userAtlet1 = User::create([
            'name' => 'Budi Santoso',
            'username' => 'budi_atlet',
            'email' => 'budi@npci.or.id',
            'password' => Hash::make('123123123'),
            'email_verified_at' => now(),
        ]);
        $userAtlet1->assignRole('Atlet');

        Atlet::create([
            'user_id' => $userAtlet1->id,
            'cabor_id' => 1, // Para Atletik
            'klasifikasi_disabilitas_id' => 1, // T11
            'name' => 'Budi Santoso',
            'jenis_disabilitas' => 'Tuna Netra Total',
            'nik' => '3201112233445001',
            'birth_place' => 'Bandung',
            'birth_date' => '1995-10-15',
            'religion' => 'Islam',
            'gender' => 'L',
            'address' => 'Jl. Merdeka No. 10, Bandung',
            'blood_type' => 'O',
            'last_education' => 'SMA',
        ]);

        // Create User for Atlet 2
        $userAtlet2 = User::create([
            'name' => 'Siti Aminah',
            'username' => 'siti_atlet',
            'email' => 'siti@npci.or.id',
            'password' => Hash::make('123123123'),
            'email_verified_at' => now(),
        ]);
        $userAtlet2->assignRole('Atlet');

        Atlet::create([
            'user_id' => $userAtlet2->id,
            'cabor_id' => 2, // Para Bulutangkis
            'klasifikasi_disabilitas_id' => 2, // T12
            'name' => 'Siti Aminah',
            'jenis_disabilitas' => 'Tuna Netra Parsial',
            'nik' => '3201112233445002',
            'birth_place' => 'Jakarta',
            'birth_date' => '1998-05-20',
            'religion' => 'Islam',
            'gender' => 'P',
            'address' => 'Jl. Sudirman No. 45, Jakarta',
            'blood_type' => 'A',
            'last_education' => 'S1 Olahraga',
        ]);
    }
}
