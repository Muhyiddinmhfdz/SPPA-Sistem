@extends('layouts.main_layout')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .modal-header-gradient {
        background: linear-gradient(135deg, #009ef7 0%, #0050ee 100%);
        border-radius: 12px 12px 0 0;
        padding: 20px 24px 16px;
    }

    .modal-header-gradient .modal-title {
        color: #fff;
    }

    .modal-header-gradient .btn-close {
        filter: invert(1);
        opacity: .8;
    }

    .modal-header-gradient .header-meta {
        color: rgba(255, 255, 255, .75);
        font-size: 13px;
    }

    .section-label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .8px;
        color: #a1a5b7;
        margin-bottom: 14px;
    }

    .section-label::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #f1f1f2;
    }

    .option-card-group {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .option-card input[type="radio"] {
        display: none;
    }

    .option-card label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-width: 100px;
        padding: 12px;
        border-radius: 10px;
        cursor: pointer;
        border: 2px solid #e4e6ef;
        background: #f9f9f9;
        font-weight: 600;
        font-size: 12px;
        gap: 4px;
        transition: all .2s ease;
        color: #5e6278;
    }

    .option-card input[type="radio"]:checked+label {
        border-color: var(--oc-color, #009ef7);
        background: color-mix(in srgb, var(--oc-color, #009ef7) 10%, white);
        color: var(--oc-color, #009ef7);
    }

    .option-card.blue {
        --oc-color: #009ef7;
    }

    .option-card.green {
        --oc-color: #50cd89;
    }

    .option-card.orange {
        --oc-color: #ff9800;
    }

    .option-card.gold {
        --oc-color: #ffc107;
    }

    .option-card.silver {
        --oc-color: #9e9e9e;
    }

    .option-card.bronze {
        --oc-color: #cd7f32;
    }
</style>
@endsection

@section('content')
<div class="row g-5 g-xl-8">
    <div class="col-xl-12">
        <!-- Card Filter -->
        <div class="card mb-5 mb-xl-8 border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-4 text-gray-900">Filter Pencarian</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Saring data kompetisi berdasarkan kriteria</span>
                </h3>
            </div>
            <div class="card-body pb-10 pt-0">
                <div class="row g-8">
                    <div class="col-md-4">
                        <label class="form-label fs-7 fw-bold text-gray-700">Cabang Olahraga:</label>
                        <select class="form-select form-select-solid" id="filter_cabor_id" data-control="select2" data-placeholder="Semua Cabor" data-allow-clear="true">
                            <option></option>
                            @foreach($cabors as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fs-7 fw-bold text-gray-700">Atlet:</label>
                        <select class="form-select form-select-solid" id="filter_atlet_id" data-control="select2" data-placeholder="Semua Atlet" data-allow-clear="true">
                            <option></option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="button" class="btn btn-light-danger w-100" id="btnResetFilter">
                            <i class="ki-duotone ki-arrows-loop fs-2"><span class="path1"></span><span class="path2"></span></i>Reset Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card card-xl-stretch mb-5 mb-xl-8 border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 text-gray-900">Data Kompetisi</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Riwayat partisipasi dan prestasi atlet dalam kompetisi</span>
                </h3>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-primary" id="btnTambahKompetisi">
                        <i class="ki-duotone ki-plus fs-2"></i>Tambah Kompetisi
                    </button>
                </div>
            </div>
            <div class="card-body py-4">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="table_kompetisi">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 min-w-50px rounded-start">No</th>
                                <th class="min-w-150px">Nama Kompetisi</th>
                                <th class="min-w-120px">Atlet</th>
                                <th class="min-w-100px">Cabor</th>
                                <th class="min-w-100px">Tingkatan</th>
                                <th class="min-w-100px">Tanggal</th>
                                <th class="min-w-100px">Medali</th>
                                <th class="min-w-100px text-center rounded-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="modalKompetisi" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px; overflow:hidden; border:none;">
            <div class="modal-header-gradient d-flex align-items-start justify-content-between">
                <div>
                    <h4 class="modal-title fw-bold mb-0" id="modalTitle">Formulir Kompetisi</h4>
                    <p class="header-meta mb-0">Input data rincian kompetisi dan hasil capaian</p>
                </div>
                <button type="button" class="btn-close mt-1" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body scroll-y px-10 py-8">
                <form id="formKompetisi" class="form">
                    @csrf
                    <input type="hidden" name="kompetisi_id" id="kompetisi_id">

                    <div class="section-label">Informasi Dasar</div>
                    <div class="row g-5 mb-7">
                        <div class="col-md-6">
                            <label class="required fs-6 fw-semibold mb-2">Cabang Olahraga (Cabor)</label>
                            <select class="form-select form-select-solid" name="cabor_id" id="cabor_id" data-control="select2" data-dropdown-parent="#modalKompetisi" data-placeholder="Pilih Cabor">
                                <option value=""></option>
                                @foreach($cabors as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="required fs-6 fw-semibold mb-2">Atlet</label>
                            <select class="form-select form-select-solid" name="atlet_id" id="atlet_id" data-control="select2" data-dropdown-parent="#modalKompetisi" data-placeholder="Pilih Atlet">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="section-label">Detail Kompetisi</div>
                    <div class="row g-5 mb-7">
                        <div class="col-md-12">
                            <label class="required fs-6 fw-semibold mb-2">Nama Kompetisi</label>
                            <input type="text" class="form-control form-control-solid" name="nama_kompetisi" id="nama_kompetisi" placeholder="Contoh: PEPARNAS XVII">
                        </div>
                    </div>

                    <div class="row g-5 mb-7">
                        <div class="col-md-4">
                            <label class="required fs-6 fw-semibold mb-2">Tingkatan</label>
                            <select class="form-select form-select-solid" name="tingkatan" id="tingkatan">
                                <option value="Internasional">Internasional</option>
                                <option value="Nasional">Nasional</option>
                                <option value="Daerah">Daerah</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="required fs-6 fw-semibold mb-2">Waktu Pelaksanaan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="ki-duotone ki-calendar fs-2"></i></span>
                                <input type="text" class="form-control form-control-solid" name="waktu_pelaksanaan" id="waktu_pelaksanaan" placeholder="Pilih Tanggal">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="required fs-6 fw-semibold mb-2">Tempat Pelaksanaan</label>
                            <input type="text" class="form-control form-control-solid" name="tempat_pelaksanaan" id="tempat_pelaksanaan" placeholder="Kota/Negara">
                        </div>
                    </div>

                    <div class="section-label">Hasil & Evaluasi</div>
                    <div class="row g-5 mb-7 text-center">
                        <div class="col-md-12">
                            <label class="fs-6 fw-semibold mb-4 d-block">Hasil Medali</label>
                            <div class="option-card-group justify-content-center">
                                <div class="option-card gold">
                                    <input type="radio" name="hasil_medali" id="medali_emas" value="emas">
                                    <label for="medali_emas"><span>🥇</span><span>Emas</span></label>
                                </div>
                                <div class="option-card silver">
                                    <input type="radio" name="hasil_medali" id="medali_perak" value="perak">
                                    <label for="medali_perak"><span>🥈</span><span>Perak</span></label>
                                </div>
                                <div class="option-card bronze">
                                    <input type="radio" name="hasil_medali" id="medali_perunggu" value="perunggu">
                                    <label for="medali_perunggu"><span>🥉</span><span>Perunggu</span></label>
                                </div>
                                <div class="option-card blue">
                                    <input type="radio" name="hasil_medali" id="medali_none" value="tanpa_medali" checked>
                                    <label for="medali_none"><span>➖</span><span>Tanpa Medali</span></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-5 mb-7">
                        <div class="col-md-6">
                            <label class="fs-6 fw-semibold mb-2">Peringkat Akhir</label>
                            <input type="text" class="form-control form-control-solid" name="hasil_peringkat" id="hasil_peringkat" placeholder="Misal: 4 Besar / Semifinal">
                        </div>
                        <div class="col-md-6">
                            <label class="fs-6 fw-semibold mb-2">Jumlah Peserta</label>
                            <input type="number" class="form-control form-control-solid" name="jumlah_peserta" id="jumlah_peserta" placeholder="Total peserta/negara">
                        </div>
                    </div>

                    <div class="row g-5">
                        <div class="col-md-12">
                            <label class="fs-6 fw-semibold mb-2">Kesimpulan / Evaluasi</label>
                            <textarea class="form-control form-control-solid" name="kesimpulan_evaluasi" id="kesimpulan_evaluasi" rows="3" placeholder="Catatan evaluasi performa atlet..."></textarea>
                        </div>
                    </div>

                    <div class="text-center pt-10">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btnSubmit">
                            <span class="indicator-label">Simpan Data</span>
                            <span class="indicator-progress">Memproses... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
<script>
    window.routes = {
        index: "{{ route('kompetisi.index') }}",
        store: "{{ route('kompetisi.store') }}",
        atlets: "{{ url('kompetisi/get-atlets') }}",
    };
    var csrf_token = "{{ csrf_token() }}";
</script>
<script src="{{ asset('js/pages/kompetisi/kompetisi.js') }}"></script>
@endsection