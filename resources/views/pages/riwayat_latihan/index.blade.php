@extends('layouts.main_layout')

@section('content')

{{-- FILTER --}}
<div class="card shadow-sm mb-5 px-6 py-5" style="border-radius:12px; border:1px solid #f1f1f2;">
    <div class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label fw-semibold text-gray-700 fs-7">Cabang Olahraga</label>
            <select id="rlFilterCabor" class="form-select form-select-sm form-select-solid">
                <option value="">-- Semua Cabor --</option>
                @foreach($cabors as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold text-gray-700 fs-7">Nama</label>
            <input type="text" id="rlFilterNama" class="form-control form-control-sm form-control-solid" placeholder="Cari nama...">
        </div>
        <div class="col-md-2">
            <button id="rlBtnFilter" class="btn btn-sm btn-primary w-100">
                <i class="ki-duotone ki-magnifier fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>Filter
            </button>
        </div>
        <div class="col-md-2">
            <button id="rlBtnReset" class="btn btn-sm btn-light w-100">Reset</button>
        </div>
    </div>
</div>

{{-- TABS --}}
<div class="card shadow-sm" style="border-radius:12px;">
    <div class="card-header border-0 pt-5">
        <ul class="nav nav-line-tabs nav-line-tabs-2x border-transparent fw-semibold">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#rlTabAtlet">
                    <i class="ki-duotone ki-people fs-4 me-1 text-success"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                    Atlet
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#rlTabPelatih">
                    <i class="ki-duotone ki-teacher fs-4 me-1 text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    Pelatih
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body pt-4">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="rlTabAtlet">
                <div class="table-responsive">
                    <table id="tableRlAtlet" class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4 fs-7">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 rounded-start">No</th>
                                <th>Nama Atlet</th>
                                <th>Cabor</th>
                                <th>Jumlah Sesi</th>
                                <th class="text-center rounded-end">Riwayat</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="rlTabPelatih">
                <div class="table-responsive">
                    <table id="tableRlPelatih" class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4 fs-7">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 rounded-start">No</th>
                                <th>Nama Pelatih</th>
                                <th>Cabor</th>
                                <th>Jumlah Sesi</th>
                                <th class="text-center rounded-end">Riwayat</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DETAIL --}}
<div class="modal fade" id="modalRiwayatLatihan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h4 class="modal-title fw-bold mb-1" id="modalRLTitle">Riwayat Latihan</h4>
                    <p class="text-muted fs-7 mb-0" id="modalRLSubtitle"></p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="tableDetailLatihan" class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-3 fs-7">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 rounded-start">No</th>
                                <th>Tanggal</th>
                                <th>Kehadiran</th>
                                <th>Durasi</th>
                                <th>Beban</th>
                                <th>Denyut Nadi/RPE</th>
                                <th>Catatan Pelatih</th>
                                <th class="rounded-end">Kesimpulan</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('js/pages/riwayat_latihan/riwayat_latihan.js') }}"></script>
@endsection