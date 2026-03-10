<?php

namespace App\Http\Controllers;

use App\Models\Kompetisi;
use App\Models\Cabor;
use App\Models\Atlet;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KompetisiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Kompetisi::with(['cabor', 'atlet'])->where('is_active', 1);

            if ($request->filled('cabor_id')) {
                $data->where('cabor_id', $request->cabor_id);
            }

            if ($request->filled('atlet_id')) {
                $data->where('atlet_id', $request->atlet_id);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('atlet_name', function ($row) {
                    return $row->atlet ? $row->atlet->name : '-';
                })
                ->addColumn('cabor_name', function ($row) {
                    return $row->cabor ? $row->cabor->name : '-';
                })
                ->addColumn('tanggal', function ($row) {
                    return $row->waktu_pelaksanaan ? $row->waktu_pelaksanaan->format('d-m-Y') : '-';
                })
                ->addColumn('medali_badge', function ($row) {
                    $badges = [
                        'emas' => '<span class="badge badge-light-warning fw-bold">Emas</span>',
                        'perak' => '<span class="badge badge-light-secondary fw-bold">Perak</span>',
                        'perunggu' => '<span class="badge badge-light-danger fw-bold">Perunggu</span>',
                        'tanpa_medali' => '<span class="badge badge-light-dark fw-bold">-</span>',
                    ];
                    return $badges[$row->hasil_medali] ?? '-';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex justify-content-center flex-shrink-0">';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 editKompetisi" title="Edit">
                                <i class="ki-duotone ki-pencil fs-2">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                             </a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm deleteKompetisi" title="Hapus">
                                <i class="ki-duotone ki-trash fs-2">
                                    <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
                                </i>
                             </a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['medali_badge', 'action'])
                ->make(true);
        }

        $cabors = Cabor::where('is_active', 1)->orderBy('name', 'asc')->get();
        return view('pages.kompetisi.index', compact('cabors'))
            ->with(['title' => 'Modul Kompetisi', 'breadcrum' => ['Pembinaan', 'Modul Kompetisi']]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cabor_id' => 'required|exists:cabors,id',
            'atlet_id' => 'required|exists:atlets,id',
            'tingkatan' => 'required|in:Internasional,Nasional,Daerah',
            'nama_kompetisi' => 'required|string|max:255',
            'waktu_pelaksanaan' => 'required|date',
            'tempat_pelaksanaan' => 'required|string|max:255',
            'jumlah_peserta' => 'nullable|integer',
            'hasil_peringkat' => 'nullable|string|max:255',
            'hasil_medali' => 'nullable|in:emas,perak,perunggu,tanpa_medali',
            'kesimpulan_evaluasi' => 'nullable|string',
        ], [
            'cabor_id.required' => 'Cabang Olahraga wajib dipilih.',
            'cabor_id.exists' => 'Cabang Olahraga yang dipilih tidak valid.',
            'atlet_id.required' => 'Atlet wajib dipilih.',
            'atlet_id.exists' => 'Atlet yang dipilih tidak valid.',
            'tingkatan.required' => 'Tingkatan wajib dipilih.',
            'nama_kompetisi.required' => 'Nama Kompetisi wajib diisi.',
            'waktu_pelaksanaan.required' => 'Waktu Pelaksanaan wajib diisi.',
            'tempat_pelaksanaan.required' => 'Tempat Pelaksanaan wajib diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $data = $request->all();
            $data['dicatat_oleh'] = Auth::id();
            Kompetisi::create($data);

            return response()->json(['success' => 'Data Kompetisi berhasil disimpan.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $kompetisi = Kompetisi::findOrFail($id);
        return response()->json($kompetisi);
    }

    public function update(Request $request, $id)
    {
        $kompetisi = Kompetisi::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'cabor_id' => 'required|exists:cabors,id',
            'atlet_id' => 'required|exists:atlets,id',
            'tingkatan' => 'required|in:Internasional,Nasional,Daerah',
            'nama_kompetisi' => 'required|string|max:255',
            'waktu_pelaksanaan' => 'required|date',
            'tempat_pelaksanaan' => 'required|string|max:255',
            'jumlah_peserta' => 'nullable|integer',
            'hasil_peringkat' => 'nullable|string|max:255',
            'hasil_medali' => 'nullable|in:emas,perak,perunggu,tanpa_medali',
            'kesimpulan_evaluasi' => 'nullable|string',
        ], [
            'cabor_id.required' => 'Cabang Olahraga wajib dipilih.',
            'cabor_id.exists' => 'Cabang Olahraga yang dipilih tidak valid.',
            'atlet_id.required' => 'Atlet wajib dipilih.',
            'atlet_id.exists' => 'Atlet yang dipilih tidak valid.',
            'tingkatan.required' => 'Tingkatan wajib dipilih.',
            'nama_kompetisi.required' => 'Nama Kompetisi wajib diisi.',
            'waktu_pelaksanaan.required' => 'Waktu Pelaksanaan wajib diisi.',
            'tempat_pelaksanaan.required' => 'Tempat Pelaksanaan wajib diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $kompetisi->update($request->all());
            return response()->json(['success' => 'Data Kompetisi berhasil diperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal memperbarui data: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $kompetisi = Kompetisi::findOrFail($id);
            $kompetisi->update(['is_active' => 0]);
            $kompetisi->delete();

            return response()->json(['success' => 'Data Kompetisi berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }

    public function getAtletsByCabor($caborId)
    {
        $atlets = Atlet::where('cabor_id', $caborId)->where('is_active', 1)->orderBy('name', 'asc')->get(['id', 'name']);
        return response()->json($atlets);
    }
}
