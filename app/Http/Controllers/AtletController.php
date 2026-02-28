<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Atlet;
use App\Models\Cabor;
use App\Models\KlasifikasiDisabilitas;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AtletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Atlet::with(['user', 'cabor', 'klasifikasi_disabilitas'])->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('cabor_name', function ($row) {
                    return $row->cabor ? $row->cabor->name : '-';
                })
                ->addColumn('klasifikasi_badge', function ($row) {
                    if (!$row->klasifikasi_disabilitas) {
                        return '<span class="badge badge-light fw-bold">-</span>';
                    }
                    return '<span class="badge badge-light-primary fw-bold" data-bs-toggle="tooltip" title="' . $row->klasifikasi_disabilitas->nama_klasifikasi . '">' . $row->klasifikasi_disabilitas->kode_klasifikasi . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex justify-content-center flex-shrink-0">';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 editAtlet" title="Edit">
                                <i class="ki-duotone ki-pencil fs-2">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                             </a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm deleteAtlet" title="Hapus">
                                <i class="ki-duotone ki-trash fs-2">
                                    <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
                                </i>
                             </a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['klasifikasi_badge', 'action'])
                ->make(true);
        }

        $cabors = Cabor::orderBy('name', 'asc')->get();
        $klasifikasis = KlasifikasiDisabilitas::orderBy('kode_klasifikasi', 'asc')->get();
        return view('pages.atlet.index', compact('cabors', 'klasifikasis'))
            ->with(['title' => 'Data Atlet', 'breadcrum' => ['Master Data', 'Data Atlet']]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cabor_id' => 'required',
            'klasifikasi_disabilitas_id' => 'required',
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'nullable|email|unique:users,email',
            'jenis_disabilitas' => 'nullable|string|max:255',
            'nik' => 'required|string|size:16|unique:atlets,nik',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'religion' => 'required|string',
            'gender' => 'required|in:L,P',
            'address' => 'required|string',
            'blood_type' => 'nullable|string',
            'last_education' => 'required|string|max:255',
            'photo_path' => 'nullable|mimes:pdf,jpeg,jpg,png|max:2048',
            'ktp_path' => 'nullable|mimes:pdf,jpeg,jpg,png|max:2048',
            'achievement_certificate_path' => 'nullable|mimes:pdf,jpeg,jpg,png|max:2048',
            'education_certificate_path' => 'nullable|mimes:pdf,jpeg,jpg,png|max:2048',
            'npwp_path' => 'nullable|mimes:pdf,jpeg,jpg,png|max:2048',
            'sk_path' => 'nullable|mimes:pdf,jpeg,jpg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Create User account for this athlete
            $user = User::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($request->password ? $request->password : '123123123'),
                'email_verified_at' => now(),
            ]);
            $user->assignRole('Atlet');

            $atlet = new Atlet();
            $atlet->user_id = $user->id;

            $atlet = $this->fillAtletData($atlet, $validated, $request);
            $atlet->save();

            DB::commit();
            return response()->json(['success' => 'Data Atlet berhasil disimpan.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Gagal menyimpan data Atlet: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $atlet = Atlet::with('user')->findOrFail($id);
        return response()->json($atlet);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $atlet = Atlet::findOrFail($id);

        $validated = $request->validate([
            'cabor_id' => 'required',
            'klasifikasi_disabilitas_id' => 'required',
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $atlet->user_id,
            'email' => 'nullable|email|unique:users,email,' . $atlet->user_id,
            'jenis_disabilitas' => 'nullable|string|max:255',
            'nik' => 'required|string|size:16|unique:atlets,nik,' . $atlet->id,
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'religion' => 'required|string',
            'gender' => 'required|in:L,P',
            'address' => 'required|string',
            'blood_type' => 'nullable|string',
            'last_education' => 'required|string|max:255',
            'photo_path' => 'nullable|mimes:pdf,jpeg,jpg,png|max:2048',
            'ktp_path' => 'nullable|mimes:pdf,jpeg,jpg,png|max:2048',
            'achievement_certificate_path' => 'nullable|mimes:pdf,jpeg,jpg,png|max:2048',
            'education_certificate_path' => 'nullable|mimes:pdf,jpeg,jpg,png|max:2048',
            'npwp_path' => 'nullable|mimes:pdf,jpeg,jpg,png|max:2048',
            'sk_path' => 'nullable|mimes:pdf,jpeg,jpg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($atlet->user_id);
            $user->name = $validated['name'];
            $user->username = $validated['username'];
            $user->email = $validated['email'];

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            $atlet = $this->fillAtletData($atlet, $validated, $request);
            $atlet->save();

            DB::commit();
            return response()->json(['success' => 'Data Atlet berhasil diperbarui.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Gagal memperbarui data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $atlet = Atlet::findOrFail($id);
            $atlet->update(['is_active' => 0]);
            $atlet->delete(); // soft delete

            DB::commit();
            return response()->json(['success' => 'Data Atlet berhasil dinonaktifkan.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Gagal menonaktifkan data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Helper to populate and handle file uploads for Atlet data.
     */
    private function fillAtletData(Atlet $atlet, array $data, Request $request)
    {
        // Filling text data
        $stringFields = ['cabor_id', 'klasifikasi_disabilitas_id', 'name', 'jenis_disabilitas', 'nik', 'birth_place', 'birth_date', 'religion', 'gender', 'address', 'blood_type', 'last_education'];

        foreach ($stringFields as $field) {
            if (isset($data[$field])) {
                $atlet->$field = $data[$field];
            }
        }

        // File uploads
        $fileFields = ['photo_path', 'ktp_path', 'achievement_certificate_path', 'education_certificate_path', 'npwp_path', 'sk_path'];
        $baseDir = 'uploads/atlet';

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old file if exists
                if ($atlet->$field) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $atlet->$field));
                }

                $file = $request->file($field);
                $filename = time() . '_' . Str::random(5) . '_' . $field . '.' . $file->getClientOriginalExtension();

                // Keep the path inside 'public' disk
                $path = $file->storeAs($baseDir, $filename, 'public');
                $atlet->$field = 'storage/' . $path;
            }
        }

        return $atlet;
    }
}
