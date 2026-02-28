@extends('layouts.auth')

@section('content')
<div class="card shadow-lg" style="max-width: 450px; width: 100%; background: rgba(255, 255, 255, 0.98); border-radius: 16px;">
    <div class="card-body p-8">
        <div class="text-center mb-8">
            <div class="mb-4">
                <i class="ki-outline ki-shield-tick" style="font-size: 48px; color: #FFB800;"></i>
            </div>
            <h3 class="text-dark fw-bold fs-3">Two-Factor Authentication</h3>
            <p class="text-muted fs-6">Masukkan kode autentikasi dari aplikasi Anda</p>
        </div>

        <form method="POST" action="{{ route('two-factor.login.store') }}" class="form fv-plugins-bootstrap5 fv-plugins-framework">
            @csrf

            <div class="mb-6">
                <label class="form-label fs-6 fw-semibold text-dark">Kode Verifikasi</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0">
                        <i class="ki-outline ki-lock-2 fs-4 text-muted"></i>
                    </span>
                    <input 
                        type="text" 
                        name="code" 
                        class="form-control bg-light border-0 @error('code') is-invalid @enderror" 
                        placeholder="Masukkan kode 6 digit"
                        required
                        autofocus
                        autocomplete="one-time-code"
                    >
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn npci-gradient btn-lg fw-bold text-dark">
                    <span class="indicator-label">Verifikasi</span>
                </button>
            </div>
        </form>

        <div class="text-center mt-8">
            <form method="POST" action="{{ route('two-factor.login.store') }}">
                @csrf
                <button type="submit" class="btn btn-link">
                    Atau gunakan recovery code
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    .npci-gradient {
        background: linear-gradient(135deg, #FFB800 0%, #FF8C00 100%) !important;
    }
</style>
@endsection
