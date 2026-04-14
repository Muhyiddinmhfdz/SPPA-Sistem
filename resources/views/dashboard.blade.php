@extends('layouts.main_layout')

@section('content')
<style>
    .stat-card {
        border-radius: 14px;
        border: none;
        transition: transform 0.25s, box-shadow 0.25s;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12) !important;
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chart-card {
        border-radius: 14px;
        border: none;
    }

    .activity-card {
        border-radius: 12px;
        border: 1px solid #f1f1f2;
        transition: all 0.2s;
        margin-bottom: 12px;
    }

    .activity-card:hover {
        background-color: #f9f9f9;
        border-color: #d1d3e0;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .section-title {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #a1a5b7;
        margin-bottom: 1rem;
    }

    .quick-link {
        border-radius: 10px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.875rem;
        color: #3f4254;
        background: #fff;
        border: 1px solid #f1f1f2;
        transition: all 0.2s;
    }

    .quick-link:hover {
        background: #f1f8ff;
        border-color: #009ef7;
        color: #009ef7;
    }

    .quick-link .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    /* Style tambahan untuk Detail Tes Performa (mengikuti style asli di tes-performa) */
    #perf_results_body .tp-category-header {
        background: linear-gradient(90deg, #1a1a2e 0%, #16213e 100%);
        color: #fff;
        border-radius: 10px;
        padding: .6rem 1rem;
        font-weight: 700;
        font-size: 13px;
        margin-bottom: .5rem;
        display: flex;
        align-items: center;
        gap: .75rem;
    }
    #perf_results_body .tp-item-row {
        display: flex;
        align-items: center;
        gap: .75rem;
        padding: .55rem .75rem;
        border-radius: 8px;
        border: 1px solid #f0f0f8;
        background: #fff;
        margin-bottom: .4rem;
    }
    #perf_results_body .tp-item-row:hover {
        border-color: #cdd9ef;
        background: #fafbff;
    }
    #perf_results_body .tp-item-name {
        flex: 1;
        font-size: 13px;
        font-weight: 600;
        color: #3f4254;
    }
    .tp-score-pill {
        border-radius: 20px;
        padding: 3px 10px;
        font-size: 11px;
        font-weight: 600;
        min-width: 100px;
        text-align: center;
    }
    .tp-score-4 { background: #e8fff3; color: #1bc5bd; border: 1px solid #b5edd8; }
    .tp-score-3 { background: #eef1ff; color: #6078f4; border: 1px solid #c8d0fb; }
    .tp-score-2 { background: #fff8dd; color: #f6a600; border: 1px solid #f6d782; }
    .tp-score-1 { background: #fff5f5; color: #f1416c; border: 1px solid #f9b3c5; }
    .tp-score-na { background: #f5f5f5; color: #a1a5b7; border: 1px solid #e4e6ef; }
</style>

<div class="card shadow-sm mb-6" style="border-radius: 14px;">
    <div class="card-header border-0 pt-5 pr-5">
        <ul class="nav nav-line-tabs nav-line-tabs-2x border-transparent fw-bold fs-6">
            <li class="nav-item">
                <a class="nav-link active text-active-primary d-flex align-items-center pb-5" data-bs-toggle="tab" href="#kt_dashboard_statistik">
                    <i class="ki-duotone ki-chart-line-star fs-2 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    Statistik Overview
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary d-flex align-items-center pb-5" data-bs-toggle="tab" href="#kt_dashboard_monitoring">
                    <i class="ki-duotone ki-notification-on fs-2 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                    Aktifitas Monitoring
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary d-flex align-items-center pb-5" data-bs-toggle="tab" href="#kt_dashboard_pembinaan">
                    <i class="ki-duotone ki-chart-line-star fs-2 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    Statistik Program Latihan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-primary d-flex align-items-center pb-5" data-bs-toggle="tab" href="#kt_dashboard_tes_performa">
                    <i class="ki-duotone ki-pulse fs-2 me-2"><span class="path1"></span><span class="path2"></span></i>
                    Statistik Tes Performa
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="tab-content" id="myTabContent">
    {{-- ===== TAB 1: STATISTIK ===== --}}
    <div class="tab-pane fade show active" id="kt_dashboard_statistik" role="tabpanel">
        {{-- ===== ROW 1: STAT CARDS ===== --}}
        <div class="row g-4 mb-6">
            {{-- Cabor --}}
            <div class="col-xl-2 col-md-4 col-6">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body p-5">
                        <div class="stat-icon bg-light-primary mb-4">
                            <i class="ki-duotone ki-basketball fs-2 text-primary">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
                            </i>
                        </div>
                        <div class="fs-2hx fw-bold text-gray-900">{{ $totalCabor }}</div>
                        <div class="text-muted fs-7 fw-semibold mt-1">Cabang Olahraga</div>
                        <a href="{{ route('master.cabor.index') }}" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            {{-- Atlet --}}
            <div class="col-xl-2 col-md-4 col-6">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body p-5">
                        <div class="stat-icon bg-light-success mb-4">
                            <i class="ki-duotone ki-people fs-2 text-success">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
                            </i>
                        </div>
                        <div class="fs-2hx fw-bold text-gray-900">{{ $totalAtlet }}</div>
                        <div class="text-muted fs-7 fw-semibold mt-1">Total Atlet</div>
                        <a href="{{ route('master.atlet.index') }}" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            {{-- Coach --}}
            <div class="col-xl-2 col-md-4 col-6">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body p-5">
                        <div class="stat-icon mb-4" style="background-color: #fff8dd;">
                            <i class="ki-duotone ki-teacher fs-2 text-warning">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                            </i>
                        </div>
                        <div class="fs-2hx fw-bold text-gray-900">{{ $totalCoach }}</div>
                        <div class="text-muted fs-7 fw-semibold mt-1">Total Pelatih</div>
                        <a href="{{ route('master.coach.index') }}" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            {{-- Medis --}}
            <div class="col-xl-2 col-md-4 col-6">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body p-5">
                        <div class="stat-icon mb-4" style="background-color: #ffe2e5;">
                            <i class="ki-duotone ki-heart fs-2 text-danger">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                        </div>
                        <div class="fs-2hx fw-bold text-gray-900">{{ $totalMedis }}</div>
                        <div class="text-muted fs-7 fw-semibold mt-1">Total Tenaga Medis</div>
                        <a href="{{ route('master.medis.index') }}" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            {{-- Klasifikasi --}}
            <div class="col-xl-2 col-md-4 col-6">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body p-5">
                        <div class="stat-icon mb-4" style="background-color: #f3e8ff;">
                            <i class="ki-duotone ki-category fs-2" style="color:#7c3aed;">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                            </i>
                        </div>
                        <div class="fs-2hx fw-bold text-gray-900">{{ $totalKlasifikasi }}</div>
                        <div class="text-muted fs-7 fw-semibold mt-1">Klasifikasi Disabilitas</div>
                        <a href="{{ route('master.klasifikasi-disabilitas.index') }}" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            {{-- Jenis Latihan --}}
            <div class="col-xl-2 col-md-4 col-6">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body p-5">
                        <div class="stat-icon mb-4" style="background-color: #e0f2fe;">
                            <i class="ki-duotone ki-chart-line-star fs-2 text-info">
                                <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                            </i>
                        </div>
                        <div class="fs-2hx fw-bold text-gray-900">{{ $totalTrainingType }}</div>
                        <div class="text-muted fs-7 fw-semibold mt-1">Jenis Latihan</div>
                        <a href="{{ route('master.training-type.index') }}" class="stretched-link"></a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== ROW 2: CHARTS ===== --}}
        <div class="row g-4 mb-6">
            {{-- Atlet per Cabor --}}
            <div class="col-xl-8">
                <div class="card chart-card shadow-sm h-100">
                    <div class="card-header border-0 pt-5 pb-0">
                        <div>
                            <h3 class="card-title fw-bold text-gray-900 fs-4">Jumlah Atlet per Cabang Olahraga</h3>
                            <p class="text-muted fs-7 mb-0">Sebaran atlet aktif berdasarkan cabor</p>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <div id="chart_atlet_per_cabor" style="min-height: 320px;"></div>
                    </div>
                </div>
            </div>
            {{-- Gender Pie + Medis Donut --}}
            <div class="col-xl-4">
                <div class="card chart-card shadow-sm mb-4">
                    <div class="card-header border-0 pt-5 pb-0">
                        <h3 class="card-title fw-bold text-gray-900 fs-5">Gender Atlet</h3>
                    </div>
                    <div class="card-body pt-2">
                        <div id="chart_gender" style="min-height: 190px;"></div>
                    </div>
                </div>
                <div class="card chart-card shadow-sm">
                    <div class="card-header border-0 pt-5 pb-0">
                        <h3 class="card-title fw-bold text-gray-900 fs-5">Tenaga Medis</h3>
                    </div>
                    <div class="card-body pt-2">
                        <div id="chart_medis" style="min-height: 190px;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== ROW 3: Coach per Cabor + Atlet per Klasifikasi + Quick Links ===== --}}
        <div class="row g-4 mb-10">
            {{-- Coach per Cabor --}}
            <div class="col-xl-5">
                <div class="card chart-card shadow-sm h-100">
                    <div class="card-header border-0 pt-5 pb-0">
                        <div>
                            <h3 class="card-title fw-bold text-gray-900 fs-4">Pelatih per Cabang Olahraga</h3>
                            <p class="text-muted fs-7 mb-0">Jumlah pelatih aktif per cabor</p>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <div id="chart_coach_per_cabor" style="min-height: 280px;"></div>
                    </div>
                </div>
            </div>
            {{-- Atlet per Klasifikasi --}}
            <div class="col-xl-4">
                <div class="card chart-card shadow-sm h-100">
                    <div class="card-header border-0 pt-5 pb-0">
                        <div>
                            <h3 class="card-title fw-bold text-gray-900 fs-4">Atlet per Klasifikasi</h3>
                            <p class="text-muted fs-7 mb-0">Distribusi atlet berdasarkan klasifikasi disabilitas</p>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <div id="chart_atlet_klasifikasi" style="min-height: 280px;"></div>
                    </div>
                </div>
            </div>
            {{-- Quick Links --}}
            <div class="col-xl-3">
                <div class="card chart-card shadow-sm h-100">
                    <div class="card-header border-0 pt-5 pb-2">
                        <h3 class="card-title fw-bold text-gray-900 fs-5">Akses Cepat</h3>
                    </div>
                    <div class="card-body pt-2 d-flex flex-column gap-3">
                        <a href="{{ route('master.cabor.index') }}" class="quick-link">
                            <span class="dot bg-primary"></span> Data Cabang Olahraga
                        </a>
                        <a href="{{ route('master.atlet.index') }}" class="quick-link">
                            <span class="dot bg-success"></span> Data Atlet
                        </a>
                        <a href="{{ route('master.coach.index') }}" class="quick-link">
                            <span class="dot bg-warning"></span> Data Pelatih
                        </a>
                        <a href="{{ route('master.medis.index') }}" class="quick-link">
                            <span class="dot bg-danger"></span> Data Medis
                        </a>
                        <a href="{{ route('master.klasifikasi-disabilitas.index') }}" class="quick-link">
                            <span class="dot" style="background:#7c3aed;"></span> Klasifikasi Disabilitas
                        </a>
                        <a href="{{ route('master.jenis-disabilitas.index') }}" class="quick-link">
                            <span class="dot" style="background:#059669;"></span> Jenis Disabilitas
                        </a>
                        <a href="{{ route('master.training-type.index') }}" class="quick-link">
                            <span class="dot" style="background:#009ef7;"></span> Jenis Latihan (Master)
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== ROW 4: Training Type per Cabor ===== --}}
        <div class="row g-4 mb-6">
            <div class="col-xl-12">
                <div class="card chart-card shadow-sm">
                    <div class="card-header border-0 pt-5 pb-0">
                        <div>
                            <h3 class="card-title fw-bold text-gray-900 fs-4">Jenis Latihan per Cabang Olahraga</h3>
                            <p class="text-muted fs-7 mb-0">Jumlah kategori latihan yang tersedia di setiap cabor</p>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <div id="chart_training_type_per_cabor" style="min-height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== ROW 5: Medal Monitoring per Cabor ===== --}}
        <div class="row g-4 mb-10">
            <div class="col-xl-12">
                <div class="card chart-card shadow-sm">
                    <div class="card-header border-0 pt-5 pb-0">
                        <div>
                            <h3 class="card-title fw-bold text-gray-900 fs-4">Monitoring Medali Kompetisi</h3>
                            <p class="text-muted fs-7 mb-0">Total perolehan medali (Emas, Perak, Perunggu) per cabang olahraga</p>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <div id="chart_medal_monitoring" style="min-height: 350px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== TAB 2: MONITORING ===== --}}
    <div class="tab-pane fade" id="kt_dashboard_monitoring" role="tabpanel">
        <div class="row g-6">
            {{-- Latihan Terbaru --}}
            <div class="col-lg-6">
                <div class="card chart-card shadow-sm">
                    <div class="card-header border-0 pt-5 pb-3">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-40px symbol-light-primary me-4">
                                <span class="symbol-label">
                                    <i class="ki-duotone ki-barbell fs-2 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span></i>
                                </span>
                            </div>
                            <div>
                                <h3 class="card-title fw-bold text-gray-900 fs-4 mb-0">Monitoring Latihan Terbaru</h3>
                                <p class="text-muted fs-7">10 Aktifitas latihan terakhir</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-2 pb-6">
                        @forelse($recentMonitoring as $m)
                        <div class="activity-card p-4">
                            <div class="d-flex align-items-center">
                                <div class="activity-icon bg-light-info me-4">
                                    <i class="ki-duotone ki-calendar-8 fs-3 text-info"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-gray-900 fw-bold fs-6">{{ $m->atlet?->name ?? $m->coach?->name ?? '-' }}</span>
                                        <span class="badge badge-light-primary fw-bold">{{ $m->tanggal->format('d M Y') }}</span>
                                    </div>
                                    <div class="text-muted fs-7 mt-1">
                                        {{ $m->cabor?->name }} • Durasi: <strong>{{ $m->durasi_latihan }} Menit</strong>
                                    </div>
                                    <div class="mt-2">
                                        @php
                                        $badgeBeban = match($m->beban_latihan) {
                                        'ringan' => 'success',
                                        'sedang' => 'warning',
                                        'berat' => 'danger',
                                        default => 'secondary'
                                        };
                                        @endphp
                                        <span class="badge badge-{{ $badgeBeban }} badge-sm me-1">Beban: {{ ucfirst($m->beban_latihan) }}</span>
                                        <span class="badge badge-light-{{ $m->kesimpulan == 'ya' ? 'success' : 'danger' }} badge-sm">Lanjut: {{ $m->kesimpulan == 'ya' ? 'Ya' : 'Tidak' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-10">
                            <img src="{{ asset('media/illustrations/sigma-1/5.png') }}" class="mw-150px mb-4 opacity-50">
                            <p class="text-muted fw-semibold">Belum ada aktifitas latihan.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Kesehatan Terbaru --}}
            <div class="col-lg-6">
                <div class="card chart-card shadow-sm">
                    <div class="card-header border-0 pt-5 pb-3">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-40px symbol-light-danger me-4">
                                <span class="symbol-label">
                                    <i class="ki-duotone ki-heart fs-2 text-danger"><span class="path1"></span><span class="path2"></span></i>
                                </span>
                            </div>
                            <div>
                                <h3 class="card-title fw-bold text-gray-900 fs-4 mb-0">Cek Kesehatan Terbaru</h3>
                                <p class="text-muted fs-7">10 Pemeriksaan kesehatan terakhir</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-2 pb-6">
                        @forelse($recentHealth as $h)
                        <div class="activity-card p-4">
                            <div class="d-flex align-items-center">
                                <div class="activity-icon bg-light-danger me-4">
                                    <i class="ki-duotone ki-flask fs-3 text-danger"><span class="path1"></span><span class="path2"></span></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-gray-900 fw-bold fs-6">{{ $h->atlet?->name ?? $h->coach?->name ?? '-' }}</span>
                                        <span class="badge badge-light-danger fw-bold">{{ $h->tanggal->format('d M Y') }}</span>
                                    </div>
                                    <div class="text-muted fs-7 mt-1">
                                        {{ $h->cabor?->name }} • Kondisi: <strong>{{ ucfirst($h->kondisi_harian) }}</strong>
                                    </div>
                                    <div class="mt-2">
                                        @php
                                        $badgeCedera = match($h->tingkat_cedera) {
                                        'tidak_cidera' => 'success',
                                        'ringan' => 'info',
                                        'sedang' => 'warning',
                                        'berat' => 'danger',
                                        default => 'secondary'
                                        };
                                        @endphp
                                        <span class="badge badge-light-{{ $badgeCedera }} badge-sm me-1">Cedera: {{ str_replace('_', ' ', ucfirst($h->tingkat_cedera)) }}</span>
                                        <span class="badge badge-{{ $h->kesimpulan == 'baik' ? 'success' : ($h->kesimpulan == 'sedang' ? 'warning' : 'danger') }} badge-sm">Hasil: {{ ucfirst($h->kesimpulan) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-10">
                            <img src="{{ asset('media/illustrations/sigma-1/5.png') }}" class="mw-150px mb-4 opacity-50">
                            <p class="text-muted fw-semibold">Belum ada aktifitas kesehatan.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== TAB 3: PEMBINAAN PRESTASI ===== --}}
    <div class="tab-pane fade" id="kt_dashboard_pembinaan" role="tabpanel">
    <div class="row g-4 mb-6">
        {{-- Total Pembinaan --}}
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body p-5">
                    <div class="stat-icon bg-light-primary mb-4">
                        <i class="ki-duotone ki-document fs-2 text-primary">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                    </div>
                    <div class="fs-2hx fw-bold text-gray-900">{{ $totalPembinaan }}</div>
                    <div class="text-muted fs-7 fw-semibold mt-1">Total Program Latihan</div>
                    <a href="{{ route('pembinaan-prestasi.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-md-6">
            <div class="card chart-card shadow-sm h-100">
                <div class="card-header border-0 pt-5 pb-0">
                    <h3 class="card-title fw-bold text-gray-900 fs-5">Rata-rata Skor per Cabor</h3>
                </div>
                <div class="card-body pt-2 pb-0">
                    <div id="chart_rata_skor" style="min-height: 250px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-6">
        <div class="col-xl-6">
            <div class="card chart-card shadow-sm h-100">
                <div class="card-header border-0 pt-5 pb-0">
                    <h3 class="card-title fw-bold text-gray-900 fs-5">Distribusi Intensitas Latihan</h3>
                </div>
                <div class="card-body pt-2 pb-0">
                    <div id="chart_intensitas" style="min-height: 250px;"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card chart-card shadow-sm h-100">
                <div class="card-header border-0 pt-5 pb-0">
                    <h3 class="card-title fw-bold text-gray-900 fs-5">Distribusi Periodesasi Latihan</h3>
                </div>
                <div class="card-body pt-2 pb-0">
                    <div id="chart_periodesasi" style="min-height: 250px;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 3: Recent Pembinaan --}}
    <div class="row g-4 mb-10">
        <div class="col-12">
            <div class="card chart-card shadow-sm">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-4 text-gray-900">Program Latihan Terbaru</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">10 program terakhir yang ditambahkan</span>
                    </h3>
                </div>
                <div class="card-body pt-3">
                    <div class="table-responsive">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fw-bold text-muted text-uppercase fs-7">
                                    <th class="min-w-150px">Tanggal</th>
                                    <th class="min-w-200px">Atlet</th>
                                    <th class="min-w-150px">Cabor</th>
                                    <th class="min-w-100px text-center">Intensitas</th>
                                    <th class="min-w-100px text-center">Periode</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPembinaan as $p)
                                <tr>
                                    <td>
                                        <span class="text-gray-900 fw-bold fs-6">{{ $p->tanggal ? $p->tanggal->format('d M Y') : '-' }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-900 fw-bold fs-6">{{ $p->atlet->name ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-gray-700 fw-semibold d-block fs-7">{{ $p->atlet->cabor->name ?? '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        @php
                                        $ik = $p->intensitas_latihan;
                                        $bg = 'light-primary';
                                        if ($ik == 'sedang') $bg = 'light-warning';
                                        if ($ik == 'berat') $bg = 'light-danger';
                                        @endphp
                                        <span class="badge badge-{{ $bg }} text-capitalize fw-bold">{{ $ik }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-light fw-bold text-capitalize">{{ $p->periodesasi_latihan }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">Belum ada program latihan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

    {{-- ===== TAB 4: TES PERFORMA ===== --}}
    <div class="tab-pane fade" id="kt_dashboard_tes_performa" role="tabpanel">
        {{-- Row 1: Stat Cards --}}
        <div class="row g-4 mb-6">
            {{-- Total Tes --}}
            <div class="col-xl-3 col-md-6 col-6">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body p-5">
                        <div class="stat-icon bg-light-primary mb-4">
                            <i class="ki-duotone ki-pulse fs-2 text-primary"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                        <div class="fs-2hx fw-bold text-gray-900">{{ $totalTesPerforma }}</div>
                        <div class="text-muted fs-7 fw-semibold mt-1">Total Tes Performa</div>
                        <a href="{{ route('tes-performa.index') }}" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            {{-- Fit --}}
            <div class="col-xl-3 col-md-6 col-6">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body p-5">
                        <div class="stat-icon bg-light-success mb-4">
                            <i class="ki-duotone ki-shield-tick fs-2 text-success"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                        <div class="fs-2hx fw-bold text-gray-900">{{ $totalFit }}</div>
                        <div class="text-muted fs-7 fw-semibold mt-1">Status Fit</div>
                    </div>
                </div>
            </div>
            {{-- Cidera --}}
            <div class="col-xl-3 col-md-6 col-6">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body p-5">
                        <div class="stat-icon bg-light-danger mb-4">
                            <i class="ki-duotone ki-bandage fs-2 text-danger"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                        <div class="fs-2hx fw-bold text-gray-900">{{ $totalCidera }}</div>
                        <div class="text-muted fs-7 fw-semibold mt-1">Status Cidera</div>
                    </div>
                </div>
            </div>
            {{-- Rehabilitasi --}}
            <div class="col-xl-3 col-md-6 col-6">
                <div class="card stat-card shadow-sm h-100">
                    <div class="card-body p-5">
                        <div class="stat-icon mb-4" style="background-color:#fff8dd;">
                            <i class="ki-duotone ki-heart-circle fs-2 text-warning"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                        <div class="fs-2hx fw-bold text-gray-900">{{ $totalRehabilitasi }}</div>
                        <div class="text-muted fs-7 fw-semibold mt-1">Rehabilitasi</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row 2: Charts --}}
        <div class="row g-4 mb-6">
            {{-- Bar Chart: Tes per Cabor --}}
            <div class="col-xl-8">
                <div class="card chart-card shadow-sm h-100">
                    <div class="card-header border-0 pt-5 pb-0">
                        <div>
                            <h3 class="card-title fw-bold text-gray-900 fs-4">Jumlah Tes per Cabang Olahraga</h3>
                            <p class="text-muted fs-7 mb-0">Distribusi pelaksanaan tes performa per cabor</p>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <div id="chart_tes_per_cabor" style="min-height: 300px;"></div>
                    </div>
                </div>
            </div>
            {{-- Donut: Status Kesehatan --}}
            <div class="col-xl-4">
                <div class="card chart-card shadow-sm h-100">
                    <div class="card-header border-0 pt-5 pb-0">
                        <h3 class="card-title fw-bold text-gray-900 fs-5">Distribusi Status Kesehatan</h3>
                    </div>
                    <div class="card-body pt-4 d-flex align-items-center">
                        <div id="chart_status_kesehatan" style="min-height: 280px; width:100%;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row 3: Tabel 10 tes terbaru --}}
        <div class="row g-4 mb-10">
            <div class="col-12">
                <div class="card chart-card shadow-sm">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-4 text-gray-900">Tes Performa Terbaru</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">10 tes terakhir yang dilaksanakan</span>
                        </h3>
                        <div class="card-toolbar">
                            <a href="{{ route('tes-performa.index') }}" class="btn btn-sm btn-light-primary fw-bold">
                                <i class="ki-duotone ki-plus fs-3"><span class="path1"></span><span class="path2"></span></i>
                                Lihat Semua
                            </a>
                        </div>
                    </div>
                    <div class="card-body pt-3">
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted text-uppercase fs-7">
                                        <th class="min-w-50px">#</th>
                                        <th class="min-w-150px">Tanggal</th>
                                        <th class="min-w-200px">Atlet</th>
                                        <th class="min-w-150px">Cabor</th>
                                        <th class="min-w-120px">Disabilitas</th>
                                        <th class="min-w-100px text-center">Status Kesehatan</th>
                                        <th class="min-w-50px text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentTesPerforma as $i => $t)
                                    <tr>
                                        <td><span class="text-muted fw-semibold">{{ $i + 1 }}</span></td>
                                        <td>
                                            <span class="text-gray-900 fw-bold fs-6">{{ $t->tanggal_pelaksanaan?->format('d M Y') ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-35px symbol-light-primary me-3">
                                                    <span class="symbol-label fw-bold text-primary fs-7">{{ strtoupper(substr($t->atlet?->name ?? '?', 0, 1)) }}</span>
                                                </div>
                                                <span class="text-gray-900 fw-bold fs-6">{{ $t->atlet?->name ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-gray-700 fw-semibold d-block fs-7">{{ $t->cabor?->name ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <span class="text-gray-700 fw-semibold fs-7">{{ $t->jenisDisabilitas?->nama_jenis ?? 'Semua' }}</span>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $sk = $t->status_kesehatan;
                                                $skBadge = match($sk) {
                                                    'fit' => 'badge-light-success',
                                                    'cidera' => 'badge-light-danger',
                                                    'rehabilitasi' => 'badge-light-warning',
                                                    default => 'badge-light'
                                                };
                                            @endphp
                                            <span class="badge {{ $skBadge }} fw-bold text-capitalize">{{ $sk }}</span>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm btn-view-performance-detail" data-id="{{ $t->id }}">
                                                <i class="ki-duotone ki-eye fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-8">
                                            <i class="ki-duotone ki-information-5 fs-2x text-muted mb-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                            <p class="mb-0">Belum ada data tes performa.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ===== MODAL: MEDAL DETAILS ===== --}}
<div class="modal fade" id="kt_modal_medal_details" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-800px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold" id="modal_medal_title">Detail Perolehan Medali</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4" id="table_medal_details">
                        <thead>
                            <tr class="fw-bold text-muted text-uppercase fs-7">
                                <th class="min-w-150px">Atlet</th>
                                <th class="min-w-200px">Kompetisi</th>
                                <th class="min-w-100px text-center">Medali</th>
                                <th class="min-w-80px text-end">Tahun</th>
                            </tr>
                        </thead>
                        <tbody id="medal_details_body">
                            {{-- Content filled via AJAX --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL: PERFORMANCE TEST DETAILS ===== --}}
<div class="modal fade" id="kt_modal_performance_test_details" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 860px;">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header border-0 px-8 pt-7 pb-4" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); border-radius: 16px 16px 0 0;">
                <div class="d-flex align-items-center gap-4">
                    <span class="bg-white bg-opacity-10 p-3 rounded-3">
                        <i class="ki-duotone ki-chart-simple-2 fs-1 text-white opacity-75"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                    </span>
                    <div>
                        <div class="text-white opacity-60 fs-8 fw-semibold text-uppercase">Hasil Tes Performa</div>
                        <h4 class="fw-bolder text-white mb-0" id="perf_atlet_name">-</h4>
                        <span class="text-white opacity-60 fs-8"><span id="perf_tanggal">-</span> · <span id="perf_cabor">-</span></span>
                    </div>
                </div>
                <div class="btn btn-sm btn-icon" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1 text-white opacity-75"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body px-8 py-7">
                <div id="perf_results_container">
                    <div class="row g-4 mb-5">
                        <div class="col-sm-4">
                            <div class="tp-section-label">Penguji</div>
                            <div class="fw-bold fs-7" id="perf_penguji">-</div>
                        </div>
                        <div class="col-sm-4">
                            <div class="tp-section-label">Status Kesehatan</div>
                            <div id="perf_status_badge_container"></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="tp-section-label">Alat Bantu</div>
                            <div class="fw-bold fs-7" id="perf_alat_bantu">-</div>
                        </div>
                        <div class="col-sm-4">
                            <div class="tp-section-label">Spesialisasi</div>
                            <div class="fw-bold fs-7" id="perf_spesialisasi">-</div>
                        </div>
                        <div class="col-sm-4">
                            <div class="tp-section-label">Jenis Disabilitas</div>
                            <div class="fw-bold fs-7" id="perf_disabilitas">-</div>
                        </div>
                        <div class="col-sm-4">
                            <div class="tp-section-label">Klasifikasi</div>
                            <div class="fw-bold fs-7" id="perf_klasifikasi">-</div>
                        </div>
                    </div>
                    
                    <div id="perf_results_body">
                        {{-- Content filled via AJAX --}}
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 px-8 pb-7 pt-0">
                <button class="btn btn-light fw-bold" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // ===== DATA FROM BLADE =====
    var atletPerCabor = @json($atletPerCabor);
    var atletPerKlasifikasi = @json($atletPerKlasifikasi);
    var trainingTypePerCabor = @json($trainingTypePerCabor);
    var coachPerCabor = @json($coachPerCabor);
    var medisByType = @json($medisByType);
    var atletLaki = @json($atletLaki);
    var atletWanita = @json($atletWanita);
    var medalStats = @json($medalStats);

    // ===== PEMBINAAN PRESTASI DATA =====
    var distribusiIntensitas = @json($distribusiIntensitas ?? []);
    var distribusiPeriodesasi = @json($distribusiPeriodesasi ?? []);
    var rataSkorCabor = @json($rataSkorCabor ?? []);

    var primaryColor = '#009ef7';
    var successColor = '#50cd89';
    var warningColor = '#ffc700'; // Emas
    var dangerColor = '#f1416c';
    var purpleColor = '#7c3aed';
    var tealColor = '#059669';
    var perakColor = '#94a3b8'; // Perak
    var perungguColor = '#a855f7'; // Perunggu

    // ===== 1. Atlet per Cabor (Horizontal Bar) =====
    var opts1 = {
        series: [{
            name: 'Atlet',
            data: atletPerCabor.map(d => d.count)
        }],
        chart: {
            type: 'bar',
            height: 320,
            toolbar: {
                show: false
            },
            fontFamily: 'inherit'
        },
        plotOptions: {
            bar: {
                horizontal: true,
                borderRadius: 6,
                distributed: true,
                barHeight: '65%'
            }
        },
        colors: [primaryColor, successColor, warningColor, dangerColor, purpleColor, tealColor, '#ff6b6b', '#48dbfb', '#ff9f43', '#10ac84'],
        dataLabels: {
            enabled: true,
            style: {
                fontSize: '12px',
                fontWeight: 700
            }
        },
        xaxis: {
            categories: atletPerCabor.map(d => d.name),
            labels: {
                style: {
                    colors: '#a1a5b7',
                    fontSize: '12px'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#5e6278',
                    fontSize: '12px'
                }
            }
        },
        grid: {
            borderColor: '#f1f1f2',
            strokeDashArray: 4
        },
        legend: {
            show: false
        },
        tooltip: {
            theme: 'dark'
        }
    };
    new ApexCharts(document.querySelector("#chart_atlet_per_cabor"), opts1).render();

    // ===== 2. Gender Pie =====
    var opts2 = {
        series: [atletLaki, atletWanita],
        chart: {
            type: 'donut',
            height: 190,
            fontFamily: 'inherit'
        },
        labels: ['Laki-laki', 'Perempuan'],
        colors: [primaryColor, dangerColor],
        dataLabels: {
            enabled: false
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '55%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
                            fontSize: '14px',
                            fontWeight: 700
                        }
                    }
                }
            }
        },
        legend: {
            position: 'bottom',
            fontSize: '12px',
            fontWeight: 600
        },
        tooltip: {
            theme: 'dark'
        }
    };
    new ApexCharts(document.querySelector("#chart_gender"), opts2).render();

    // ===== 3. Medis Donut =====
    var opts3 = {
        series: medisByType.map(d => d.count),
        chart: {
            type: 'donut',
            height: 190,
            fontFamily: 'inherit'
        },
        labels: medisByType.map(d => d.name),
        colors: [primaryColor, successColor, warningColor],
        dataLabels: {
            enabled: false
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '55%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
                            fontSize: '14px',
                            fontWeight: 700
                        }
                    }
                }
            }
        },
        legend: {
            position: 'bottom',
            fontSize: '12px',
            fontWeight: 600
        },
        tooltip: {
            theme: 'dark'
        }
    };
    new ApexCharts(document.querySelector("#chart_medis"), opts3).render();

    // ===== 4. Coach per Cabor (Vertical Bar) =====
    var opts4 = {
        series: [{
            name: 'Pelatih',
            data: coachPerCabor.map(d => d.count)
        }],
        chart: {
            type: 'bar',
            height: 280,
            toolbar: {
                show: false
            },
            fontFamily: 'inherit'
        },
        plotOptions: {
            bar: {
                borderRadius: 6,
                distributed: true,
                columnWidth: '60%'
            }
        },
        colors: [warningColor, dangerColor, primaryColor, successColor, purpleColor, tealColor, '#ff6b6b', '#48dbfb'],
        dataLabels: {
            enabled: true,
            style: {
                fontSize: '11px',
                fontWeight: 700
            }
        },
        xaxis: {
            categories: coachPerCabor.map(d => d.name),
            labels: {
                style: {
                    colors: '#a1a5b7',
                    fontSize: '11px'
                },
                rotate: -20
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#5e6278',
                    fontSize: '11px'
                }
            }
        },
        grid: {
            borderColor: '#f1f1f2',
            strokeDashArray: 4
        },
        legend: {
            show: false
        },
        tooltip: {
            theme: 'dark'
        }
    };
    new ApexCharts(document.querySelector("#chart_coach_per_cabor"), opts4).render();

    // ===== 5. Atlet per Klasifikasi (Radar/Bar) =====
    var opts5 = {
        series: [{
            name: 'Atlet',
            data: atletPerKlasifikasi.map(d => d.count)
        }],
        chart: {
            type: 'bar',
            height: 280,
            toolbar: {
                show: false
            },
            fontFamily: 'inherit'
        },
        plotOptions: {
            bar: {
                horizontal: true,
                borderRadius: 5,
                distributed: true,
                barHeight: '55%'
            }
        },
        colors: [purpleColor, tealColor, primaryColor, dangerColor, warningColor, successColor, '#ff6b6b', '#48dbfb', '#ff9f43', '#10ac84'],
        dataLabels: {
            enabled: true,
            style: {
                fontSize: '11px',
                fontWeight: 700
            }
        },
        xaxis: {
            categories: atletPerKlasifikasi.map(d => d.name),
            labels: {
                style: {
                    colors: '#a1a5b7',
                    fontSize: '11px'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#5e6278',
                    fontSize: '11px'
                }
            }
        },
        grid: {
            borderColor: '#f1f1f2',
            strokeDashArray: 4
        },
        legend: {
            show: false
        },
        tooltip: {
            theme: 'dark'
        }
    };
    new ApexCharts(document.querySelector("#chart_atlet_klasifikasi"), opts5).render();

    // ===== 6. Training Type per Cabor =====
    var opts6 = {
        series: [{
            name: 'Jenis Latihan',
            data: trainingTypePerCabor.map(d => d.count)
        }],
        chart: {
            type: 'bar',
            height: 300,
            toolbar: {
                show: false
            },
            fontFamily: 'inherit'
        },
        plotOptions: {
            bar: {
                borderRadius: 7,
                distributed: true,
                columnWidth: '50%'
            }
        },
        colors: [primaryColor, successColor, warningColor, dangerColor, purpleColor, tealColor, '#ff6b6b', '#48dbfb'],
        dataLabels: {
            enabled: true,
            style: {
                fontSize: '11px',
                fontWeight: 700
            }
        },
        xaxis: {
            categories: trainingTypePerCabor.map(d => d.name),
            labels: {
                style: {
                    colors: '#a1a5b7',
                    fontSize: '11px'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#5e6278',
                    fontSize: '11px'
                }
            }
        },
        grid: {
            borderColor: '#f1f1f2',
            strokeDashArray: 4
        },
        legend: {
            show: false
        },
        tooltip: {
            theme: 'dark'
        }
    };
    new ApexCharts(document.querySelector("#chart_training_type_per_cabor"), opts6).render();

    // ===== 7. Medal Monitoring (Grouped Bar) =====
    var opts7 = {
        series: [{
            name: 'Emas',
            data: medalStats.map(d => d.emas)
        }, {
            name: 'Perak',
            data: medalStats.map(d => d.perak)
        }, {
            name: 'Perunggu',
            data: medalStats.map(d => d.perunggu)
        }],
        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: false
            },
            fontFamily: 'inherit',
            events: {
                dataPointSelection: function(event, chartContext, config) {
                    var caborIndex = config.dataPointIndex;
                    var caborId = medalStats[caborIndex].id;
                    showMedalDetails(caborId);
                }
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                borderRadius: 4
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        colors: [warningColor, perakColor, perungguColor],
        xaxis: {
            categories: medalStats.map(d => d.name),
            labels: {
                style: {
                    colors: '#a1a5b7',
                    fontSize: '11px'
                }
            }
        },
        yaxis: {
            title: {
                text: 'Jumlah Medali',
                style: {
                    color: '#a1a5b7'
                }
            },
            labels: {
                style: {
                    colors: '#5e6278',
                    fontSize: '11px'
                }
            }
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            theme: 'dark',
            y: {
                formatter: function(val) {
                    return val + " Medali"
                }
            }
        },
        grid: {
            borderColor: '#f1f1f2',
            strokeDashArray: 4
        },
        legend: {
            position: 'top',
            horizontalAlign: 'center',
            fontSize: '12px',
            fontWeight: 600
        }
    };
    new ApexCharts(document.querySelector("#chart_medal_monitoring"), opts7).render();

    // ===== Function to show medal details modal =====
    function showMedalDetails(caborId) {
        var modal = new bootstrap.Modal(document.getElementById('kt_modal_medal_details'));
        var body = document.getElementById('medal_details_body');
        var title = document.getElementById('modal_medal_title');

        body.innerHTML = '<tr><td colspan="4" class="text-center py-10"><span class="spinner-border spinner-border-sm align-middle ms-2"></span> Loading...</td></tr>';
        modal.show();

        fetch(`/dashboard/medal-details/${caborId}`)
            .then(response => response.json())
            .then(data => {
                title.innerText = `Detail Perolehan Medali: ${data.cabor_name}`;
                body.innerHTML = '';

                if (data.details.length === 0) {
                    body.innerHTML = '<tr><td colspan="4" class="text-center py-10 text-muted">Tidak ada data medali untuk cabor ini.</td></tr>';
                    return;
                }

                data.details.forEach(item => {
                    var badgeClass = item.medali === 'emas' ? 'warning' : (item.medali === 'perak' ? 'secondary' : 'info');
                    var badgeColor = item.medali === 'emas' ? '#ffc700' : (item.medali === 'perak' ? '#94a3b8' : '#a855f7');

                    var row = `
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-35px symbol-circle me-3">
                                        <span class="symbol-label bg-light-primary text-primary fw-bold">${item.atlet_name.charAt(0)}</span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-900 fw-bold fs-6">${item.atlet_name}</span>
                                        <span class="text-muted fs-7">${item.tingkatan}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-gray-800 fw-semibold d-block fs-7">${item.nama_kompetisi}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge fw-bold px-4 py-3 text-uppercase" style="background-color: ${badgeColor}; color: #fff;">${item.medali}</span>
                            </td>
                            <td class="text-end">
                                <span class="text-gray-700 fw-bold d-block fs-7">${item.tahun}</span>
                            </td>
                        </tr>
                    `;
                    body.innerHTML += row;
                });
            })
            .catch(error => {
                console.error('Error:', error);
                body.innerHTML = '<tr><td colspan="4" class="text-center py-10 text-danger">Gagal memuat data.</td></tr>';
            });
    }

    // ===== PEMBINAAN PRESTASI CHARTS =====
    // 1. Rata-rata Skor per Cabor (Bar Chart)
    if (document.querySelector("#chart_rata_skor")) {
        var optsRataSkor = {
            series: [{
                name: 'Rata-rata Skor',
                data: rataSkorCabor.map(d => d.score)
            }],
            chart: {
                type: 'bar',
                height: 250,
                toolbar: {
                    show: false
                },
                fontFamily: 'inherit'
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    distributed: true,
                    columnWidth: '50%'
                }
            },
            colors: [primaryColor, successColor, warningColor, dangerColor, purpleColor, tealColor, perakColor, perungguColor],
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val;
                },
                style: {
                    fontSize: '11px',
                    fontWeight: 700
                }
            },
            xaxis: {
                categories: rataSkorCabor.map(d => d.name),
                labels: {
                    style: {
                        colors: '#a1a5b7',
                        fontSize: '11px'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#5e6278',
                        fontSize: '11px'
                    }
                }
            },
            grid: {
                borderColor: '#f1f1f2',
                strokeDashArray: 4
            },
            legend: {
                show: false
            },
            tooltip: {
                theme: 'dark'
            }
        };
        new ApexCharts(document.querySelector("#chart_rata_skor"), optsRataSkor).render();
    }

    // 2. Distribusi Intensitas (Donut)
    if (document.querySelector("#chart_intensitas")) {
        var optsIntensitas = {
            series: distribusiIntensitas.map(d => d.count),
            chart: {
                type: 'donut',
                height: 250,
                fontFamily: 'inherit'
            },
            labels: distribusiIntensitas.map(d => d.name),
            colors: [primaryColor, warningColor, dangerColor], // Ringan, Sedang, Berat
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total',
                                fontSize: '14px',
                                fontWeight: 700
                            }
                        }
                    }
                }
            },
            legend: {
                position: 'bottom',
                fontSize: '12px',
                fontWeight: 600
            },
            tooltip: {
                theme: 'dark'
            }
        };
        new ApexCharts(document.querySelector("#chart_intensitas"), optsIntensitas).render();
    }

    // 3. Distribusi Periodesasi (Donut)
    if (document.querySelector("#chart_periodesasi")) {
        var optsPeriodesasi = {
            series: distribusiPeriodesasi.map(d => d.count),
            chart: {
                type: 'donut',
                height: 250,
                fontFamily: 'inherit'
            },
            labels: distribusiPeriodesasi.map(d => d.name),
            colors: [successColor, purpleColor, tealColor],
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total',
                                fontSize: '14px',
                                fontWeight: 700
                            }
                        }
                    }
                }
            },
            legend: {
                position: 'bottom',
                fontSize: '12px',
                fontWeight: 600
            },
            tooltip: {
                theme: 'dark'
            }
        };
        new ApexCharts(document.querySelector("#chart_periodesasi"), optsPeriodesasi).render();
    }

    // ===== TES PERFORMA CHARTS =====
    var tesPerCabor = @json($tesPerCabor ?? []);
    var distribusiStatusKesehatan = @json($distribusiStatusKesehatan ?? []);

    // Chart: Tes per Cabor (Horizontal Bar)
    if (document.querySelector("#chart_tes_per_cabor")) {
        var optsTesPerCabor = {
            series: [{ name: 'Jumlah Tes', data: tesPerCabor.map(d => d.count) }],
            chart: { type: 'bar', height: 300, toolbar: { show: false }, fontFamily: 'inherit' },
            plotOptions: { bar: { horizontal: true, borderRadius: 6, distributed: true, barHeight: '60%' } },
            colors: [primaryColor, successColor, warningColor, dangerColor, purpleColor, tealColor, '#ff6b6b', '#48dbfb', '#ff9f43', '#10ac84'],
            dataLabels: { enabled: true, style: { fontSize: '12px', fontWeight: 700 } },
            xaxis: { categories: tesPerCabor.map(d => d.name), labels: { style: { colors: '#a1a5b7', fontSize: '12px' } } },
            yaxis: { labels: { style: { colors: '#5e6278', fontSize: '12px' } } },
            grid: { borderColor: '#f1f1f2', strokeDashArray: 4 },
            legend: { show: false },
            tooltip: { theme: 'dark' },
            noData: { text: 'Belum ada data tes', align: 'center', verticalAlign: 'middle', style: { fontSize: '14px', color: '#a1a5b7' } }
        };
        new ApexCharts(document.querySelector("#chart_tes_per_cabor"), optsTesPerCabor).render();
    }

    // Chart: Distribusi Status Kesehatan (Donut)
    if (document.querySelector("#chart_status_kesehatan")) {
        var colorStatusMap = { 'Fit': successColor, 'Cidera': dangerColor, 'Rehabilitasi': warningColor };
        var statusColors = distribusiStatusKesehatan.length > 0
            ? distribusiStatusKesehatan.map(d => colorStatusMap[d.name] || primaryColor)
            : [successColor, dangerColor, warningColor];

        var optsStatus = {
            series: distribusiStatusKesehatan.length > 0 ? distribusiStatusKesehatan.map(d => d.count) : [0],
            chart: { type: 'donut', height: 280, fontFamily: 'inherit' },
            labels: distribusiStatusKesehatan.length > 0 ? distribusiStatusKesehatan.map(d => d.name) : ['Belum ada data'],
            colors: statusColors,
            dataLabels: { enabled: false },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            total: { show: true, label: 'Total Tes', fontSize: '13px', fontWeight: 700 }
                        }
                    }
                }
            },
            legend: { position: 'bottom', fontSize: '12px', fontWeight: 600 },
            tooltip: { theme: 'dark' }
        };
        new ApexCharts(document.querySelector("#chart_status_kesehatan"), optsStatus).render();
    }

    // ===== PERFORMANCE TEST DETAIL MODAL LOGIC =====
    $('.btn-view-performance-detail').on('click', function() {
        var id = $(this).data('id');
        var btn = $(this);
        btn.addClass('disabled').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

        $.ajax({
            url: "{{ route('dashboard.performance-test-detail', ['id' => '__ID__']) }}".replace('__ID__', id),
            type: 'GET',
            success: function(res) {
                btn.removeClass('disabled').html('<i class="ki-duotone ki-eye fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>');
                
                // Fill Header Info
                $('#perf_atlet_name').text(res.test.atlet_name);
                $('#perf_tanggal').text(res.test.tanggal);
                $('#perf_cabor').text(res.test.cabor_name);
                $('#perf_penguji').text(res.test.penguji || '-');
                $('#perf_spesialisasi').text(res.test.spesialisasi || '-');
                $('#perf_disabilitas').text(res.test.disabilitas || 'Semua');
                $('#perf_klasifikasi').text(res.test.klasifikasi || '-');
                $('#perf_alat_bantu').text(res.test.alat_bantu || 'Tidak ada');

                var sc = { 'Fit': 'success', 'Cidera': 'danger', 'Rehabilitasi': 'warning' };
                var badgeCls = sc[res.test.status_kesehatan] || 'secondary';
                $('#perf_status_badge_container').html('<span class="badge badge-light-' + badgeCls + ' fw-bold">' + res.test.status_kesehatan + '</span>');

                // Group results by category
                var groups = {};
                (res.results || []).forEach(function(r) {
                    var catName = r.category_name || 'Lainnya';
                    if (!groups[catName]) groups[catName] = [];
                    groups[catName].push(r);
                });

                var html = '';
                var groupKeys = Object.keys(groups);
                if (groupKeys.length > 0) {
                    groupKeys.forEach(function(catName) {
                        html += '<div class="tp-category-header mb-2">' + catName + '</div>';
                        html += '<div class="mb-4">';
                        groups[catName].forEach(function(r) {
                            var sc_val = r.numeric_score;
                            var cls = 'tp-score-na';
                            if (sc_val >= 4) cls = 'tp-score-4';
                            else if (sc_val == 3) cls = 'tp-score-3';
                            else if (sc_val == 2) cls = 'tp-score-2';
                            else if (sc_val == 1) cls = 'tp-score-1';

                            html += '<div class="tp-item-row">';
                            html += '<div class="tp-item-name">' + r.item_name + '</div>';
                            html += '<div class="fw-bold fs-7 text-gray-700">' + (r.nilai !== null ? r.nilai : '-') + ' ' + (r.satuan || '') + '</div>';
                            html += '<span class="tp-score-pill ' + cls + ' min-w-100px text-center">' + (r.score_label ? r.score_label + ' (' + sc_val + ')' : '—') + '</span>';
                            html += '</div>';
                        });
                        html += '</div>';
                    });
                } else {
                    html = '<div class="text-center text-muted py-8">Detail hasil tidak ditemukan.</div>';
                }
                $('#perf_results_body').html(html);

                // Show Modal
                $('#kt_modal_performance_test_details').modal('show');
            },
            error: function() {
                btn.removeClass('disabled').html('<i class="ki-duotone ki-eye fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>');
                alert('Gagal mengambil data detail tes.');
            }
        });
    });
</script>
@endsection
