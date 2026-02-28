<?php

namespace App\Http\Controllers;

use App\Models\KlasifikasiDisabilitas;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class KlasifikasiDisabilitasController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = KlasifikasiDisabilitas::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="edit btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 editKlasifikasi" title="Edit Data">
                                <i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>
                            </a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm deleteKlasifikasi" title="Hapus Data">
                                <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.klasifikasi_disabilitas.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_klasifikasi' => 'required|string|max:50|unique:klasifikasi_disabilitas,kode_klasifikasi',
            'nama_klasifikasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        KlasifikasiDisabilitas::create($request->all());

        return response()->json(['success' => 'Klasifikasi Disabilitas berhasil ditambahkan.']);
    }

    public function edit($id)
    {
        $klasifikasi = KlasifikasiDisabilitas::find($id);
        if (!$klasifikasi) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
        return response()->json($klasifikasi);
    }

    public function update(Request $request, $id)
    {
        $klasifikasi = KlasifikasiDisabilitas::find($id);
        if (!$klasifikasi) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'kode_klasifikasi' => 'required|string|max:50|unique:klasifikasi_disabilitas,kode_klasifikasi,' . $id,
            'nama_klasifikasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $klasifikasi->update($request->all());

        return response()->json(['success' => 'Klasifikasi Disabilitas berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $klasifikasi = KlasifikasiDisabilitas::find($id);
        if (!$klasifikasi) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }

        $klasifikasi->update(['is_active' => 0]);
        $klasifikasi->delete(); // soft delete

        return response()->json(['success' => 'Klasifikasi Disabilitas berhasil dinonaktifkan.']);
    }
}
