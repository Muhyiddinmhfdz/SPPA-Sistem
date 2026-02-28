<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coach;
use App\Models\Cabor;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CoachController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Coach::with('cabor')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('cabor_name', function ($row) {
                    return $row->cabor ? $row->cabor->name : '-';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="edit btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 editCoach" title="Edit Data">
                                <i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>
                            </a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm deleteCoach" title="Hapus Data">
                                <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $cabors = Cabor::all();
        return view('pages.coach.index', compact('cabors'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cabor_id' => 'required|exists:cabors,id',
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:20|unique:coaches,nik',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'religion' => 'required|string|max:50',
            'gender' => 'required|in:L,P',
            'address' => 'required|string',
            'blood_type' => 'nullable|string|max:5',
            'last_education' => 'required|string|max:100',

            // Multiple file uploads
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ktp' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'certificate' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'npwp' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'sk' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',

            // Akun Login Optional
            'username' => 'nullable|string|max:50|unique:users,username',
            'password' => 'nullable|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $data = $request->except(['photo', 'ktp', 'certificate', 'npwp', 'sk', 'username', 'password']);

        $files = ['photo', 'ktp', 'certificate', 'npwp', 'sk'];
        foreach ($files as $fileKey) {
            if ($request->hasFile($fileKey)) {
                $file = $request->file($fileKey);
                $filename = time() . '_' . $fileKey . '_' . str_replace(" ", "_", $file->getClientOriginalName());
                $file->move(public_path('uploads/coach_documents'), $filename);
                $data[$fileKey . '_path'] = 'uploads/coach_documents/' . $filename;
            }
        }

        DB::beginTransaction();
        try {
            $user_id = null;
            if ($request->filled('username')) {
                $user = User::create([
                    'name' => $request->name,
                    'username' => $request->username,
                    'password' => Hash::make($request->password ?? '123123123')
                ]);
                $user->assignRole('Pelatih');
                $user_id = $user->id;
            }

            $data['user_id'] = $user_id;
            Coach::create($data);

            DB::commit();
            return response()->json(['success' => 'Data Pelatih berhasil ditambahkan.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $coach = Coach::with('user')->find($id);
        if (!$coach) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
        return response()->json($coach);
    }

    public function update(Request $request, $id)
    {
        $coach = Coach::find($id);
        if (!$coach) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'cabor_id' => 'required|exists:cabors,id',
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:20|unique:coaches,nik,' . $id,
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'religion' => 'required|string|max:50',
            'gender' => 'required|in:L,P',
            'address' => 'required|string',
            'blood_type' => 'nullable|string|max:5',
            'last_education' => 'required|string|max:100',

            // Multiple file uploads
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ktp' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'certificate' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'npwp' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'sk' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',

            // Akun Login Optional
            'username' => 'nullable|string|max:50|unique:users,username,' . $coach->user_id,
            'password' => 'nullable|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $data = $request->except(['photo', 'ktp', 'certificate', 'npwp', 'sk', '_method', 'username', 'password']);

        $files = ['photo', 'ktp', 'certificate', 'npwp', 'sk'];
        foreach ($files as $fileKey) {
            if ($request->hasFile($fileKey)) {
                // Hapus file lama jika ada
                $dbPathField = $fileKey . '_path';
                if ($coach->$dbPathField && file_exists(public_path($coach->$dbPathField))) {
                    unlink(public_path($coach->$dbPathField));
                }

                $file = $request->file($fileKey);
                $filename = time() . '_' . $fileKey . '_' . str_replace(" ", "_", $file->getClientOriginalName());
                $file->move(public_path('uploads/coach_documents'), $filename);
                $data[$dbPathField] = 'uploads/coach_documents/' . $filename;
            }
        }

        DB::beginTransaction();
        try {
            if ($request->filled('username')) {
                if ($coach->user_id) {
                    $user = User::find($coach->user_id);
                    $userData = [
                        'name' => $request->name,
                        'username' => $request->username
                    ];
                    if ($request->filled('password')) {
                        $userData['password'] = Hash::make($request->password);
                    }
                    $user->update($userData);
                } else {
                    $user = User::create([
                        'name' => $request->name,
                        'username' => $request->username,
                        'password' => Hash::make($request->password ?? '123123123')
                    ]);
                    $user->assignRole('Pelatih');
                    $data['user_id'] = $user->id;
                }
            }

            $coach->update($data);

            DB::commit();
            return response()->json(['success' => 'Data Pelatih berhasil diperbarui.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Gagal memperbarui data: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $coach = Coach::find($id);
        if (!$coach) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }

        $coach->update(['is_active' => 0]);
        $coach->delete(); // soft delete

        return response()->json(['success' => 'Data Pelatih berhasil dinonaktifkan.']);
    }
}
