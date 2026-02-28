@extends('layouts.main_layout')

@section('content')
<div class="card mb-5 mb-xl-8">
    <div class="card-header border-0 pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold fs-3 mb-1">Manajemen User</span>
            <span class="text-muted mt-1 fw-semibold fs-7">Daftar semua user yang terdaftar di sistem</span>
        </h3>
        <div class="card-toolbar">
            <button type="button" class="btn btn-sm btn-primary" id="btn-add-user">
                <i class="ki-duotone ki-plus fs-2"></i> Tambah User
            </button>
        </div>
    </div>
    <div class="card-body py-3">
        <div class="row g-5 mb-5">
            <div class="col-md-4">
                <div class="card bg-light-primary border-0">
                    <div class="card-body d-flex align-items-center py-6">
                        <div class="symbol symbol-45px me-5">
                            <div class="symbol-label bg-primary">
                                <i class="ki-duotone ki-people text-white fs-1">
                                    <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                                </i>
                            </div>
                        </div>
                        <div>
                            <span class="text-muted fw-semibold fs-7">Total User</span>
                            <h3 class="text-primary fw-bold mb-0">{{ $totalUsers }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-light-success border-0">
                    <div class="card-body d-flex align-items-center py-6">
                        <div class="symbol symbol-45px me-5">
                            <div class="symbol-label bg-success">
                                <i class="ki-duotone ki-check-circle text-white fs-1">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div>
                            <span class="text-muted fw-semibold fs-7">User Aktif</span>
                            <h3 class="text-success fw-bold mb-0">{{ $activeUsers }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-light-danger border-0">
                    <div class="card-body d-flex align-items-center py-6">
                        <div class="symbol symbol-45px me-5">
                            <div class="symbol-label bg-danger">
                                <i class="ki-duotone ki-cross-circle text-white fs-1">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div>
                            <span class="text-muted fw-semibold fs-7">User Tidak Aktif</span>
                            <h3 class="text-danger fw-bold mb-0">{{ $inactiveUsers }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            {{ $dataTable->table() }}
        </div>
    </div>
</div>

<div class="modal fade" id="user-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="user-modal-title">Tambah User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="user-modal-content">
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{ $dataTable->scripts() }}
<script src="{{ asset('js/pages/master/user.js') }}"></script>
@endpush
