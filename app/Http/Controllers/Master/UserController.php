<?php

namespace App\Http\Controllers\Master;

use App\DataTables\UserDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Master\User\StoreUserRequest;
use App\Http\Requests\Master\User\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(UserDataTable $dataTable)
    {
        $totalUsers = \App\Models\User::count();
        $activeUsers = \App\Models\User::active()->count();
        $inactiveUsers = \App\Models\User::inactive()->count();

        return $dataTable->render('master.user.index', compact('totalUsers', 'activeUsers', 'inactiveUsers'));
    }

    public function data(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'role', 'status', 'trashed']);
        $users = $this->userService->getAllUsers($filters)->get();

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    public function create(): JsonResponse
    {
        $roles = Role::all();

        return response()->json([
            'success' => true,
            'html' => view('master.user.modals.create', compact('roles'))->render(),
        ]);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->createUser($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'User berhasil dibuat.',
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat user: '.$e->getMessage(),
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $user = $this->userService->getUserById($id);

            return response()->json([
                'success' => true,
                'html' => view('master.user.modals.show', compact('user'))->render(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }
    }

    public function edit($id): JsonResponse
    {
        try {
            $user = $this->userService->getUserById($id);
            $roles = Role::all();

            return response()->json([
                'success' => true,
                'html' => view('master.user.modals.edit', compact('user', 'roles'))->render(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.',
            ], 404);
        }
    }

    public function update(UpdateUserRequest $request, $id): JsonResponse
    {
        try {
            $user = $this->userService->updateUser($id, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'User berhasil diperbarui.',
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui user: '.$e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->userService->deleteUser($id);

            return response()->json([
                'success' => true,
                'message' => 'User berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus user: '.$e->getMessage(),
            ], 500);
        }
    }

    public function restore($id): JsonResponse
    {
        try {
            $user = $this->userService->restoreUser($id);

            return response()->json([
                'success' => true,
                'message' => 'User berhasil dipulihkan.',
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulihkan user: '.$e->getMessage(),
            ], 500);
        }
    }

    public function bulkAction(Request $request): JsonResponse
    {
        $request->validate([
            'user_ids' => 'required|array',
            'action' => 'required|in:activate,deactivate,delete',
        ]);

        try {
            $this->userService->bulkAction($request->user_ids, $request->action);

            return response()->json([
                'success' => true,
                'message' => 'Aksi berhasil dilakukan.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan aksi: '.$e->getMessage(),
            ], 500);
        }
    }
}
