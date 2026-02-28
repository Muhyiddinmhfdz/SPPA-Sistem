<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('roles')->latest();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('role', function ($row) {
                    $roles = $row->roles->pluck('name')->toArray();
                    $badges = '';
                    foreach ($roles as $role) {
                        $badges .= '<span class="badge badge-light-primary fs-7 me-1">' . $role . '</span>';
                    }
                    return $badges;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex justify-content-center">';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 editUser"><i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i></a>';
                    if ($row->username !== 'superadmin') {
                        $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm deleteUser"><i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></a>';
                    }
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['role', 'action'])
                ->make(true);
        }

        $roles = Role::all();
        return view('pages.user.index', compact('roles'))->with(['title' => 'Data User', 'breadcrum' => ['Master Data', 'Data User']]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|exists:roles,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        $user->assignRole($request->role);

        return response()->json(['success' => 'User berhasil ditambahkan.']);
    }

    public function edit($id)
    {
        $user = User::with('roles')->find($id);
        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan.'], 404);
        }

        $userRole = $user->roles->pluck('name')->first();
        return response()->json([
            'user' => $user,
            'role' => $userRole
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($id)],
            'password' => 'nullable|string|min:8',
            'role' => 'required|exists:roles,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan.'], 404);
        }

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->syncRoles($request->role);

        return response()->json(['success' => 'User berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan.'], 404);
        }

        if ($user->username === 'superadmin' || $user->id === request()->user()->id) {
            return response()->json(['error' => 'Action tidak diizinkan.'], 403);
        }

        $user->delete();

        return response()->json(['success' => 'User berhasil dihapus.']);
    }
}
