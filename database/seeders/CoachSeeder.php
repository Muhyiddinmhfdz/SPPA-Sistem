<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Coach;
use App\Models\Cabor;
use Illuminate\Support\Facades\Hash;

class CoachSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada Cabor minimal satu
        $cabor = Cabor::first();

        if (!$cabor) {
            $cabor = Cabor::create(['name' => 'Para Atletik (AT)']);
        }

        // Create User for Coach
        $user = User::create([
            'name' => 'Budi Pelatih',
            'username' => 'coach',
            'email' => 'coach@npci.or.id',
            'password' => Hash::make('123123123'),
            'email_verified_at' => now(),
        ]);

        $user->assignRole('Pelatih');

        // Create Coach profile
        Coach::create([
            'user_id' => $user->id,
            'cabor_id' => $cabor->id,
            'name' => 'Budi Pelatih',
            'nik' => '3201111111111111',
            'birth_place' => 'Jakarta',
            'birth_date' => '1980-01-01',
            'religion' => 'Islam',
            'gender' => 'L',
            'address' => 'Jl. Pelatih No. 1, Jakarta',
            'blood_type' => 'O',
            'last_education' => 'S1 Pendidikan Kepelatihan Olahraga'
        ]);

        $this->command->info('Coach seeded successfully. Username: coach, Password: 123123123');
    }
}
