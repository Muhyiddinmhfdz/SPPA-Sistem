<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cabor;
use App\Models\Coach;
use App\Models\Medis;
use App\Models\Atlet;
use App\Models\KlasifikasiDisabilitas;
use App\Models\JenisDisabilitas;
use App\Models\TrainingType;
use App\Models\PembinaanPrestasi;
use App\Models\PerformanceTest;

use App\Models\CekKesehatan;
use App\Models\MonitoringLatihan;
use App\Models\Kompetisi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Summary Stats
        $totalCabor   = Cabor::where('is_active', 1)->count();
        $totalCoach   = Coach::where('is_active', 1)->count();
        $totalMedis   = Medis::where('is_active', 1)->count();
        $totalAtlet   = Atlet::where('is_active', 1)->count();
        $totalKlasifikasi = KlasifikasiDisabilitas::where('is_active', 1)->count();
        $totalJenis   = JenisDisabilitas::where('is_active', 1)->count();
        $totalTrainingType = TrainingType::where('is_active', 1)->count();

        // Atlet per Cabor (for bar chart)
        $atletPerCabor = Cabor::withCount(['atlets' => function ($q) {
            $q->where('is_active', 1);
        }])->where('is_active', 1)->get()->map(function ($c) {
            return ['name' => $c->name, 'count' => $c->atlets_count];
        })->values();

        // Atlet per Klasifikasi Disabilitas (for donut chart)
        $atletPerKlasifikasi = KlasifikasiDisabilitas::withCount(['atlets' => function ($q) {
            $q->where('is_active', 1);
        }])->where('is_active', 1)->get()->map(function ($k) {
            return ['name' => $k->kode_klasifikasi, 'count' => $k->atlets_count];
        })->values();

        // Gender distribution among athletes (for pie chart)
        $atletLaki   = Atlet::where('is_active', 1)->where('gender', 'L')->count();
        $atletWanita = Atlet::where('is_active', 1)->where('gender', 'P')->count();

        // Coach per Cabor (for bar chart)
        $coachPerCabor = Cabor::withCount(['coaches' => function ($q) {
            $q->where('is_active', 1);
        }])->where('is_active', 1)->get()->map(function ($c) {
            return ['name' => $c->name, 'count' => $c->coaches_count];
        })->values();

        // Medis by klasifikasi type
        $medisByType = [
            ['name' => 'Dokter',  'count' => Medis::where('is_active', 1)->where('klasifikasi', 'dokter')->count()],
            ['name' => 'Perawat', 'count' => Medis::where('is_active', 1)->where('klasifikasi', 'perawat')->count()],
            ['name' => 'Masseur', 'count' => Medis::where('is_active', 1)->where('klasifikasi', 'masseur')->count()],
        ];

        // Training Type per Cabor
        $trainingTypePerCabor = Cabor::withCount(['trainingTypes' => function ($q) {
            $q->where('is_active', 1);
        }])->where('is_active', 1)->get()->map(function ($c) {
            return ['name' => $c->name, 'count' => $c->training_types_count];
        })->values();

        // Medal Monitoring per Cabor
        $medalStats = Cabor::where('is_active', 1)
            ->withCount([
                'kompetisis as emas_count' => function ($q) {
                    $q->where('hasil_medali', 'emas');
                },
                'kompetisis as perak_count' => function ($q) {
                    $q->where('hasil_medali', 'perak');
                },
                'kompetisis as perunggu_count' => function ($q) {
                    $q->where('hasil_medali', 'perunggu');
                }
            ])->get()->map(function ($c) {
                return [
                    'id' => $c->id,
                    'name' => $c->name,
                    'emas' => $c->emas_count,
                    'perak' => $c->perak_count,
                    'perunggu' => $c->perunggu_count,
                    'total' => $c->emas_count + $c->perak_count + $c->perunggu_count
                ];
            })->values();

        // ===== Recent Monitoring Data (Role Based) =====
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $isAtlet = $user->hasRole('atlet');
        $isPelatih = $user->hasRole('pelatih');

        $healthQuery = CekKesehatan::with(['atlet', 'coach', 'cabor'])->where('is_active', 1)->latest();
        $monitoringQuery = MonitoringLatihan::with(['atlet', 'coach', 'cabor'])->where('is_active', 1)->latest();

        if ($isAtlet) {
            $healthQuery->where('person_type', 'atlet')->where('person_id', $user->atlet?->id);
            $monitoringQuery->where('person_type', 'atlet')->where('person_id', $user->atlet?->id);
        } elseif ($isPelatih) {
            $caborId = $user->coach?->cabor_id;
            $healthQuery->where('cabor_id', $caborId);
            $monitoringQuery->where('cabor_id', $caborId);
        }

        $recentHealth = $healthQuery->limit(10)->get();
        $recentMonitoring = $monitoringQuery->limit(10)->get();

        // ===== TAB 3: PEMBINAAN PRESTASI STATS =====
        $pembinaanQuery = PembinaanPrestasi::with(['atlet.cabor']);

        // Simple role check for Pembinaan
        if ($isAtlet) {
            $pembinaanQuery->where('atlet_id', $user->atlet?->id);
        } elseif ($isPelatih) {
            $caborId = $user->coach?->cabor_id;
            $pembinaanQuery->whereHas('atlet', function ($q) use ($caborId) {
                $q->where('cabor_id', $caborId);
            });
        }

        $totalPembinaan = (clone $pembinaanQuery)->count();

        // Distribusi Intensitas
        $distribusiIntensitas = (clone $pembinaanQuery)
            ->select('intensitas_latihan', DB::raw('count(*) as count'))
            ->groupBy('intensitas_latihan')
            ->get()->map(function ($item) {
                return ['name' => ucfirst($item->intensitas_latihan), 'count' => $item->count];
            })->values();

        // Distribusi Periodesasi
        $distribusiPeriodesasi = (clone $pembinaanQuery)
            ->select('periodesasi_latihan', DB::raw('count(*) as count'))
            ->groupBy('periodesasi_latihan')
            ->get()->map(function ($item) {
                return ['name' => ucfirst($item->periodesasi_latihan), 'count' => $item->count];
            })->values();

        // Rata-rata Skor per Cabor (only if not restricted to one athlete, or just calculate from raw if general)
        // To keep it simple, we'll calculate it by fetching the details for the filtered programs
        $pembinaanIds = (clone $pembinaanQuery)->pluck('id');
        $rataSkorCabor = DB::table('pembinaan_prestasi_details')
            ->join('pembinaan_prestasis', 'pembinaan_prestasis.id', '=', 'pembinaan_prestasi_details.pembinaan_prestasi_id')
            ->join('atlets', 'atlets.id', '=', 'pembinaan_prestasis.atlet_id')
            ->join('cabors', 'cabors.id', '=', 'atlets.cabor_id')
            ->whereIn('pembinaan_prestasis.id', $pembinaanIds)
            ->whereNotNull('pembinaan_prestasi_details.score')
            ->select('cabors.name', DB::raw('round(avg(pembinaan_prestasi_details.score), 2) as avg_score'))
            ->groupBy('cabors.id', 'cabors.name')
            ->get()->map(function ($item) {
                return ['name' => $item->name, 'score' => (float)$item->avg_score];
            })->values();

        // Recent Pembinaan
        $recentPembinaan = (clone $pembinaanQuery)->latest()->limit(10)->get();

        // ===== TAB 4: TES PERFORMA STATS =====
        $tesPerformaQuery = PerformanceTest::where('is_active', 1);

        // Role filter for tes performa
        if ($isAtlet) {
            $tesPerformaQuery->where('atlet_id', $user->atlet?->id);
        } elseif ($isPelatih) {
            $caborId = $user->coach?->cabor_id;
            $tesPerformaQuery->where('cabor_id', $caborId);
        }

        $totalTesPerforma = (clone $tesPerformaQuery)->count();

        // Status kesehatan distribution
        $distribusiStatusKesehatan = (clone $tesPerformaQuery)
            ->select('status_kesehatan', DB::raw('count(*) as count'))
            ->groupBy('status_kesehatan')
            ->get()
            ->map(fn($i) => ['name' => ucfirst($i->status_kesehatan), 'count' => $i->count])
            ->values();

        $totalFit         = (clone $tesPerformaQuery)->where('status_kesehatan', 'fit')->count();
        $totalCidera      = (clone $tesPerformaQuery)->where('status_kesehatan', 'cidera')->count();
        $totalRehabilitasi = (clone $tesPerformaQuery)->where('status_kesehatan', 'rehabilitasi')->count();

        // Tes per Cabor
        $tesPerCabor = Cabor::where('is_active', 1)
            ->withCount(['performanceTests as tes_count' => function ($q) use ($isAtlet, $isPelatih, $user) {
                $q->where('is_active', 1);
                if ($isAtlet) {
                    $q->where('atlet_id', $user->atlet?->id);
                } elseif ($isPelatih) {
                    $q->where('cabor_id', $user->coach?->cabor_id);
                }
            }])
            ->get()
            ->map(fn($c) => ['name' => $c->name, 'count' => $c->tes_count])
            ->filter(fn($c) => $c['count'] > 0)
            ->values();

        // Recent 10 tes performa
        $recentTesPerforma = (clone $tesPerformaQuery)
            ->with(['atlet', 'cabor', 'jenisDisabilitas'])
            ->latest('tanggal_pelaksanaan')
            ->limit(10)
            ->get();

        $title = 'Dashboard';
        $breadcrum = ['Dashboard', 'Statistik Overview'];

        return view('dashboard', compact(
            'totalCabor',
            'totalCoach',
            'totalMedis',
            'totalAtlet',
            'totalKlasifikasi',
            'totalJenis',
            'totalTrainingType',
            'atletPerCabor',
            'atletPerKlasifikasi',
            'atletLaki',
            'atletWanita',
            'coachPerCabor',
            'medisByType',
            'trainingTypePerCabor',
            'medalStats',
            'recentHealth',
            'recentMonitoring',
            'totalPembinaan',
            'distribusiIntensitas',
            'distribusiPeriodesasi',
            'rataSkorCabor',
            'recentPembinaan',
            'totalTesPerforma',
            'distribusiStatusKesehatan',
            'totalFit',
            'totalCidera',
            'totalRehabilitasi',
            'tesPerCabor',
            'recentTesPerforma',
            'title',
            'breadcrum'
        ));
    }

    public function getMedalDetails($caborId)
    {
        $details = Kompetisi::with(['atlet'])
            ->where('cabor_id', $caborId)
            ->whereIn('hasil_medali', ['emas', 'perak', 'perunggu'])
            ->orderBy('waktu_pelaksanaan', 'desc')
            ->get()
            ->map(function ($k) {
                return [
                    'atlet_name' => $k->atlet?->name ?? 'N/A',
                    'nama_kompetisi' => $k->nama_kompetisi,
                    'medali' => $k->hasil_medali,
                    'tahun' => date('Y', strtotime($k->waktu_pelaksanaan)),
                    'tingkatan' => $k->tingkatan
                ];
            });

        $cabor = Cabor::find($caborId);

        return response()->json([
            'cabor_name' => $cabor?->name ?? 'Unknown',
            'details' => $details
        ]);
    }

    public function getPerformanceTestDetails($id)
    {
        $test = PerformanceTest::with(['atlet', 'cabor', 'jenisDisabilitas', 'klasifikasiDisabilitas'])
            ->findOrFail($id);

        $results = DB::table('performance_test_results')
            ->join('physical_test_items', 'performance_test_results.physical_test_item_id', '=', 'physical_test_items.id')
            ->join('physical_test_categories', 'physical_test_items.physical_test_category_id', '=', 'physical_test_categories.id')
            ->leftJoin('physical_test_item_scores', 'performance_test_results.physical_test_item_score_id', '=', 'physical_test_item_scores.id')
            ->where('performance_test_results.performance_test_id', $id)
            ->where('performance_test_results.is_active', 1)
            ->select(
                'physical_test_items.name as item_name',
                'physical_test_items.satuan',
                'performance_test_results.nilai',
                'physical_test_categories.name as category_name',
                'physical_test_item_scores.label as score_label',
                'physical_test_item_scores.score as numeric_score'
            )
            ->get();

        return response()->json([
            'test' => [
                'tanggal' => $test->tanggal_pelaksanaan?->format('d M Y'),
                'atlet_name' => $test->atlet?->name,
                'cabor_name' => $test->cabor?->name,
                'status_kesehatan' => ucfirst($test->status_kesehatan),
                'spesialisasi' => $test->spesialisasi,
                'penguji' => $test->penguji,
                'alat_bantu' => $test->alat_bantu,
                'disabilitas' => $test->jenisDisabilitas?->nama_jenis ?? 'Umum',
                'klasifikasi' => $test->klasifikasiDisabilitas?->kode_klasifikasi ?? '-'
            ],
            'results' => $results
        ]);
    }
}
