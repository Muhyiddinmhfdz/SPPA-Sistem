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
        $superAdmin = User::create([
            'name' => 'Super Administrator',
            'username' => 'superadmin',
            'email' => 'superadmin@npci.or.id',
            'password' => Hash::make('123123123'),
            'email_verified_at' => now(),
        ]);

        $superAdminRole = Role::where('name', 'Super Admin')->first();
        if ($superAdminRole) {
            $superAdmin->assignRole($superAdminRole);
        }

        $admin = User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@npci.or.id',
            'password' => Hash::make('123123123'),
            'email_verified_at' => now(),
        ]);

        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $admin->assignRole($adminRole);
        }

        $this->command->info('Users created successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('- Super Admin: superadmin / password');
        $this->command->info('- Admin: admin / password');
    }
}
