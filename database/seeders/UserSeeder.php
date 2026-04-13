<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@npci.or.id'],
            [
                'name' => 'Super Administrator',
                'username' => 'superadmin',
                'password' => Hash::make('123123123'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        $superAdminRole = Role::where('name', 'Super Admin')->first();
        if ($superAdminRole) {
            $superAdmin->syncRoles([$superAdminRole]);
        }

        $admin = User::updateOrCreate(
            ['email' => 'admin@npci.or.id'],
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'password' => Hash::make('123123123'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $admin->syncRoles([$adminRole]);
        }

        $this->command->info('Users seeded successfully.');
        $this->command->info('Login credentials:');
        $this->command->info('- Super Admin: superadmin / 123123123');
        $this->command->info('- Admin: admin / 123123123');
    }
}
