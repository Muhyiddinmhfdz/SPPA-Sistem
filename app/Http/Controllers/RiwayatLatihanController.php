<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Atlet;
use App\Models\Coach;
use App\Models\Cabor;
use App\Models\MonitoringLatihan;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class RiwayatLatihanController extends Controller
{
    public function index()
    {
        $cabors = Cabor::where('is_active', 1)->orderBy('name')->get();
        $title = 'Riwayat Latihan';
        $breadcrum = ['Monitoring', 'Monitoring Latihan', 'Riwayat Latihan'];
        return view('pages.riwayat_latihan.index', compact('cabors', 'title', 'breadcrum'));
    }

    public function dataAtlet(Request $request)
    {
        $query = Atlet::with('cabor')->where('is_active', 1)
            ->withCount(['monitoringLatihan']);

        if ($request->filled('cabor_id'))    $query->where('cabor_id', $request->cabor_id);
        if ($request->filled('search_name')) $query->where('name', 'like', '%' . $request->search_name . '%');

        return DataTables::of($query->latest('atlets.created_at'))
            ->addIndexColumn()
            ->addColumn('cabor_name',       fn($r) => $r->cabor?->name ?? '-')
            ->addColumn('jumlah_latihan',   fn($r) => $r->monitoring_latihan_count . ' sesi')
            ->addColumn('action', fn($r) =>
            '<a href="javascript:void(0)" class="btn btn-sm btn-primary btnRiwayatLatihan"
                    data-id="' . $r->id . '" data-type="atlet" data-name="' . htmlspecialchars($r->name) . '">
                    <i class="ki-duotone ki-eye fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    Riwayat
                </a>')
            ->rawColumns(['action'])->make(true);
    }

    public function dataPelatih(Request $request)
    {
        $query = Coach::with('cabor')->where('is_active', 1)
            ->withCount(['monitoringLatihan']);

        if ($request->filled('cabor_id'))    $query->where('cabor_id', $request->cabor_id);
        if ($request->filled('search_name')) $query->where('name', 'like', '%' . $request->search_name . '%');

        return DataTables::of($query->latest('coaches.created_at'))
            ->addIndexColumn()
            ->addColumn('cabor_name',       fn($r) => $r->cabor?->name ?? '-')
            ->addColumn('jumlah_latihan',   fn($r) => $r->monitoring_latihan_count . ' sesi')
            ->addColumn('action', fn($r) =>
            '<a href="javascript:void(0)" class="btn btn-sm btn-primary btnRiwayatLatihan"
                    data-id="' . $r->id . '" data-type="pelatih" data-name="' . htmlspecialchars($r->name) . '">
                    <i class="ki-duotone ki-eye fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    Riwayat
                </a>')
            ->rawColumns(['action'])->make(true);
    }

    public function detailRiwayat(Request $request)
    {
        $query = MonitoringLatihan::where('person_id', $request->person_id)
            ->where('person_type', $request->person_type)
            ->where('is_active', 1);

        return DataTables::of($query->latest())
            ->addIndexColumn()
            ->addColumn('tanggal_fmt',       fn($r) => $r->tanggal ? $r->tanggal->locale('id')->isoFormat('D MMMM YYYY') : '-')
            ->addColumn('kehadiran_badge',   fn($r) => self::badge(
                $r->kehadiran,
                ['hadir' => 'success', 'tidak_hadir' => 'danger', 'izin' => 'warning', 'sakit' => 'info'],
                ['hadir' => 'Hadir', 'tidak_hadir' => 'Tidak Hadir', 'izin' => 'Izin', 'sakit' => 'Sakit']
            ))
            ->addColumn('beban_badge',       fn($r) => self::badge(
                $r->beban_latihan,
                ['ringan' => 'success', 'sedang' => 'warning', 'berat' => 'danger'],
                ['ringan' => 'Ringan', 'sedang' => 'Sedang', 'berat' => 'Berat']
            ))
            ->addColumn('kesimpulan_badge',  fn($r) => self::badge(
                $r->kesimpulan,
                ['ya' => 'success', 'tidak' => 'warning'],
                ['ya' => 'Ya – Lanjut', 'tidak' => 'Tidak – Evaluasi']
            ))
            ->addColumn('catatan_short',     fn($r) => $r->catatan_pelatih
                ? '<span title="' . htmlspecialchars($r->catatan_pelatih) . '">' . Str::limit($r->catatan_pelatih, 40) . '</span>'
                : '<span class="text-muted">-</span>')
            ->addColumn('durasi_latihan',   fn($r) => ($r->durasi_latihan ?? '-') . ' Menit')
            ->rawColumns(['kehadiran_badge', 'beban_badge', 'kesimpulan_badge', 'catatan_short'])
            ->make(true);
    }

    private static function badge($val, array $colors, array $labels)
    {
        $color = $colors[$val] ?? 'secondary';
        $label = $labels[$val] ?? $val;
        return "<span class=\"badge badge-light-{$color} fw-bold\">{$label}</span>";
    }
}
