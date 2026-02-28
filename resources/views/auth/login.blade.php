@extends('layouts.auth')

@section('content')
<div class="card shadow-sm border-0" style="max-width: 450px; width: 100%; background: #ffffff; border-radius: 16px; overflow: hidden; transform: translateY(0); transition: all 0.3s; box-shadow: 0 15px 35px rgba(0,0,0,0.2) !important;">
    <div class="card-body p-8 p-lg-10">
        <div class="text-center mb-8">
            <h3 class="text-dark fw-bolder fs-2 mb-2">Selamat Datang</h3>
            <p class="text-gray-500 fs-6 fw-medium">Masuk untuk melanjutkan ke dashboard</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="form w-100" id="kt_sign_in_form">
            @csrf

            <div class="fv-row mb-6">
                <label class="form-label fs-6 fw-bold text-dark">Username</label>
                <div class="position-relative">
                    <span class="position-absolute top-50 translate-middle-y ms-4">
                        <i class="ki-outline ki-user fs-3 text-gray-500"></i>
                    </span>
                    <input
                        type="text"
                        name="username"
                        class="form-control form-control-lg form-control-solid fw-semibold ps-12 @error('username') is-invalid @enderror"
                        placeholder="Masukkan username"
                        value="{{ old('username') }}"
                        required
                        autofocus
                        autocomplete="off"
                        style="border-radius: 10px;">
                    @error('username')
                    <div class="invalid-feedback ms-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="fv-row mb-6">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label fs-6 fw-bold text-dark mb-0">Password</label>
                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="link-primary fs-7 fw-bold" style="transition: color 0.2s;">Lupa Password?</a>
                    @endif
                </div>
                <div class="position-relative">
                    <span class="position-absolute top-50 translate-middle-y ms-4">
                        <i class="ki-outline ki-lock-2 fs-3 text-gray-500"></i>
                    </span>
                    <input
                        type="password"
                        name="password"
                        class="form-control form-control-lg form-control-solid fw-semibold ps-12 border-0 @error('password') is-invalid @enderror"
                        placeholder="Masukkan password"
                        required
                        id="password_input"
                        autocomplete="current-password"
                        style="border-radius: 10px;">
                    <span class="position-absolute top-50 translate-middle-y end-0 me-4 cursor-pointer" id="toggle_password">
                        <i class="ki-outline ki-eye-slash fs-3 text-gray-500 toggle-password-icon hover-primary"></i>
                    </span>
                </div>
                @error('password')
                <div class="text-danger mt-2 ms-2 fs-7">{{ $message }}</div>
                @enderror
            </div>

            <div class="fv-row mb-8">
                <label class="form-check form-check-custom form-check-solid form-check-inline">
                    <input class="form-check-input" type="checkbox" name="remember" value="1">
                    <span class="form-check-label fw-semibold text-gray-600 fs-7">Biarkan saya tetap masuk</span>
                </label>
            </div>

            <div class="d-grid mb-6">
                <button type="submit" id="kt_sign_in_submit" class="btn npci-gradient text-dark fw-bolder fs-5 py-3 transition-all" style="border-radius: 10px;">
                    <span class="indicator-label d-flex justify-content-center align-items-center">
                        <i class="ki-outline ki-entrance-left fs-2 me-2 text-dark"></i> Masuk Sistem
                    </span>
                    <span class="indicator-progress text-dark">
                        Mohon tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2 text-dark"></span>
                    </span>
                </button>
            </div>

            @if (Route::has('register'))
            <div class="text-center">
                <p class="text-gray-500 fs-6 fw-medium mb-0">
                    Belum memiliki akun?
                    <a href="{{ route('register') }}" class="link-primary fw-bolder">Daftar Sekarang</a>
                </p>
            </div>
            @endif
        </form>
    </div>
</div>

<style>
    .hover-primary:hover {
        color: #FFB800 !important;
        transition: color 0.2s ease;
    }

    .link-primary {
        color: #FFB800 !important;
    }

    .link-primary:hover {
        color: #FF8C00 !important;
    }

    /* Input focus wrapper effect */
    .form-control-solid:focus {
        background-color: #f1f1f4;
        border-color: #FFB800 !important;
        box-shadow: 0 0 0 0.25rem rgba(255, 184, 0, 0.25) !important;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        const togglePassword = document.querySelector('#toggle_password');
        const passwordInput = document.querySelector('#password_input');
        const toggleIcon = document.querySelector('.toggle-password-icon');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                if (type === 'password') {
                    toggleIcon.classList.remove('ki-eye');
                    toggleIcon.classList.add('ki-eye-slash');
                } else {
                    toggleIcon.classList.remove('ki-eye-slash');
                    toggleIcon.classList.add('ki-eye');
                    toggleIcon.classList.add('text-primary');
                }
            });
        }

        // Enhance button state on submit
        const form = document.querySelector('#kt_sign_in_form');
        const submitBtn = document.querySelector('#kt_sign_in_submit');

        if (form && submitBtn) {
            form.addEventListener('submit', function() {
                submitBtn.setAttribute('data-kt-indicator', 'on');
                submitBtn.classList.add('disabled');
            });
        }
    });
</script>
@endsection