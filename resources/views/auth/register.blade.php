@extends('layouts.auth')

@section('content')
<div class="card shadow-lg" style="max-width: 500px; width: 100%; background: rgba(255, 255, 255, 0.98); border-radius: 16px;">
    <div class="card-body p-8">
        <div class="text-center mb-8">
            <h3 class="text-dark fw-bold fs-3">Buat Akun Baru</h3>
            <p class="text-muted fs-6">Daftar untuk mengakses sistem NPCI</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="form fv-plugins-bootstrap5 fv-plugins-framework">
            @csrf

            <div class="mb-5">
                <label class="form-label fs-6 fw-semibold text-dark">Nama Lengkap</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0">
                        <i class="ki-outline ki-user fs-4 text-muted"></i>
                    </span>
                    <input 
                        type="text" 
                        name="name" 
                        class="form-control bg-light border-0 @error('name') is-invalid @enderror" 
                        placeholder="Masukkan nama lengkap"
                        value="{{ old('name') }}" 
                        required 
                        autofocus
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-5">
                <label class="form-label fs-6 fw-semibold text-dark">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0">
                        <i class="ki-outline ki-profile-user fs-4 text-muted"></i>
                    </span>
                    <input 
                        type="text" 
                        name="username" 
                        class="form-control bg-light border-0 @error('username') is-invalid @enderror" 
                        placeholder="username"
                        value="{{ old('username') }}" 
                        required
                    >
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-5">
                <label class="form-label fs-6 fw-semibold text-dark">Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0">
                        <i class="ki-outline ki-message-text fs-4 text-muted"></i>
                    </span>
                    <input 
                        type="email" 
                        name="email" 
                        class="form-control bg-light border-0 @error('email') is-invalid @enderror" 
                        placeholder="email@npci.or.id"
                        value="{{ old('email') }}" 
                        required
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-5">
                <label class="form-label fs-6 fw-semibold text-dark">Password</label>
                <div class="input-group" data-kt-password-meter="true">
                    <span class="input-group-text bg-light border-0">
                        <i class="ki-outline ki-lock-2 fs-4 text-muted"></i>
                    </span>
                    <input 
                        type="password" 
                        name="password" 
                        class="form-control bg-light border-0 @error('password') is-invalid @enderror" 
                        placeholder="Masukkan password"
                        required
                    >
                    <span class="input-group-text bg-light border-0">
                        <i class="ki-outline ki-eye-slash fs-4 toggle-password" style="cursor: pointer;"></i>
                    </span>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label class="form-label fs-6 fw-semibold text-dark">Konfirmasi Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0">
                        <i class="ki-outline ki-lock-2 fs-4 text-muted"></i>
                    </span>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        class="form-control bg-light border-0" 
                        placeholder="Konfirmasi password"
                        required
                    >
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn npci-gradient btn-lg fw-bold text-dark">
                    <span class="indicator-label">Daftar Sekarang</span>
                </button>
            </div>
        </form>

        <div class="text-center mt-8">
            <p class="text-muted fs-6">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="link-primary fw-semibold">Masuk</a>
            </p>
        </div>
    </div>
</div>

<style>
    .npci-gradient {
        background: linear-gradient(135deg, #FFB800 0%, #FF8C00 100%) !important;
    }
    .npci-gradient:hover {
        background: linear-gradient(135deg, #FFC107 0%, #FFA000 100%) !important;
    }
    .link-primary {
        color: #FFB800 !important;
    }
    .link-primary:hover {
        color: #FF8C00 !important;
    }
</style>
@endsection
