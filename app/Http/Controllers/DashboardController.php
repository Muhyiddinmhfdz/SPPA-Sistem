<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cabor;
use App\Models\Coach;
use App\Models\Medis;
use App\Models\Atlet;
use App\Models\KlasifikasiDisabilitas;
use App\Models\JenisDisabilitas;

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

        $title = 'Dashboard';
        $breadcrum = ['Dashboard', 'Statistik Overview'];

        return view('dashboard', compact(
            'totalCabor',
            'totalCoach',
            'totalMedis',
            'totalAtlet',
            'totalKlasifikasi',
            'totalJenis',
            'atletPerCabor',
            'atletPerKlasifikasi',
            'atletLaki',
            'atletWanita',
            'coachPerCabor',
            'medisByType',
            'title',
            'breadcrum'
        ));
    }
}
