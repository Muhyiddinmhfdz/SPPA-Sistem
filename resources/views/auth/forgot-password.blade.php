@extends('layouts.auth')

@section('content')
<div class="card shadow-lg" style="max-width: 450px; width: 100%; background: rgba(255, 255, 255, 0.98); border-radius: 16px;">
    <div class="card-body p-8">
        <div class="text-center mb-8">
            <div class="mb-4">
                <i class="ki-outline ki-lock text-primary" style="font-size: 48px; color: #FFB800;"></i>
            </div>
            <h3 class="text-dark fw-bold fs-3">Lupa Password?</h3>
            <p class="text-muted fs-6">Masukkan email Anda untuk reset password</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success d-flex align-items-center p-5 mb-5">
                <i class="ki-outline ki-check-circle fs-1 me-4"></i>
                <div class="d-flex flex-column">
                    <span>{{ session('status') }}</span>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="form fv-plugins-bootstrap5 fv-plugins-framework">
            @csrf

            <div class="mb-6">
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
                        autofocus
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn npci-gradient btn-lg fw-bold text-dark">
                    <span class="indicator-label">Kirim Link Reset</span>
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
