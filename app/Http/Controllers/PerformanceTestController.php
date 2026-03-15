<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerformanceTest;
use App\Models\PerformanceTestResult;
use App\Models\Atlet;
use App\Models\Cabor;
use App\Models\KlasifikasiDisabilitas;
use App\Models\JenisDisabilitas;
use App\Models\PhysicalTestCategory;
use App\Models\PhysicalTestItem;
use App\Models\PhysicalTestItemScore;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class PerformanceTestController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PerformanceTest::with([
                'atlet',
                'cabor',
                'jenisDisabilitas',
            ])->where('is_active', 1)->latest();

            if ($request->filled('cabor_id')) {
                $data->where('cabor_id', $request->cabor_id);
            }
            if ($request->filled('atlet_id')) {
                $data->where('atlet_id', $request->atlet_id);
            }
            if ($request->filled('tanggal_from')) {
                $data->whereDate('tanggal_pelaksanaan', '>=', $request->tanggal_from);
            }
            if ($request->filled('tanggal_to')) {
                $data->whereDate('tanggal_pelaksanaan', '<=', $request->tanggal_to);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('tanggal', fn($r) => $r->tanggal_pelaksanaan?->format('d/m/Y') ?? '-')
                ->addColumn('atlet_name', fn($r) => $r->atlet?->name ?? '-')
                ->addColumn('cabor_name', fn($r) => $r->cabor?->name ?? '-')
                ->addColumn('disabilitas', fn($r) => $r->jenisDisabilitas?->nama_jenis ?? 'Semua')
                ->addColumn('status_badge', function ($r) {
                    $map = [
                        'fit' => ['label' => 'Fit', 'class' => 'badge-light-success'],
                        'cidera' => ['label' => 'Cidera', 'class' => 'badge-light-danger'],
                        'rehabilitasi' => ['label' => 'Rehabilitasi', 'class' => 'badge-light-warning'],
                    ];
                    $s = $map[$r->status_kesehatan] ?? ['label' => $r->status_kesehatan, 'class' => 'badge-light'];
                    return '<span class="badge ' . $s['class'] . ' fw-bold">' . $s['label'] . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex justify-content-center flex-shrink-0">';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 viewPerformance" title="Detail">
                                <i class="ki-duotone ki-eye fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                             </a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-icon btn-bg-light btn-active-color-warning btn-sm me-1 editPerformance" title="Edit">
                                <i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>
                             </a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm deletePerformance" title="Hapus">
                                <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                             </a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action', 'status_badge'])
                ->make(true);
        }

        $cabors = Cabor::where('is_active', 1)->get();
        $atlets = Atlet::where('is_active', 1)->with(['cabor', 'jenisDisabilitas', 'klasifikasi_disabilitas'])->get();
        $disabilitas = JenisDisabilitas::where('is_active', 1)->get();
        $klasifikasis = KlasifikasiDisabilitas::all();

        return view('pages.tes_performa.index', compact('cabors', 'atlets', 'disabilitas', 'klasifikasis'))
            ->with([
                'title' => 'Input Tes Performa',
                'breadcrum' => ['Tes & Evaluasi', 'Input Tes Performa'],
            ]);
    }

    /**  Returns atlet info + relevant test items for the form */
    public function getAtletData($atletId)
    {
        $atlet = Atlet::with(['cabor', 'jenisDisabilitas', 'klasifikasi_disabilitas'])->findOrFail($atletId);
        return response()->json(['atlet' => $atlet]);
    }

    /**  Returns physical test categories + items filtered to this atlet's disabilitas */
    public function getTestItems($atletId)
    {
        $atlet = Atlet::findOrFail($atletId);

        $categories = PhysicalTestCategory::with([
            'items' => function ($q) use ($atlet) {
                $q->where('is_active', 1)
                    ->where(function ($q2) use ($atlet) {
                        $q2->whereNull('jenis_disabilitas_id')
                            ->orWhere('jenis_disabilitas_id', $atlet->jenis_disabilitas_id);
                    })
                    ->with(['scores' => fn($s) => $s->where('is_active', 1)->orderBy('score', 'desc')]);
            }
        ])
            ->where('cabor_id', $atlet->cabor_id)
            ->where('is_active', 1)
            ->orderBy('id')
            ->get();

        return response()->json(['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'atlet_id' => 'required|exists:atlets,id',
            'cabor_id' => 'required|exists:cabors,id',
            'tanggal_pelaksanaan' => 'required|date',
            'status_kesehatan' => 'required|in:fit,cidera,rehabilitasi',
            'results' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $test = PerformanceTest::create([
                'atlet_id' => $request->atlet_id,
                'cabor_id' => $request->cabor_id,
                'klasifikasi_disabilitas_id' => $request->klasifikasi_disabilitas_id,
                'jenis_disabilitas_id' => $request->jenis_disabilitas_id,
                'alat_bantu' => $request->alat_bantu,
                'status_kesehatan' => $request->status_kesehatan,
                'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
                'spesialisasi' => $request->spesialisasi,
                'penguji' => $request->penguji,
                'is_active' => 1,
            ]);

            // Save results
            if ($request->results) {
                foreach ($request->results as $itemId => $nilai) {
                    if ($nilai === null || $nilai === '') continue;

                    // Auto-resolve score
                    $scoreId = null;
                    $scores = PhysicalTestItemScore::where('physical_test_item_id', $itemId)
                        ->where('is_active', 1)->get();
                    foreach ($scores as $score) {
                        $minOk = $score->min_value === null || $nilai >= $score->min_value;
                        $maxOk = $score->max_value === null || $nilai <= $score->max_value;
                        if ($minOk && $maxOk) {
                            $scoreId = $score->id;
                            break;
                        }
                    }

                    PerformanceTestResult::create([
                        'performance_test_id' => $test->id,
                        'physical_test_item_id' => $itemId,
                        'nilai' => $nilai,
                        'physical_test_item_score_id' => $scoreId,
                        'is_active' => 1,
                    ]);
                }
            }

            DB::commit();
            return response()->json(['success' => 'Data tes performa berhasil disimpan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $test = PerformanceTest::with([
            'atlet',
            'cabor',
            'jenisDisabilitas',
            'klasifikasiDisabilitas',
            'results.physicalTestItem.category',
            'results.physicalTestItemScore',
        ])->findOrFail($id);

        return response()->json(['data' => $test]);
    }

    public function edit($id)
    {
        $test = PerformanceTest::with([
            'results.physicalTestItem',
        ])->findOrFail($id);

        return response()->json(['data' => $test]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'atlet_id' => 'required|exists:atlets,id',
            'cabor_id' => 'required|exists:cabors,id',
            'tanggal_pelaksanaan' => 'required|date',
            'status_kesehatan' => 'required|in:fit,cidera,rehabilitasi',
            'results' => 'nullable|array',
        ]);

        $test = PerformanceTest::findOrFail($id);

        DB::beginTransaction();
        try {
            $test->update([
                'atlet_id' => $request->atlet_id,
                'cabor_id' => $request->cabor_id,
                'klasifikasi_disabilitas_id' => $request->klasifikasi_disabilitas_id,
                'jenis_disabilitas_id' => $request->jenis_disabilitas_id,
                'alat_bantu' => $request->alat_bantu,
                'status_kesehatan' => $request->status_kesehatan,
                'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
                'spesialisasi' => $request->spesialisasi,
                'penguji' => $request->penguji,
            ]);

            // Delete old results and re-insert
            $test->results()->delete();

            if ($request->results) {
                foreach ($request->results as $itemId => $nilai) {
                    if ($nilai === null || $nilai === '') continue;

                    $scoreId = null;
                    $scores = PhysicalTestItemScore::where('physical_test_item_id', $itemId)
                        ->where('is_active', 1)->get();
                    foreach ($scores as $score) {
                        $minOk = $score->min_value === null || $nilai >= $score->min_value;
                        $maxOk = $score->max_value === null || $nilai <= $score->max_value;
                        if ($minOk && $maxOk) {
                            $scoreId = $score->id;
                            break;
                        }
                    }

                    PerformanceTestResult::create([
                        'performance_test_id' => $test->id,
                        'physical_test_item_id' => $itemId,
                        'nilai' => $nilai,
                        'physical_test_item_score_id' => $scoreId,
                        'is_active' => 1,
                    ]);
                }
            }

            DB::commit();
            return response()->json(['success' => 'Data tes performa berhasil diperbarui.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal memperbarui: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $test = PerformanceTest::findOrFail($id);
        $test->update(['is_active' => 0]);
        $test->delete();
        return response()->json(['success' => 'Data tes performa berhasil dihapus.']);
    }
}
