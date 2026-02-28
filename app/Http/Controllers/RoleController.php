<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::query()->latest();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex justify-content-center">';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 editRole"><i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i></a>';
                    if (!in_array($row->name, ['Super Admin', 'Admin', 'Staff'])) {
                        $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm deleteRole"><i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></a>';
                    }
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.role.index', ['title' => 'Manajemen Role', 'breadcrum' => ['Pengaturan', 'Manajemen Role']]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        Role::create(['name' => $request->name, 'guard_name' => 'web']);

        return response()->json(['success' => 'Role berhasil ditambahkan.']);
    }

    public function edit($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['error' => 'Role tidak ditemukan.'], 404);
        }
        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $role = Role::find($id);
        if (!$role) {
            return response()->json(['error' => 'Role tidak ditemukan.'], 404);
        }

        if (in_array($role->name, ['Super Admin', 'Admin', 'Staff'])) {
            return response()->json(['error' => 'Role bawaan sistem tidak dapat diubah.'], 403);
        }

        $role->update(['name' => $request->name]);

        return response()->json(['success' => 'Role berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['error' => 'Role tidak ditemukan.'], 404);
        }

        if (in_array($role->name, ['Super Admin', 'Admin', 'Staff'])) {
            return response()->json(['error' => 'Role bawaan sistem tidak dapat dihapus.'], 403);
        }

        $role->delete();

        return response()->json(['success' => 'Role berhasil dihapus.']);
    }
}
