@extends('layouts.auth')

@section('content')
<div class="card shadow-lg" style="max-width: 450px; width: 100%; background: rgba(255, 255, 255, 0.98); border-radius: 16px;">
    <div class="card-body p-8">
        <div class="text-center mb-8">
            <div class="mb-4">
                <i class="ki-outline ki-shield-tick" style="font-size: 48px; color: #FFB800;"></i>
            </div>
            <h3 class="text-dark fw-bold fs-3">Konfirmasi Password</h3>
            <p class="text-muted fs-6">Silakan konfirmasi password Anda untuk melanjutkan</p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="form fv-plugins-bootstrap5 fv-plugins-framework">
            @csrf

            <div class="mb-6">
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
                        autofocus
                    >
                    <span class="input-group-text bg-light border-0">
                        <i class="ki-outline ki-eye-slash fs-4 toggle-password" style="cursor: pointer;"></i>
                    </span>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn npci-gradient btn-lg fw-bold text-dark">
                    <span class="indicator-label">Konfirmasi</span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .npci-gradient {
        background: linear-gradient(135deg, #FFB800 0%, #FF8C00 100%) !important;
    }
</style>
@endsection
