@extends('layouts.auth')

@section('content')
<div class="card shadow-lg" style="max-width: 450px; width: 100%; background: rgba(255, 255, 255, 0.98); border-radius: 16px;">
    <div class="card-body p-8">
        <div class="text-center mb-8">
            <div class="mb-4">
                <i class="ki-outline ki-lock-2 text-primary" style="font-size: 48px; color: #FFB800;"></i>
            </div>
            <h3 class="text-dark fw-bold fs-3">Reset Password</h3>
            <p class="text-muted fs-6">Masukkan password baru Anda</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="form fv-plugins-bootstrap5 fv-plugins-framework">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

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
                        value="{{ old('email', $request->email) }}" 
                        required 
                        readonly
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-5">
                <label class="form-label fs-6 fw-semibold text-dark">Password Baru</label>
                <div class="input-group" data-kt-password-meter="true">
                    <span class="input-group-text bg-light border-0">
                        <i class="ki-outline ki-lock-2 fs-4 text-muted"></i>
                    </span>
                    <input 
                        type="password" 
                        name="password" 
                        class="form-control bg-light border-0 @error('password') is-invalid @enderror" 
                        placeholder="Masukkan password baru"
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
                        placeholder="Konfirmasi password baru"
                        required
                    >
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn npci-gradient btn-lg fw-bold text-dark">
                    <span class="indicator-label">Reset Password</span>
                </button>
            </div>
        </form>

        <div class="text-center mt-8">
            <p class="text-muted fs-6">
                <a href="{{ route('login') }}" class="link-primary fw-semibold">Kembali ke Login</a>
            </p>
        </div>
    </div>
</div>

<style>
    .npci-gradient {
        background: linear-gradient(135deg, #FFB800 0%, #FF8C00 100%) !important;
    }
    .link-primary {
        color: #FFB800 !important;
    }
    .link-primary:hover {
        color: #FF8C00 !important;
    }
</style>
@endsection
