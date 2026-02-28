<?php

namespace App\Http\Requests\Master\Role;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:50', 'unique:roles,name'],
            'guard_name' => ['required', 'string', 'in:web,api'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama role wajib diisi.',
            'name.min' => 'Nama role minimal 2 karakter.',
            'name.max' => 'Nama role maksimal 50 karakter.',
            'name.unique' => 'Nama role sudah digunakan.',
            'guard_name.required' => 'Guard name wajib dipilih.',
            'guard_name.in' => 'Guard name tidak valid.',
            'permissions.array' => 'Permissions harus berupa array.',
            'permissions.*.exists' => 'Permission tidak ditemukan.',
        ];
    }
}
