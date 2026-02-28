<?php

namespace App\Http\Controllers\Master;

use App\DataTables\RoleDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Role\StoreRoleRequest;
use App\Http\Requests\Master\Role\UpdateRoleRequest;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index(RoleDataTable $dataTable)
    {
        $totalRoles = \Spatie\Permission\Models\Role::count();
        $totalPermissions = \Spatie\Permission\Models\Permission::count();

        return $dataTable->render('master.role.index', compact('totalRoles', 'totalPermissions'));
    }

    public function data(Request $request): JsonResponse
    {
        $filters = $request->only(['search']);
        $roles = $this->roleService->getAllRoles($filters)->get();

        return response()->json([
            'success' => true,
            'data' => $roles,
        ]);
    }

    public function create(): JsonResponse
    {
        $permissionsGrouped = $this->roleService->getPermissionsGroupedByModule();

        return response()->json([
            'success' => true,
            'html' => view('master.role.modals.create', compact('permissionsGrouped'))->render(),
        ]);
    }

    public function store(StoreRoleRequest $request): JsonResponse
    {
        try {
            $role = $this->roleService->createRole($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Role berhasil dibuat.',
                'data' => $role,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat role: '.$e->getMessage(),
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $role = $this->roleService->getRoleById($id);

            return response()->json([
                'success' => true,
                'html' => view('master.role.modals.show', compact('role'))->render(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Role tidak ditemukan.',
            ], 404);
        }
    }

    public function edit($id): JsonResponse
    {
        try {
            $role = $this->roleService->getRoleById($id);
            $permissionsGrouped = $this->roleService->getPermissionsGroupedByModule();

            return response()->json([
                'success' => true,
                'html' => view('master.role.modals.edit', compact('role', 'permissionsGrouped'))->render(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Role tidak ditemukan.',
            ], 404);
        }
    }

    public function update(UpdateRoleRequest $request, $id): JsonResponse
    {
        try {
            $role = $this->roleService->updateRole($id, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Role berhasil diperbarui.',
                'data' => $role,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui role: '.$e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->roleService->deleteRole($id);

            return response()->json([
                'success' => true,
                'message' => 'Role berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus role: '.$e->getMessage(),
            ], 500);
        }
    }
}
