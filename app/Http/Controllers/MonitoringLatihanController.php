<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MonitoringLatihan;
use App\Models\Cabor;
use App\Models\Atlet;
use App\Models\Coach;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MonitoringLatihanController extends Controller
{
    // ─── DataTable Atlet ─────────────────────────────────────────────────────
    public function indexAtlet(Request $request)
    {
        if ($request->ajax()) {
            $query = MonitoringLatihan::with(['atlet', 'cabor'])
                ->where('person_type', 'atlet')
                ->where('is_active', 1);

            if ($request->filled('cabor_id'))  $query->where('cabor_id', $request->cabor_id);
            if ($request->filled('person_id')) $query->where('person_id', $request->person_id);

            return DataTables::of($query->latest('monitoring_latihan.created_at'))
                ->addIndexColumn()
                ->addColumn('nama',          fn($r) => $r->atlet?->name ?? '-')
                ->addColumn('cabor_name',    fn($r) => $r->cabor?->name ?? '-')
                ->addColumn('tanggal_fmt',   fn($r) => $r->tanggal ? $r->tanggal->locale('id')->isoFormat('D MMMM YYYY') : '-')
                ->addColumn('durasi_latihan', fn($r) => ($r->durasi_latihan ?? '-') . ' Menit')
                ->addColumn('kehadiran_badge', fn($r) => self::kehadiranBadge($r->kehadiran))
                ->addColumn('beban_badge',   fn($r) => self::bebanBadge($r->beban_latihan))
                ->addColumn('kesimpulan_badge', fn($r) => self::kesimpulanBadge($r->kesimpulan))
                ->addColumn('action',        fn($r) => self::actionBtn($r))
                ->rawColumns(['kehadiran_badge', 'beban_badge', 'kesimpulan_badge', 'action'])
                ->make(true);
        }

        $cabors = Cabor::where('is_active', 1)->orderBy('name')->get();
        $atlets = Atlet::where('is_active', 1)->orderBy('name')->get();
        $title = 'Input Monitoring Latihan';
        $breadcrum = ['Monitoring', 'Monitoring Latihan', 'Input Monitoring'];
        return view('pages.monitoring_latihan.index', compact('cabors', 'atlets', 'title', 'breadcrum'));
    }

    // ─── DataTable Pelatih ───────────────────────────────────────────────────
    public function indexPelatih(Request $request)
    {
        if ($request->ajax()) {
            $query = MonitoringLatihan::with(['coach', 'cabor'])
                ->where('person_type', 'pelatih')
                ->where('is_active', 1);

            if ($request->filled('cabor_id'))  $query->where('cabor_id', $request->cabor_id);
            if ($request->filled('person_id')) $query->where('person_id', $request->person_id);

            return DataTables::of($query->latest('monitoring_latihan.created_at'))
                ->addIndexColumn()
                ->addColumn('nama',          fn($r) => $r->coach?->name ?? '-')
                ->addColumn('cabor_name',    fn($r) => $r->cabor?->name ?? '-')
                ->addColumn('tanggal_fmt',   fn($r) => $r->tanggal ? $r->tanggal->locale('id')->isoFormat('D MMMM YYYY') : '-')
                ->addColumn('durasi_latihan', fn($r) => ($r->durasi_latihan ?? '-') . ' Menit')
                ->addColumn('kehadiran_badge', fn($r) => self::kehadiranBadge($r->kehadiran))
                ->addColumn('beban_badge',   fn($r) => self::bebanBadge($r->beban_latihan))
                ->addColumn('kesimpulan_badge', fn($r) => self::kesimpulanBadge($r->kesimpulan))
                ->addColumn('action',        fn($r) => self::actionBtn($r))
                ->rawColumns(['kehadiran_badge', 'beban_badge', 'kesimpulan_badge', 'action'])
                ->make(true);
        }
        return response()->json(['error' => 'Not AJAX'], 400);
    }

    // ─── Get persons by cabor ────────────────────────────────────────────────
    public function getPersonsByCabor(Request $request)
    {
        $type    = $request->type;
        $caborId = $request->cabor_id;

        $persons = ($type === 'atlet')
            ? Atlet::where('is_active', 1)->when($caborId, fn($q) => $q->where('cabor_id', $caborId))->orderBy('name')->get(['id', 'name'])
            : Coach::where('is_active', 1)->when($caborId, fn($q) => $q->where('cabor_id', $caborId))->orderBy('name')->get(['id', 'name']);

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
            'kehadiran'      => 'required|in:hadir,tidak_hadir,izin,sakit',
            'durasi_latihan' => 'required|numeric',
            'beban_latihan'  => 'required|in:ringan,sedang,berat',
            'denyut_nadi_rpe' => 'nullable|string|max:100',
            'catatan_pelatih' => 'nullable|string|max:2000',
            'kesimpulan'     => 'required|in:ya,tidak',
        ]);

        if ($validator->fails())
            return response()->json(['error' => $validator->errors()->first()], 422);

        $data = $request->only(['person_type', 'person_id', 'cabor_id', 'tanggal', 'kehadiran', 'durasi_latihan', 'beban_latihan', 'denyut_nadi_rpe', 'catatan_pelatih', 'kesimpulan']);
        $data['dicatat_oleh'] = Auth::id();

        MonitoringLatihan::create($data);
        return response()->json(['success' => 'Data monitoring latihan berhasil disimpan.']);
    }

    // ─── Edit ────────────────────────────────────────────────────────────────
    public function edit($id)
    {
        $record = MonitoringLatihan::find($id);
        return $record
            ? response()->json($record)
            : response()->json(['error' => 'Data tidak ditemukan.'], 404);
    }

    // ─── Update ──────────────────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $record = MonitoringLatihan::find($id);
        if (!$record) return response()->json(['error' => 'Data tidak ditemukan.'], 404);

        $validator = Validator::make($request->all(), [
            'person_type'    => 'required|in:atlet,pelatih',
            'person_id'      => 'required|integer',
            'cabor_id'       => 'required|exists:cabors,id',
            'tanggal'        => 'required|date',
            'kehadiran'      => 'required|in:hadir,tidak_hadir,izin,sakit',
            'durasi_latihan' => 'required|numeric',
            'beban_latihan'  => 'required|in:ringan,sedang,berat',
            'denyut_nadi_rpe' => 'nullable|string|max:100',
            'catatan_pelatih' => 'nullable|string|max:2000',
            'kesimpulan'     => 'required|in:ya,tidak',
        ]);

        if ($validator->fails())
            return response()->json(['error' => $validator->errors()->first()], 422);

        $record->update($request->only(['person_type', 'person_id', 'cabor_id', 'tanggal', 'kehadiran', 'durasi_latihan', 'beban_latihan', 'denyut_nadi_rpe', 'catatan_pelatih', 'kesimpulan']));
        return response()->json(['success' => 'Data monitoring latihan berhasil diperbarui.']);
    }

    // ─── Destroy ─────────────────────────────────────────────────────────────
    public function destroy($id)
    {
        $record = MonitoringLatihan::find($id);
        if (!$record) return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        $record->update(['is_active' => 0]);
        $record->delete();
        return response()->json(['success' => 'Data monitoring latihan berhasil dihapus.']);
    }

    // ─── Badge Helpers ────────────────────────────────────────────────────────
    private static function kehadiranBadge($val)
    {
        return match ($val) {
            'hadir'       => '<span class="badge badge-light-success fw-bold">Hadir</span>',
            'tidak_hadir' => '<span class="badge badge-light-danger fw-bold">Tidak Hadir</span>',
            'izin'        => '<span class="badge badge-light-warning fw-bold">Izin</span>',
            'sakit'       => '<span class="badge badge-light-info fw-bold">Sakit</span>',
            default       => '-',
        };
    }

    private static function bebanBadge($val)
    {
        return match ($val) {
            'ringan' => '<span class="badge badge-light-success fw-bold">Ringan</span>',
            'sedang' => '<span class="badge badge-light-warning fw-bold">Sedang</span>',
            'berat'  => '<span class="badge badge-light-danger fw-bold">Berat</span>',
            default  => '-',
        };
    }

    private static function kesimpulanBadge($val)
    {
        return match ($val) {
            'ya'    => '<span class="badge badge-light-success fw-bold">Ya – Lanjut Program</span>',
            'tidak' => '<span class="badge badge-light-warning fw-bold">Tidak – Evaluasi Program</span>',
            default => '-',
        };
    }

    private static function actionBtn($row)
    {
        $id = $row->id;
        return '<div class="d-flex gap-1 justify-content-center">
            <a href="javascript:void(0)" data-id="' . $id . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm editMonLat" title="Edit"><i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i></a>
            <a href="javascript:void(0)" data-id="' . $id . '" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm deleteMonLat" title="Hapus"><i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></a>
        </div>';
    }
}
