<?php

namespace App\Services;

use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function getAllRoles(array $filters = [])
    {
        $query = Role::with('permissions');

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where('name', 'like', "%{$search}%");
        }

        return $query;
    }

    public function getRoleById($id)
    {
        return Role::with('permissions')->findOrFail($id);
    }

    public function createRole(array $data): Role
    {
        $data = $this->sanitizeInput($data);

        $role = Role::create([
            'name' => $data['name'],
            'guard_name' => $data['guard_name'] ?? 'web',
        ]);

        if (isset($data['permissions'])) {
            $permissions = Permission::whereIn('id', $data['permissions'])->get();
            $role->syncPermissions($permissions);
        }

        activity('role')
            ->performedOn($role)
            ->causedBy(auth()->user())
            ->log("Created role {$role->name}");

        return $role;
    }

    public function updateRole($id, array $data): Role
    {
        $role = Role::findOrFail($id);
        $data = $this->sanitizeInput($data);

        $role->update([
            'name' => $data['name'],
            'guard_name' => $data['guard_name'] ?? $role->guard_name,
        ]);

        if (isset($data['permissions'])) {
            $permissions = Permission::whereIn('id', $data['permissions'])->get();
            $role->syncPermissions($permissions);
        }

        activity('role')
            ->performedOn($role)
            ->causedBy(auth()->user())
            ->log("Updated role {$role->name}");

        return $role;
    }

    public function deleteRole($id): bool
    {
        $role = Role::findOrFail($id);

        activity('role')
            ->performedOn($role)
            ->causedBy(auth()->user())
            ->log("Deleted role {$role->name}");

        return $role->delete();
    }

    public function getPermissionsGroupedByModule()
    {
        $permissions = Permission::all();

        $grouped = $permissions->groupBy(function ($permission) {
            $parts = explode('.', $permission->name);

            return ucfirst($parts[0]);
        });

        return $grouped;
    }

    protected function sanitizeInput(array $data): array
    {
        if (isset($data['name'])) {
            $data['name'] = trim(Str::lower($data['name']));
        }

        return $data;
    }
}
