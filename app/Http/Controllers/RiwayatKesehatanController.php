<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Atlet;
use App\Models\Coach;
use App\Models\Cabor;
use App\Models\CekKesehatan;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class RiwayatKesehatanController extends Controller
{
    // ─── Main page (returns view with cabor list) ────────────────────────────
    public function index()
    {
        $cabors = Cabor::where('is_active', 1)->orderBy('name')->get();
        $title = 'Riwayat Kesehatan';
        $breadcrum = ['Monitoring', 'Kesehatan', 'Riwayat Kesehatan'];
        return view('pages.riwayat_kesehatan.index', compact('cabors', 'title', 'breadcrum'));
    }

    // ─── DataTable: Atlet list with health record count ──────────────────────
    public function dataAtlet(Request $request)
    {
        $query = Atlet::with('cabor')
            ->where('is_active', 1)
            ->withCount(['cekKesehatan']);

        if ($request->filled('cabor_id')) {
            $query->where('cabor_id', $request->cabor_id);
        }
        if ($request->filled('search_name')) {
            $query->where('name', 'like', '%' . $request->search_name . '%');
        }

        return DataTables::of($query->latest('atlets.created_at'))
            ->addIndexColumn()
            ->addColumn('cabor_name', fn($r) => $r->cabor?->name ?? '-')
            ->addColumn('jumlah_pemeriksaan', fn($r) => $r->cek_kesehatan_count . ' kali')
            ->addColumn('action', function ($r) {
                return '<a href="javascript:void(0)" class="btn btn-sm btn-primary btnRiwayat"
                    data-id="' . $r->id . '" data-type="atlet" data-name="' . htmlspecialchars($r->name) . '">
                    <i class="ki-duotone ki-eye fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    Riwayat
                </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    // ─── DataTable: Pelatih list with health record count ────────────────────
    public function dataPelatih(Request $request)
    {
        $query = Coach::with('cabor')
            ->where('is_active', 1)
            ->withCount(['cekKesehatan']);

        if ($request->filled('cabor_id')) {
            $query->where('cabor_id', $request->cabor_id);
        }
        if ($request->filled('search_name')) {
            $query->where('name', 'like', '%' . $request->search_name . '%');
        }

        return DataTables::of($query->latest('coaches.created_at'))
            ->addIndexColumn()
            ->addColumn('cabor_name', fn($r) => $r->cabor?->name ?? '-')
            ->addColumn('jumlah_pemeriksaan', fn($r) => $r->cek_kesehatan_count . ' kali')
            ->addColumn('action', function ($r) {
                return '<a href="javascript:void(0)" class="btn btn-sm btn-primary btnRiwayat"
                    data-id="' . $r->id . '" data-type="pelatih" data-name="' . htmlspecialchars($r->name) . '">
                    <i class="ki-duotone ki-eye fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    Riwayat
                </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    // ─── DataTable: Detail riwayat for a specific person ─────────────────────
    public function detailRiwayat(Request $request)
    {
        $personId   = $request->person_id;
        $personType = $request->person_type; // 'atlet' or 'pelatih'

        $query = CekKesehatan::where('person_id', $personId)
            ->where('person_type', $personType)
            ->where('is_active', 1);

        return DataTables::of($query->latest())
            ->addIndexColumn()
            ->addColumn('tanggal_fmt', fn($r) => $r->tanggal ? $r->tanggal->locale('id')->isoFormat('D MMMM YYYY') : '-')
            ->addColumn('kondisi_badge', fn($r) => self::kondisiBadge($r->kondisi_harian))
            ->addColumn('cedera_badge', fn($r) => self::cederaBadge($r->tingkat_cedera))
            ->addColumn('kesimpulan_badge', fn($r) => self::kesimpulanBadge($r->kesimpulan))
            ->addColumn('riwayat_medis_short', fn($r) => $r->riwayat_medis
                ? '<span title="' . htmlspecialchars($r->riwayat_medis) . '">' . Str::limit($r->riwayat_medis, 40) . '</span>'
                : '<span class="text-muted">-</span>')
            ->rawColumns(['kondisi_badge', 'cedera_badge', 'kesimpulan_badge', 'riwayat_medis_short'])
            ->make(true);
    }

    // ─── Badge Helpers ────────────────────────────────────────────────────────
    private static function kondisiBadge($val)
    {
        return match ($val) {
            'sehat'  => '<span class="badge badge-light-success fw-bold">Sehat</span>',
            'lelah'  => '<span class="badge badge-light-warning fw-bold">Lelah</span>',
            'cidera' => '<span class="badge badge-light-danger fw-bold">Cidera</span>',
            default  => '-',
        };
    }

    private static function cederaBadge($val)
    {
        return match ($val) {
            'tidak_cidera' => '<span class="badge badge-light-success fw-bold">Tidak Cidera</span>',
            'ringan'       => '<span class="badge badge-light-info fw-bold">Ringan</span>',
            'sedang'       => '<span class="badge badge-light-warning fw-bold">Sedang</span>',
            'berat'        => '<span class="badge badge-light-danger fw-bold">Berat</span>',
            default        => '-',
        };
    }

    private static function kesimpulanBadge($val)
    {
        return match ($val) {
            'baik'   => '<span class="badge badge-light-success fw-bold">Baik – Lanjut Program</span>',
            'sedang' => '<span class="badge badge-light-warning fw-bold">Sedang – Evaluasi</span>',
            'berat'  => '<span class="badge badge-light-danger fw-bold">Berat – Hentikan</span>',
            default  => '-',
        };
    }
}
