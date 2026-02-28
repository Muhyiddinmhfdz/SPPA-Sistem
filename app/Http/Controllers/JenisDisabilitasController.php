<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisDisabilitas;
use App\Models\KlasifikasiDisabilitas;
use Yajra\DataTables\Facades\DataTables;

class JenisDisabilitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = JenisDisabilitas::with('klasifikasi_disabilitas')->latest('jenis_disabilitas.created_at');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('kode_klasifikasi', function ($row) {
                    return $row->klasifikasi_disabilitas
                        ? '<span class="badge badge-light-primary fw-bold">' . $row->klasifikasi_disabilitas->kode_klasifikasi . '</span>'
                        : '-';
                })
                ->addColumn('nama_klasifikasi', function ($row) {
                    return $row->klasifikasi_disabilitas ? $row->klasifikasi_disabilitas->nama_klasifikasi : '-';
                })
                ->addColumn('action', function ($row) {
                    $btn  = '<div class="d-flex justify-content-center flex-shrink-0">';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 editJenis" title="Edit">';
                    $btn .= '<i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>';
                    $btn .= '</a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm deleteJenis" title="Hapus">';
                    $btn .= '<i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>';
                    $btn .= '</a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['kode_klasifikasi', 'action'])
                ->make(true);
        }

        $klasifikasis = KlasifikasiDisabilitas::orderBy('kode_klasifikasi', 'asc')->get();
        return view('pages.jenis_disabilitas.index', compact('klasifikasis'))
            ->with(['title' => 'Data Jenis Disabilitas', 'breadcrum' => ['Master Data', 'Jenis Disabilitas']]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'klasifikasi_disabilitas_id' => 'required|exists:klasifikasi_disabilitas,id',
            'nama_jenis' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        JenisDisabilitas::create($validated);
        return response()->json(['success' => 'Data Jenis Disabilitas berhasil disimpan.']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $jenis = JenisDisabilitas::findOrFail($id);
        return response()->json($jenis);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $jenis = JenisDisabilitas::findOrFail($id);

        $validated = $request->validate([
            'klasifikasi_disabilitas_id' => 'required|exists:klasifikasi_disabilitas,id',
            'nama_jenis' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $jenis->update($validated);
        return response()->json(['success' => 'Data Jenis Disabilitas berhasil diperbarui.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $jenis = JenisDisabilitas::findOrFail($id);
            $jenis->update(['is_active' => 0]);
            $jenis->delete(); // soft delete
            return response()->json(['success' => 'Data Jenis Disabilitas berhasil dinonaktifkan.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menonaktifkan: ' . $e->getMessage()], 500);
        }
    }
}
