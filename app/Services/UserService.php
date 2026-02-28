<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserService
{
    public function getAllUsers(array $filters = [])
    {
        $query = User::with('roles');

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (isset($filters['role'])) {
            $query->whereHas('roles', function ($q) use ($filters) {
                $q->where('name', $filters['role']);
            });
        }

        if (isset($filters['status'])) {
            $query->where('is_active', $filters['status'] === 'active');
        }

        if (isset($filters['trashed']) && $filters['trashed']) {
            $query->onlyTrashed();
        }

        return $query;
    }

    public function getUserById($id)
    {
        return User::with('roles', 'permissions')->findOrFail($id);
    }

    public function createUser(array $data): User
    {
        $data = $this->sanitizeInput($data);

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_active' => $data['is_active'] ?? true,
        ]);

        if (isset($data['role_id'])) {
            $role = Role::find($data['role_id']);
            if ($role) {
                $user->assignRole($role);
            }
        }

        activity('user')
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->log("Created user {$user->name}");

        return $user;
    }

    public function updateUser($id, array $data): User
    {
        $user = User::findOrFail($id);
        $data = $this->sanitizeInput($data);

        $updateData = [
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'is_active' => $data['is_active'] ?? $user->is_active,
        ];

        if (! empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        if (isset($data['role_id'])) {
            $role = Role::find($data['role_id']);
            if ($role) {
                $user->syncRoles([$role]);
            }
        }

        activity('user')
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->log("Updated user {$user->name}");

        return $user;
    }

    public function deleteUser($id): bool
    {
        $user = User::findOrFail($id);

        activity('user')
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->log("Deleted user {$user->name}");

        return $user->delete();
    }

    public function restoreUser($id): User
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        activity('user')
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->log("Restored user {$user->name}");

        return $user;
    }

    public function forceDeleteUser($id): bool
    {
        $user = User::onlyTrashed()->findOrFail($id);

        activity('user')
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->log("Permanently deleted user {$user->name}");

        return $user->forceDelete();
    }

    public function bulkAction(array $userIds, string $action): bool
    {
        $users = User::whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            switch ($action) {
                case 'activate':
                    $user->update(['is_active' => true]);
                    break;
                case 'deactivate':
                    $user->update(['is_active' => false]);
                    break;
                case 'delete':
                    $user->delete();
                    break;
            }
        }

        activity('user')
            ->causedBy(auth()->user())
            ->log("Bulk {$action} performed on ".count($users).' users');

        return true;
    }

    protected function sanitizeInput(array $data): array
    {
        if (isset($data['name'])) {
            $data['name'] = trim(Str::title($data['name']));
        }

        if (isset($data['username'])) {
            $data['username'] = trim(Str::lower($data['username']));
        }

        if (isset($data['email'])) {
            $data['email'] = trim(Str::lower($data['email']));
        }

        return $data;
    }
}
