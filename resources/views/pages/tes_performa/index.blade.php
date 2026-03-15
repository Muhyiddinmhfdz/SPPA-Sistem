@extends('layouts.main_layout')

@section('css')
<style>
    /* Filter & table card */
    .tp-form-card {
        background: #f8f9fb;
        border: 1px dashed #d8e0ef;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
    }

    .tp-section-label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #a1a5b7;
        margin-bottom: .6rem;
    }

    .tp-context-card {
        background: #eef6ff;
        border: 1px solid #d6e6ff;
        border-radius: 12px;
        padding: 1rem 1.4rem;
    }

    .tp-helper {
        color: #7e8299;
        font-size: 11px;
        margin-top: .35rem;
        line-height: 1.4;
    }

    /* Test item rows inside modal */
    .tp-category-header {
        background: linear-gradient(90deg, #1a1a2e 0%, #16213e 100%);
        color: #fff;
        border-radius: 10px;
        padding: .6rem 1rem;
        font-weight: 700;
        font-size: 13px;
        margin-bottom: .5rem;
        display: flex;
        align-items: center;
        gap: .75rem;
    }

    .tp-item-row {
        display: flex;
        align-items: center;
        gap: .75rem;
        padding: .55rem .75rem;
        border-radius: 8px;
        border: 1px solid #f0f0f8;
        background: #fff;
        margin-bottom: .4rem;
    }

    .tp-item-row:hover {
        border-color: #cdd9ef;
        background: #fafbff;
    }

    .tp-item-name {
        flex: 1;
        font-size: 13px;
        font-weight: 600;
        color: #3f4254;
    }

    .tp-item-satuan {
        font-size: 11px;
        color: #a1a5b7;
        min-width: 60px;
    }

    .tp-item-input {
        width: 110px;
    }

    .tp-item-badge {
        min-width: 90px;
        font-size: 11px;
    }

    /* Score badge auto-resolved */
    .tp-score-pill {
        border-radius: 20px;
        padding: 3px 10px;
        font-size: 11px;
        font-weight: 600;
        min-width: 70px;
        text-align: center;
    }

    .tp-score-4 {
        background: #e8fff3;
        color: #1bc5bd;
        border: 1px solid #b5edd8;
    }

    .tp-score-3 {
        background: #eef1ff;
        color: #6078f4;
        border: 1px solid #c8d0fb;
    }

    .tp-score-2 {
        background: #fff8dd;
        color: #f6a600;
        border: 1px solid #f6d782;
    }

    .tp-score-1 {
        background: #fff5f5;
        color: #f1416c;
        border: 1px solid #f9b3c5;
    }

    .tp-score-na {
        background: #f5f5f5;
        color: #a1a5b7;
        border: 1px solid #e4e6ef;
    }
</style>
@endsection

@section('content')
<div class="row g-5 g-xl-8">
    <div class="col-xl-12">

        {{-- Filter Card --}}
        <div class="card mb-5 mb-xl-8 border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-4 text-gray-900">Filter Pencarian</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Saring data hasil tes performa atlet</span>
                </h3>
            </div>
            <div class="card-body pb-8 pt-0">
                <div class="row g-6">
                    <div class="col-md-3">
                        <label class="form-label fs-7 fw-bold text-gray-700">Cabang Olahraga:</label>
                        <select class="form-select form-select-solid" id="filter_cabor_id" data-control="select2" data-placeholder="Semua Cabor" data-allow-clear="true">
                            <option></option>
                            @foreach($cabors as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fs-7 fw-bold text-gray-700">Nama Atlet:</label>
                        <select class="form-select form-select-solid" id="filter_atlet_id" data-control="select2" data-placeholder="Pilih Atlet" data-allow-clear="true">
                            <option></option>
                            @foreach($atlets as $a)
                            <option value="{{ $a->id }}" data-cabor="{{ $a->cabor_id }}">{{ $a->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fs-7 fw-bold text-gray-700">Dari Tanggal:</label>
                        <input type="date" class="form-control form-control-solid" id="filter_tanggal_from">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fs-7 fw-bold text-gray-700">Sampai Tanggal:</label>
                        <input type="date" class="form-control form-control-solid" id="filter_tanggal_to">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-light-danger w-100" id="btnResetFilter">
                            <i class="ki-duotone ki-arrows-loop fs-2"><span class="path1"></span><span class="path2"></span></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="card card-xl-stretch mb-5 mb-xl-8 border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 text-gray-900">Input Tes Performa</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Pencatatan hasil tes fisik atlet NPCI per sesi</span>
                </h3>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-primary fw-bold" id="btnTambahPerformance" data-bs-toggle="modal" data-bs-target="#kt_modal_performance">
                        <i class="ki-duotone ki-plus fs-2"><span class="path1"></span><span class="path2"></span></i> Input Tes Baru
                    </button>
                </div>
            </div>
            <div class="card-body py-4">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="table_performance">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 min-w-50px rounded-start">No</th>
                                <th class="min-w-100px">Tanggal</th>
                                <th class="min-w-160px">Nama Atlet</th>
                                <th class="min-w-120px">Cabor</th>
                                <th class="min-w-150px">Jenis Disabilitas</th>
                                <th class="min-w-100px">Status Kesehatan</th>
                                <th class="min-w-100px text-center rounded-end">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===================== MODAL TAMBAH/EDIT ===================== --}}
<div class="modal fade" id="kt_modal_performance" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 1100px;">
        <div class="modal-content rounded-4 shadow-lg border-0">

            {{-- Header --}}
            <div class="modal-header border-0 px-10 pt-8 pb-0">
                <div>
                    <h3 class="fw-bolder text-gray-900 mb-1" id="modalPerformanceTitle">Input Tes Performa</h3>
                    <p class="text-muted fs-7 mb-0">Isikan informasi atlet dan nilai hasil tes fisik secara lengkap.</p>
                </div>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            {{-- Body --}}
            <div class="modal-body px-10 py-7">
                <form id="formPerformance">
                    @csrf
                    <input type="hidden" id="performance_id" name="_method_id">

                    {{-- ── BAGIAN 1: Info Atlet ── --}}
                    <div class="tp-section-label">1. Informasi Atlet</div>
                    <div class="tp-form-card mb-6">
                        <div class="row g-5">
                            <div class="col-lg-6 fv-row">
                                <label class="required fs-7 fw-bold text-gray-700 mb-2">Nama Atlet</label>
                                <select class="form-select form-select-solid form-select-lg" name="atlet_id" id="atlet_id" data-control="select2" data-placeholder="Pilih Atlet..." data-dropdown-parent="#kt_modal_performance">
                                    <option></option>
                                    @foreach($atlets as $a)
                                    <option value="{{ $a->id }}" data-cabor="{{ $a->cabor_id }}" data-klasifikasi="{{ $a->klasifikasi_disabilitas_id }}" data-disabilitas="{{ $a->jenis_disabilitas_id }}">{{ $a->name }}</option>
                                    @endforeach
                                </select>
                                <div class="tp-helper">Pilih atlet untuk mengotomatiskan cabor dan disabilitas.</div>
                            </div>

                            <div class="col-lg-6">
                                <div class="tp-context-card h-100 d-flex flex-column justify-content-center" id="atletInfoCard">
                                    <div class="tp-section-label mb-2">Info Otomatis dari Data Atlet</div>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <span class="text-muted fs-8">Cabor</span>
                                            <div class="fw-bold fs-7" id="infoAtletCabor">-</div>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-muted fs-8">Jenis Disabilitas</span>
                                            <div class="fw-bold fs-7" id="infoAtletDisabilitas">-</div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="cabor_id" id="cabor_id">
                                    <input type="hidden" name="klasifikasi_disabilitas_id" id="klasifikasi_disabilitas_id">
                                    <input type="hidden" name="jenis_disabilitas_id" id="jenis_disabilitas_id">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── BAGIAN 2: Info Sesi ── --}}
                    <div class="tp-section-label">2. Informasi Sesi Tes</div>
                    <div class="tp-form-card mb-6">
                        <div class="row g-5">
                            <div class="col-lg-3 fv-row">
                                <label class="required fs-7 fw-bold text-gray-700 mb-2">Tanggal Pelaksanaan</label>
                                <input type="date" name="tanggal_pelaksanaan" id="tanggal_pelaksanaan" class="form-control form-control-solid form-control-lg">
                            </div>
                            <div class="col-lg-3 fv-row">
                                <label class="required fs-7 fw-bold text-gray-700 mb-2">Status Kesehatan</label>
                                <select class="form-select form-select-solid form-select-lg" name="status_kesehatan" id="status_kesehatan" data-control="select2" data-dropdown-parent="#kt_modal_performance">
                                    <option value="fit">✅ Fit</option>
                                    <option value="cidera">🤕 Cidera</option>
                                    <option value="rehabilitasi">🩺 Rehabilitasi</option>
                                </select>
                            </div>
                            <div class="col-lg-3 fv-row">
                                <label class="fs-7 fw-bold text-gray-700 mb-2">Alat Bantu</label>
                                <select class="form-select form-select-solid form-select-lg" name="alat_bantu" id="alat_bantu" data-control="select2" data-placeholder="Tidak Ada" data-allow-clear="true" data-dropdown-parent="#kt_modal_performance">
                                    <option></option>
                                    <option value="prothesis">Prothesis</option>
                                    <option value="orthosis">Orthosis</option>
                                    <option value="wheelchair_manual">Wheelchair Manual</option>
                                    <option value="wheelchair_racing">Wheelchair Racing</option>
                                </select>
                            </div>
                            <div class="col-lg-3 fv-row">
                                <label class="fs-7 fw-bold text-gray-700 mb-2">Spesialisasi / Nomor / Kelas</label>
                                <input type="text" name="spesialisasi" id="spesialisasi" class="form-control form-control-solid form-control-lg" placeholder="Kelas A, T11, dll.">
                            </div>
                            <div class="col-lg-6 fv-row">
                                <label class="fs-7 fw-bold text-gray-700 mb-2">Nama Penguji</label>
                                <input type="text" name="penguji" id="penguji" class="form-control form-control-solid form-control-lg" placeholder="Nama petugas yang mengetes">
                            </div>
                        </div>
                    </div>

                    {{-- ── BAGIAN 3: Komponen Tes Fisik ── --}}
                    <div class="tp-section-label">3. Komponen Tes Fisik</div>
                    <div id="testItemsWrapper">
                        <div class="text-center py-10 text-muted fs-7" id="testItemsPlaceholder">
                            <i class="ki-duotone ki-information-5 fs-3x text-muted opacity-30"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            <div class="mt-3 fw-semibold">Pilih atlet terlebih dahulu untuk menampilkan komponen tes fisik yang relevan.</div>
                        </div>
                        <div id="testItemsContainer" class="d-none"></div>
                    </div>

                </form>
            </div>

            {{-- Footer --}}
            <div class="modal-footer border-0 px-10 pb-8 pt-0">
                <button type="button" class="btn btn-light fw-bold me-3" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary fw-bold" id="btnSavePerformance">
                    <span class="indicator-label"><i class="ki-duotone ki-check fs-2"></i> Simpan Tes</span>
                    <span class="indicator-progress">Menyimpan... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===================== MODAL DETAIL VIEW ===================== --}}
<div class="modal fade" id="kt_modal_performance_detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 860px;">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header border-0 px-8 pt-7 pb-4" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); border-radius: 16px 16px 0 0;">
                <div class="d-flex align-items-center gap-4">
                    <span class="bg-white bg-opacity-10 p-3 rounded-3">
                        <i class="ki-duotone ki-chart-simple-2 fs-1 text-white opacity-75"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                    </span>
                    <div>
                        <div class="text-white opacity-60 fs-8 fw-semibold text-uppercase">Hasil Tes Performa</div>
                        <h4 class="fw-bolder text-white mb-0" id="detailAtletName">-</h4>
                        <span class="text-white opacity-60 fs-8" id="detailTanggal">-</span>
                    </div>
                </div>
                <div class="btn btn-sm btn-icon" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1 text-white opacity-75"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body px-8 py-7">
                <div id="performanceDetailContent">
                    <div class="d-flex justify-content-center py-10">
                        <span class="spinner-border text-primary" style="width:2.5rem;height:2.5rem;"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 px-8 pb-7 pt-0">
                <button class="btn btn-light fw-bold" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    window.routes = {
        index: "{{ route('tes-performa.index') }}",
        store: "{{ route('tes-performa.store') }}",
        show: "{{ url('tes-performa') }}",
        edit: "{{ url('tes-performa') }}",
        update: "{{ url('tes-performa') }}",
        destroy: "{{ url('tes-performa') }}",
        atletData: "{{ url('tes-performa/atlet-data') }}",
        testItems: "{{ url('tes-performa/test-items') }}",
    };
</script>
<script src="{{ asset('js/pages/tes_performa.js') }}"></script>
@endsection