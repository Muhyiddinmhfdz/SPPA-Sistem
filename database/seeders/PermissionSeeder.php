<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'user.view', 'user.create', 'user.edit', 'user.delete', 'user.restore',
            'role.view', 'role.create', 'role.edit', 'role.delete',
            'atlet.view', 'atlet.create', 'atlet.edit', 'atlet.delete',
            'pelatih.view', 'pelatih.create', 'pelatih.edit', 'pelatih.delete',
            'prestasi.view', 'prestasi.create', 'prestasi.edit', 'prestasi.delete',
            'event.view', 'event.create', 'event.edit', 'event.delete',
            'laporan.view', 'laporan.export',
            'pengaturan.view', 'pengaturan.edit',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        $this->command->info('Permissions created successfully!');
    }
}
