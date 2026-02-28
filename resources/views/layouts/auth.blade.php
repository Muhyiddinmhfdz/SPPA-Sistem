<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Login' }} - SPPA Sistem</title>
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.ico') }}">
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #151521;
        }

        .bg-auth-side {
            background-image: url('{{ asset("assets/media/auth/bg3.jpg") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
        }

        .bg-auth-side::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(21, 21, 33, 0.95) 0%, rgba(21, 21, 33, 0.75) 100%);
        }

        .npci-gradient {
            background: linear-gradient(135deg, #FFB800 0%, #FF8C00 100%) !important;
            border: none !important;
        }

        .npci-gradient:hover {
            background: linear-gradient(135deg, #FFC107 0%, #FFA000 100%) !important;
            box-shadow: 0 4px 15px rgba(255, 184, 0, 0.4);
            transform: translateY(-1px);
        }

        .npci-text-gradient {
            background: linear-gradient(135deg, #FFB800 0%, #FFD54F 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-3px);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>

<body id="kt_body" class="auth-bg">
    <div class="d-flex flex-column flex-root h-100" style="min-height: 100vh;">
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <!-- Left Side: Login Form -->
            <div class="d-flex flex-column flex-lg-row-fluid flex-center w-lg-50 px-10 py-10" style="background-color: #1e1e2d; position: relative; z-index: 1;">

                <div class="d-flex flex-column flex-center text-center mb-8">
                    <!-- <a href="#" class="mb-4 d-inline-block">
                        <img alt="Logo" src="{{ asset('assets/media/logos/default.svg') }}" class="h-60px h-lg-80px" style="filter: brightness(0) invert(1) drop-shadow(0 2px 5px rgba(0,0,0,0.3));">
                    </a> -->
                    <h1 class="text-white fw-bold fs-2x mb-1 mt-3" style="letter-spacing: 0.5px;">SISTEM PEMBINAAN PRESTASI</h1>
                    <div class="npci-text-gradient fs-5 fw-semibold">
                        NATIONAL PARALYMPIC COMMITTEE INDONESIA
                    </div>
                </div>

                @yield('content')

                <div class="d-flex flex-center flex-wrap fs-6 text-muted mt-10">
                    <span class="text-gray-500 hover-primary">&copy; {{ date('Y') }} NPCI. Hak Cipta Dilindungi.</span>
                </div>
            </div>

            <!-- Right Side: Info Panel -->
            <div class="d-none d-lg-flex flex-lg-row-fluid w-50 bg-auth-side order-1 order-lg-2">
                <div class="d-flex flex-column justify-content-center align-items-center w-100 h-100 p-15" style="position: relative; z-index: 2;">

                    <div class="text-center mb-12">
                        <span class="badge badge-light-warning fw-bold px-4 py-2 mb-4 fs-7 rounded-pill border border-warning border-opacity-25" style="background: rgba(255, 184, 0, 0.1);">Sistem Manajemen Terpadu</span>
                        <h2 class="text-white fs-2tx fw-bolder mb-4 lh-sm">
                            Mengembangkan Potensi<br>
                            <span class="npci-text-gradient">Atlet Disabilitas Indonesia</span>
                        </h2>
                        <p class="text-gray-400 fs-5 fw-medium" style="max-width: 500px; margin: 0 auto;">
                            Portal informasi dan manajemen komprehensif untuk mendata, memantau, dan menghitung analisis performa atlet.
                        </p>
                    </div>

                    <div class="row w-100" style="max-width: 550px;">
                        <div class="col-sm-6 mb-5">
                            <div class="glass-card d-flex align-items-center p-5 h-100">
                                <div class="symbol symbol-40px me-4">
                                    <div class="symbol-label bg-light-warning">
                                        <i class="ki-outline ki-chart-line-star text-warning fs-2x"></i>
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-white fw-bold fs-5 mb-1">Pemantauan</span>
                                    <span class="text-gray-500 fs-7">Analisis data performa</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-5">
                            <div class="glass-card d-flex align-items-center p-5 h-100">
                                <div class="symbol symbol-40px me-4">
                                    <div class="symbol-label bg-light-primary">
                                        <i class="ki-outline ki-profile-user text-primary fs-2x"></i>
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-white fw-bold fs-5 mb-1">Database Atlet</span>
                                    <span class="text-gray-500 fs-7">Manajemen data terpusat</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-5 mb-sm-0">
                            <div class="glass-card d-flex align-items-center p-5 h-100">
                                <div class="symbol symbol-40px me-4">
                                    <div class="symbol-label bg-light-success">
                                        <i class="ki-outline ki-calendar-8 text-success fs-2x"></i>
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-white fw-bold fs-5 mb-1">Manajemen Event</span>
                                    <span class="text-gray-500 fs-7">Pendaftaran & jadwal</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="glass-card d-flex align-items-center p-5 h-100">
                                <div class="symbol symbol-40px me-4">
                                    <div class="symbol-label bg-light-danger">
                                        <i class="ki-outline ki-medal-star text-danger fs-2x"></i>
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-white fw-bold fs-5 mb-1">Capaian Prestasi</span>
                                    <span class="text-gray-500 fs-7">Dokumentasi hasil</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    @yield('scripts')
</body>

</html>