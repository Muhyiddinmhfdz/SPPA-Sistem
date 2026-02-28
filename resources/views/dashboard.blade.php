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
</style>

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
    {{-- Jenis --}}
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body p-5">
                <div class="stat-icon mb-4" style="background-color: #e6f9f0;">
                    <i class="ki-duotone ki-abstract-26 fs-2" style="color:#059669;">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                </div>
                <div class="fs-2hx fw-bold text-gray-900">{{ $totalJenis }}</div>
                <div class="text-muted fs-7 fw-semibold mt-1">Jenis Disabilitas</div>
                <a href="{{ route('master.jenis-disabilitas.index') }}" class="stretched-link"></a>
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
<div class="row g-4">
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
    var coachPerCabor = @json($coachPerCabor);
    var medisByType = @json($medisByType);
    var atletLaki = @json($atletLaki);
    var atletWanita = @json($atletWanita);

    var primaryColor = '#009ef7';
    var successColor = '#50cd89';
    var warningColor = '#ffc700';
    var dangerColor = '#f1416c';
    var purpleColor = '#7c3aed';
    var tealColor = '#059669';

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
</script>
@endsection