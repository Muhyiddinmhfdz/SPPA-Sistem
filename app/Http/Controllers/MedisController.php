<?php

namespace App\Http\Controllers;

use App\Models\Medis;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MedisController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Medis::with('user')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('user_account', function ($row) {
                    return $row->user ? $row->user->username : '-';
                })
                ->addColumn('klasifikasi_badge', function ($row) {
                    $badges = [
                        'dokter' => '<span class="badge badge-light-primary">Dokter</span>',
                        'perawat' => '<span class="badge badge-light-success">Perawat</span>',
                        'masseur' => '<span class="badge badge-light-warning">Masseur</span>',
                    ];
                    return $badges[$row->klasifikasi] ?? '-';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="edit btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 editMedis" title="Edit Data">
                                <i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>
                            </a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm deleteMedis" title="Hapus Data">
                                <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </a>';
                    return $btn;
                })
                ->rawColumns(['action', 'klasifikasi_badge'])
                ->make(true);
        }

        return view('pages.medis.index')
            ->with(['title' => 'Data Medis', 'breadcrum' => ['Master Data', 'Data Medis']]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'nullable|string|min:6',
            'klasifikasi' => 'required|in:dokter,perawat,masseur',
            'nik' => 'nullable|string|max:50',
            'birth_place' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'religion' => 'nullable|string|max:50',
            'gender' => 'nullable|in:L,P',
            'address' => 'nullable|string',
            'blood_type' => 'nullable|string|max:10',
            'last_education' => 'nullable|string',
            'education_certificate_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'photo_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ktp_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'competency_certificate_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'npwp_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'sk_appointment_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // 1. Create User
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password ?? '123123123'),
        ]);

        $user->assignRole('Medis');

        // 2. Upload Files (if any)
        $data = $request->except(['username', 'email', 'password', 'medis_id']);
        $data['user_id'] = $user->id;

        $fileFields = ['education_certificate_path', 'photo_path', 'ktp_path', 'competency_certificate_path', 'npwp_path', 'sk_appointment_path'];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $path = $file->store('public/uploads/medis');
                $data[$field] = str_replace('public/', 'storage/', $path);
            }
        }

        // 3. Create Medis Record
        Medis::create($data);

        return response()->json(['success' => 'Data Medis berhasil ditambahkan.']);
    }

    public function edit($id)
    {
        $medis = Medis::with('user')->find($id);
        if (!$medis) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
        return response()->json($medis);
    }

    public function update(Request $request, $id)
    {
        $medis = Medis::find($id);
        if (!$medis) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }

        $userId = $medis->user_id;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,' . $userId,
            'email' => 'nullable|email|unique:users,email,' . $userId,
            'password' => 'nullable|string|min:6',
            'klasifikasi' => 'required|in:dokter,perawat,masseur',
            'education_certificate_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'photo_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ktp_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'competency_certificate_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'npwp_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'sk_appointment_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // 1. Update User
        if ($medis->user) {
            $userData = [
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
            ];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $medis->user->update($userData);
        }

        // 2. Handle File Uploads
        $data = $request->except(['username', 'email', 'password', 'medis_id']);

        $fileFields = ['education_certificate_path', 'photo_path', 'ktp_path', 'competency_certificate_path', 'npwp_path', 'sk_appointment_path'];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old file if exists
                if ($medis->$field) {
                    $oldPath = str_replace('storage/', 'public/', $medis->$field);
                    Storage::delete($oldPath);
                }
                $file = $request->file($field);
                $path = $file->store('public/uploads/medis');
                $data[$field] = str_replace('public/', 'storage/', $path);
            }
        }

        // 3. Update Medis
        $medis->update($data);

        return response()->json(['success' => 'Data Medis berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $medis = Medis::find($id);
        if (!$medis) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }

        $medis->update(['is_active' => 0]);
        $medis->delete(); // soft delete

        return response()->json(['success' => 'Data Medis berhasil dinonaktifkan.']);
    }
}
