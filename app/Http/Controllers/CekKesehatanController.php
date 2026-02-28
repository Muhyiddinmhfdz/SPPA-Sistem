<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CekKesehatan;
use App\Models\Cabor;
use App\Models\Atlet;
use App\Models\Coach;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CekKesehatanController extends Controller
{
    // ─── DataTable for Atlet tab ────────────────────────────────────────────
    public function indexAtlet(Request $request)
    {
        if ($request->ajax()) {
            $query = CekKesehatan::with(['atlet', 'cabor'])
                ->where('person_type', 'atlet')
                ->where('is_active', 1);

            if ($request->filled('cabor_id')) {
                $query->where('cabor_id', $request->cabor_id);
            }
            if ($request->filled('person_id')) {
                $query->where('person_id', $request->person_id);
            }

            return DataTables::of($query->latest('cek_kesehatan.created_at'))
                ->addIndexColumn()
                ->addColumn('nama', fn($r) => $r->atlet?->name ?? '-')
                ->addColumn('cabor_name', fn($r) => $r->cabor?->name ?? '-')
                ->addColumn('tanggal_fmt', fn($r) => $r->tanggal ? $r->tanggal->locale('id')->isoFormat('D MMMM YYYY') : '-')
                ->addColumn('kondisi_badge', fn($r) => self::kondisiBadge($r->kondisi_harian))
                ->addColumn('cedera_badge', fn($r) => self::cederaBadge($r->tingkat_cedera))
                ->addColumn('kesimpulan_badge', fn($r) => self::kesimpulanBadge($r->kesimpulan))
                ->addColumn('action', fn($r) => self::actionBtn($r))
                ->rawColumns(['kondisi_badge', 'cedera_badge', 'kesimpulan_badge', 'action'])
                ->make(true);
        }

        $cabors = Cabor::where('is_active', 1)->orderBy('name')->get();
        $atlets = Atlet::where('is_active', 1)->orderBy('name')->get();

        $title = 'Test Kesehatan';
        $breadcrum = ['Monitoring', 'Kesehatan', 'Test Kesehatan'];

        return view('pages.cek_kesehatan.index', compact('cabors', 'atlets', 'title', 'breadcrum'));
    }

    // ─── DataTable for Pelatih tab ───────────────────────────────────────────
    public function indexPelatih(Request $request)
    {
        if ($request->ajax()) {
            $query = CekKesehatan::with(['coach', 'cabor'])
                ->where('person_type', 'pelatih')
                ->where('is_active', 1);

            if ($request->filled('cabor_id')) {
                $query->where('cabor_id', $request->cabor_id);
            }
            if ($request->filled('person_id')) {
                $query->where('person_id', $request->person_id);
            }

            return DataTables::of($query->latest('cek_kesehatan.created_at'))
                ->addIndexColumn()
                ->addColumn('nama', fn($r) => $r->coach?->name ?? '-')
                ->addColumn('cabor_name', fn($r) => $r->cabor?->name ?? '-')
                ->addColumn('tanggal_fmt', fn($r) => $r->tanggal ? $r->tanggal->locale('id')->isoFormat('D MMMM YYYY') : '-')
                ->addColumn('kondisi_badge', fn($r) => self::kondisiBadge($r->kondisi_harian))
                ->addColumn('cedera_badge', fn($r) => self::cederaBadge($r->tingkat_cedera))
                ->addColumn('kesimpulan_badge', fn($r) => self::kesimpulanBadge($r->kesimpulan))
                ->addColumn('action', fn($r) => self::actionBtn($r))
                ->rawColumns(['kondisi_badge', 'cedera_badge', 'kesimpulan_badge', 'action'])
                ->make(true);
        }

        return response()->json(['error' => 'Not an AJAX request'], 400);
    }

    // ─── Get persons by cabor for dynamic dropdown ───────────────────────────
    public function getPersonsByCabor(Request $request)
    {
        $type = $request->type; // 'atlet' or 'pelatih'
        $caborId = $request->cabor_id;

        if ($type === 'atlet') {
            $persons = Atlet::where('is_active', 1)
                ->when($caborId, fn($q) => $q->where('cabor_id', $caborId))
                ->orderBy('name')->get(['id', 'name']);
        } else {
            $persons = Coach::where('is_active', 1)
                ->when($caborId, fn($q) => $q->where('cabor_id', $caborId))
                ->orderBy('name')->get(['id', 'name']);
        }

        return response()->json($persons);
    }

    // ─── Store ───────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'person_type'    => 'required|in:atlet,pelatih',
            'person_id'      => 'required|integer',
            'cabor_id'       => 'required|exists:cabors,id',
            'tanggal'        => 'required|date',
            'kondisi_harian' => 'required|in:sehat,lelah,cidera',
            'tingkat_cedera' => 'required|in:tidak_cidera,ringan,sedang,berat',
            'riwayat_medis'  => 'nullable|string|max:2000',
            'kesimpulan'     => 'required|in:baik,sedang,berat',
            'catatan'        => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $data = $request->only([
            'person_type',
            'person_id',
            'cabor_id',
            'tanggal',
            'kondisi_harian',
            'tingkat_cedera',
            'riwayat_medis',
            'kesimpulan',
            'catatan',
        ]);
        $data['dibuat_oleh'] = Auth::id();

        CekKesehatan::create($data);

        return response()->json(['success' => 'Data cek kesehatan berhasil disimpan.']);
    }

    // ─── Edit ────────────────────────────────────────────────────────────────
    public function edit($id)
    {
        $record = CekKesehatan::find($id);
        if (!$record) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
        return response()->json($record);
    }

    // ─── Update ──────────────────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $record = CekKesehatan::find($id);
        if (!$record) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'person_type'    => 'required|in:atlet,pelatih',
            'person_id'      => 'required|integer',
            'cabor_id'       => 'required|exists:cabors,id',
            'tanggal'        => 'required|date',
            'kondisi_harian' => 'required|in:sehat,lelah,cidera',
            'tingkat_cedera' => 'required|in:tidak_cidera,ringan,sedang,berat',
            'riwayat_medis'  => 'nullable|string|max:2000',
            'kesimpulan'     => 'required|in:baik,sedang,berat',
            'catatan'        => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $record->update($request->only([
            'person_type',
            'person_id',
            'cabor_id',
            'tanggal',
            'kondisi_harian',
            'tingkat_cedera',
            'riwayat_medis',
            'kesimpulan',
            'catatan',
        ]));

        return response()->json(['success' => 'Data cek kesehatan berhasil diperbarui.']);
    }

    // ─── Destroy (soft) ──────────────────────────────────────────────────────
    public function destroy($id)
    {
        $record = CekKesehatan::find($id);
        if (!$record) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
        $record->update(['is_active' => 0]);
        $record->delete();
        return response()->json(['success' => 'Data cek kesehatan berhasil dihapus.']);
    }

    // ─── Badge Helpers ────────────────────────────────────────────────────────
    private static function kondisiBadge($val)
    {
        $map = [
            'sehat'  => '<span class="badge badge-light-success fw-bold">Sehat</span>',
            'lelah'  => '<span class="badge badge-light-warning fw-bold">Lelah</span>',
            'cidera' => '<span class="badge badge-light-danger fw-bold">Cidera</span>',
        ];
        return $map[$val] ?? '-';
    }

    private static function cederaBadge($val)
    {
        $map = [
            'tidak_cidera' => '<span class="badge badge-light-success fw-bold">Tidak Cidera</span>',
            'ringan'       => '<span class="badge badge-light-info fw-bold">Ringan</span>',
            'sedang'       => '<span class="badge badge-light-warning fw-bold">Sedang</span>',
            'berat'        => '<span class="badge badge-light-danger fw-bold">Berat</span>',
        ];
        return $map[$val] ?? '-';
    }

    private static function kesimpulanBadge($val)
    {
        $map = [
            'baik'   => '<span class="badge badge-light-success fw-bold">Baik – Lanjut Program</span>',
            'sedang' => '<span class="badge badge-light-warning fw-bold">Sedang – Evaluasi Program</span>',
            'berat'  => '<span class="badge badge-light-danger fw-bold">Berat – Hentikan/Sesuaikan</span>',
        ];
        return $map[$val] ?? '-';
    }

    private static function actionBtn($row)
    {
        $btn  = '<div class="d-flex gap-1 justify-content-center">';
        $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm editCekKesehatan" title="Edit"><i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i></a>';
        $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm deleteCekKesehatan" title="Hapus"><i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></a>';
        $btn .= '</div>';
        return $btn;
    }
}
