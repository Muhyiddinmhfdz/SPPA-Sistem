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
        $superAdmin = User::firstOrCreate(
            ['username' => 'superadmin'],
            [
                'name' => 'Super Administrator',
                'email' => 'superadmin@npci.or.id',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $superAdminRole = Role::where('name', 'Super Admin')->first();
        if ($superAdminRole && ! $superAdmin->hasRole('Super Admin')) {
            $superAdmin->assignRole($superAdminRole);
        }

        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'email' => 'admin@npci.or.id',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole && ! $admin->hasRole('Admin')) {
            $admin->assignRole($adminRole);
        }

        $staff = User::firstOrCreate(
            ['username' => 'staff'],
            [
                'name' => 'Staff NPCI',
                'email' => 'staff@npci.or.id',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $staffRole = Role::where('name', 'Staff')->first();
        if ($staffRole && ! $staff->hasRole('Staff')) {
            $staff->assignRole($staffRole);
        }

        $this->command->info('Users created successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('- Super Admin: superadmin / password');
        $this->command->info('- Admin: admin / password');
        $this->command->info('- Staff: staff / password');
    }
}
