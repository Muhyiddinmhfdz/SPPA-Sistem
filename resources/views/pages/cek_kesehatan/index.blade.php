@extends('layouts.main_layout')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* ── Filter + Nav ────────────────────────────────── */
    .filter-card {
        border-radius: 12px;
        background: #fff;
        border: 1px solid #f1f1f2;
    }

    .flatpickr-input[readonly] {
        background-color: #f5f8fa !important;
    }

    .nav-line-tabs .nav-item .nav-link {
        color: #a1a5b7;
        font-weight: 600;
    }

    .nav-line-tabs .nav-item .nav-link.active {
        color: #009ef7;
        border-bottom: 2px solid #009ef7;
    }

    /* ── Card-Style Radio Buttons ────────────────────── */
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
        min-width: 110px;
        padding: 12px 16px;
        border-radius: 10px;
        cursor: pointer;
        border: 2px solid #e4e6ef;
        background: #f9f9f9;
        font-weight: 600;
        font-size: 13px;
        gap: 6px;
        transition: all .2s ease;
        color: #5e6278;
        text-align: center;
    }

    .option-card label i {
        font-size: 1.6rem;
    }

    .option-card input[type="radio"]:checked+label {
        border-color: var(--oc-color, #009ef7);
        background: color-mix(in srgb, var(--oc-color, #009ef7) 10%, white);
        color: var(--oc-color, #009ef7);
        box-shadow: 0 4px 14px color-mix(in srgb, var(--oc-color, #009ef7) 25%, transparent);
    }

    .option-card.green {
        --oc-color: #50cd89;
    }

    .option-card.yellow {
        --oc-color: #ffc700;
    }

    .option-card.red {
        --oc-color: #f1416c;
    }

    .option-card.blue {
        --oc-color: #009ef7;
    }

    .option-card.teal {
        --oc-color: #17c6b1;
    }

    /* ── Modal Header gradient ───────────────────────── */
    .modal-header-gradient {
        background: linear-gradient(135deg, #009ef7 0%, #0d6efd 100%);
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

    /* ── Section Divider ─────────────────────────────── */
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

    /* ── Kesimpulan Card ─────────────────────────────── */
    .kesimpulan-card {
        border: 2px solid #e4e6ef;
        border-radius: 12px;
        padding: 14px 18px;
        cursor: pointer;
        transition: all .2s;
        position: relative;
    }

    .kesimpulan-card input {
        position: absolute;
        opacity: 0;
    }

    .kesimpulan-card:has(input:checked) {
        border-color: var(--kc, #009ef7);
        background: color-mix(in srgb, var(--kc, #009ef7) 6%, white);
    }

    .kesimpulan-card.ks-baik {
        --kc: #50cd89;
    }

    .kesimpulan-card.ks-sedang {
        --kc: #ffc700;
    }

    .kesimpulan-card.ks-berat {
        --kc: #f1416c;
    }

    .kesimpulan-card .ks-icon {
        font-size: 1.8rem;
    }

    .info-pill {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 50px;
        display: inline-block;
    }
</style>
@endsection

@section('content')

{{-- FILTER CARD --}}
<div class="card filter-card shadow-sm mb-5 px-6 py-5">
    <div class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label fw-semibold text-gray-700 fs-7">
                <i class="ki-duotone ki-abstract-26 fs-6 me-1 text-primary"><span class="path1"></span><span class="path2"></span></i>
                Cabang Olahraga
            </label>
            <select id="filterCabor" class="form-select form-select-sm form-select-solid">
                <option value="">-- Semua Cabor --</option>
                @foreach($cabors as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold text-gray-700 fs-7">
                <i class="ki-duotone ki-profile-user fs-6 me-1 text-success"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                Nama Atlet / Pelatih
            </label>
            <select id="filterPerson" class="form-select form-select-sm form-select-solid">
                <option value="">-- Semua --</option>
                @foreach($atlets as $a)
                <option value="{{ $a->id }}" data-type="atlet">{{ $a->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button id="btnFilter" class="btn btn-sm btn-primary w-100">
                <i class="ki-duotone ki-magnifier fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>Filter
            </button>
        </div>
        <div class="col-md-2">
            <button id="btnResetFilter" class="btn btn-sm btn-light w-100">
                <i class="ki-duotone ki-arrows-circle fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>Reset
            </button>
        </div>
    </div>
</div>

{{-- TABS --}}
<div class="card shadow-sm">
    <div class="card-header border-0 pt-5">
        <ul class="nav nav-line-tabs nav-line-tabs-2x border-transparent fw-semibold">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#tab_atlet">
                    <i class="ki-duotone ki-people fs-4 me-1 text-success"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                    Atlet
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tab_pelatih">
                    <i class="ki-duotone ki-teacher fs-4 me-1 text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    Pelatih
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body pt-4">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="tab_atlet">
                <div class="table-responsive">
                    <table id="tableAtlet" class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4 fs-7">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 rounded-start">No</th>
                                <th>Nama Atlet</th>
                                <th>Cabor</th>
                                <th>Tanggal Pemeriksaan</th>
                                <th>Kondisi Harian</th>
                                <th>Tingkat Cedera</th>
                                <th>Kesimpulan</th>
                                <th class="text-center rounded-end">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="tab_pelatih">
                <div class="table-responsive">
                    <table id="tablePelatih" class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4 fs-7">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 rounded-start">No</th>
                                <th>Nama Pelatih</th>
                                <th>Cabor</th>
                                <th>Tanggal Pemeriksaan</th>
                                <th>Kondisi Harian</th>
                                <th>Tingkat Cedera</th>
                                <th>Kesimpulan</th>
                                <th class="text-center rounded-end">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL INPUT CEK KESEHATAN ===== --}}
<div class="modal fade" id="modalCekKesehatan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:14px; overflow:hidden; border:none;">

            {{-- GRADIENT HEADER --}}
            <div class="modal-header-gradient d-flex align-items-start justify-content-between">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="ki-duotone ki-heart fs-1 text-white"><span class="path1"></span><span class="path2"></span></i>
                        <h4 class="modal-title fw-bold mb-0" id="modalTitle">Formulir Cek Kesehatan</h4>
                    </div>
                    <p class="header-meta mb-0" id="modalSubtitle">Isi semua data pemeriksaan dengan lengkap dan benar</p>
                </div>
                <button type="button" class="btn-close mt-1" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-6 py-5">
                <form id="formCekKesehatan" novalidate>
                    @csrf
                    <input type="hidden" id="ck_id" name="ck_id">

                    {{-- ── SECTION 1: IDENTITAS ─────────────────────────────── --}}
                    <div class="section-label">
                        <i class="ki-duotone ki-profile-user fs-5 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                        Identitas Peserta
                    </div>

                    <div class="row g-4 mb-5">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-7 required">Jenis</label>
                            <select class="form-select form-select-solid" id="ck_person_type" name="person_type">
                                <option value="atlet">🏊 Atlet</option>
                                <option value="pelatih">🎯 Pelatih</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-7 required">Cabang Olahraga</label>
                            <select class="form-select form-select-solid" id="ck_cabor_id" name="cabor_id">
                                <option value="">-- Pilih Cabor --</option>
                                @foreach($cabors as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-7 required">Nama</label>
                            <select class="form-select form-select-solid" id="ck_person_id" name="person_id">
                                <option value="">-- Pilih Nama --</option>
                            </select>
                        </div>
                    </div>

                    {{-- ── SECTION 2: TANGGAL ──────────────────────────────── --}}
                    <div class="section-label">
                        <i class="ki-duotone ki-calendar fs-5 text-info"><span class="path1"></span><span class="path2"></span></i>
                        Tanggal Pemeriksaan
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold fs-7 required">Tanggal</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">
                                    <i class="ki-duotone ki-calendar-2 fs-4 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                </span>
                                <input type="text" class="form-control form-control-solid border-0 ps-2" id="ck_tanggal" name="tanggal" placeholder="Pilih tanggal..." autocomplete="off" readonly>
                            </div>
                        </div>
                    </div>

                    {{-- ── SECTION 3: KONDISI HARIAN ───────────────────────── --}}
                    <div class="section-label">
                        <i class="ki-duotone ki-pulse fs-5 text-success"><span class="path1"></span><span class="path2"></span></i>
                        Kondisi Harian
                    </div>
                    <p class="text-muted fs-7 mb-3">Pilih kondisi fisik dan mental yang paling sesuai hari ini</p>

                    <div class="option-card-group mb-5">
                        <div class="option-card green">
                            <input type="radio" name="kondisi_harian" id="kh_sehat" value="sehat">
                            <label for="kh_sehat">
                                <span style="font-size:1.8rem">💪</span>
                                <span>Sehat</span>
                                <small class="fw-normal text-muted" style="font-size:11px">Fit & siap latihan</small>
                            </label>
                        </div>
                        <div class="option-card yellow">
                            <input type="radio" name="kondisi_harian" id="kh_lelah" value="lelah">
                            <label for="kh_lelah">
                                <span style="font-size:1.8rem">😓</span>
                                <span>Lelah</span>
                                <small class="fw-normal text-muted" style="font-size:11px">Perlu istirahat</small>
                            </label>
                        </div>
                        <div class="option-card red">
                            <input type="radio" name="kondisi_harian" id="kh_cidera" value="cidera">
                            <label for="kh_cidera">
                                <span style="font-size:1.8rem">🤕</span>
                                <span>Cedera</span>
                                <small class="fw-normal text-muted" style="font-size:11px">Ada keluhan fisik</small>
                            </label>
                        </div>
                    </div>

                    {{-- ── SECTION 4: TINGKAT CEDERA ───────────────────────── --}}
                    <div class="section-label">
                        <i class="ki-duotone ki-shield-cross fs-5 text-danger"><span class="path1"></span><span class="path2"></span></i>
                        Tingkat Cedera
                    </div>
                    <p class="text-muted fs-7 mb-3">Jika ada cedera, tentukan tingkat keparahannya</p>

                    <div class="option-card-group mb-5">
                        <div class="option-card green">
                            <input type="radio" name="tingkat_cedera" id="tc_tidak" value="tidak_cidera">
                            <label for="tc_tidak">
                                <span style="font-size:1.8rem">✅</span>
                                <span>Tidak Cedera</span>
                                <small class="fw-normal text-muted" style="font-size:11px">Kondisi normal</small>
                            </label>
                        </div>
                        <div class="option-card blue">
                            <input type="radio" name="tingkat_cedera" id="tc_ringan" value="ringan">
                            <label for="tc_ringan">
                                <span style="font-size:1.8rem">🩹</span>
                                <span>Ringan</span>
                                <small class="fw-normal text-muted" style="font-size:11px">Masih bisa latihan</small>
                            </label>
                        </div>
                        <div class="option-card yellow">
                            <input type="radio" name="tingkat_cedera" id="tc_sedang" value="sedang">
                            <label for="tc_sedang">
                                <span style="font-size:1.8rem">⚠️</span>
                                <span>Sedang</span>
                                <small class="fw-normal text-muted" style="font-size:11px">Perlu pengawasan</small>
                            </label>
                        </div>
                        <div class="option-card red">
                            <input type="radio" name="tingkat_cedera" id="tc_berat" value="berat">
                            <label for="tc_berat">
                                <span style="font-size:1.8rem">🚨</span>
                                <span>Berat</span>
                                <small class="fw-normal text-muted" style="font-size:11px">Butuh penanganan</small>
                            </label>
                        </div>
                    </div>

                    {{-- ── SECTION 5: RIWAYAT MEDIS ────────────────────────── --}}
                    <div class="section-label">
                        <i class="ki-duotone ki-document fs-5 text-warning"><span class="path1"></span><span class="path2"></span></i>
                        Riwayat Tindakan Medis
                    </div>
                    <div class="mb-5">
                        <textarea class="form-control form-control-solid" id="ck_riwayat" name="riwayat_medis" rows="3"
                            placeholder="Tuliskan riwayat tindakan medis sebelumnya jika ada (obat yang dikonsumsi, tindakan, dll.)..."></textarea>
                        <span class="text-muted fs-8 mt-1 d-block">
                            <i class="ki-duotone ki-information fs-6 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            Biarkan kosong jika tidak ada riwayat medis sebelumnya
                        </span>
                    </div>

                    {{-- ── SECTION 6: KESIMPULAN ───────────────────────────── --}}
                    <div class="section-label">
                        <i class="ki-duotone ki-check-circle fs-5 text-success"><span class="path1"></span><span class="path2"></span></i>
                        Kesimpulan & Rekomendasi
                    </div>
                    <p class="text-muted fs-7 mb-3">Tentukan kesimpulan berdasarkan hasil pemeriksaan keseluruhan</p>

                    <div class="row g-3 mb-5">
                        <div class="col-md-4">
                            <label class="kesimpulan-card ks-baik w-100 d-block">
                                <input type="radio" name="kesimpulan" value="baik" id="ks_baik">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="ks-icon">🟢</span>
                                    <div>
                                        <div class="fw-bold text-success">Baik</div>
                                        <div class="text-muted fs-8">Lanjut program latihan seperti biasa</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label class="kesimpulan-card ks-sedang w-100 d-block">
                                <input type="radio" name="kesimpulan" value="sedang" id="ks_sedang">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="ks-icon">🟡</span>
                                    <div>
                                        <div class="fw-bold text-warning">Sedang</div>
                                        <div class="text-muted fs-8">Evaluasi & sesuaikan program latihan</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label class="kesimpulan-card ks-berat w-100 d-block">
                                <input type="radio" name="kesimpulan" value="berat" id="ks_berat">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="ks-icon">🔴</span>
                                    <div>
                                        <div class="fw-bold text-danger">Berat</div>
                                        <div class="text-muted fs-8">Hentikan / penyesuaian program latihan</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- ── CATATAN TAMBAHAN ─────────────────────────────────── --}}
                    <div class="section-label">
                        <i class="ki-duotone ki-notepad-edit fs-5 text-muted"><span class="path1"></span><span class="path2"></span></i>
                        Catatan Tambahan <span class="fw-normal text-muted">(Opsional)</span>
                    </div>
                    <textarea class="form-control form-control-solid" id="ck_catatan" name="catatan" rows="2"
                        placeholder="Catatan tambahan dari petugas medis atau pelatih..."></textarea>
                </form>
            </div>

            <div class="modal-footer border-top px-6 py-4" style="background:#f9f9f9;">
                <button type="button" class="btn btn-light fw-semibold" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>Batal
                </button>
                <button type="button" class="btn btn-primary fw-semibold px-8" id="btnSimpanCekKesehatan">
                    <span class="indicator-label">
                        <i class="ki-duotone ki-check fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>Simpan Pemeriksaan
                    </span>
                    <span class="indicator-progress d-none">
                        Menyimpan... <span class="spinner-border spinner-border-sm ms-2 align-middle"></span>
                    </span>
                </button>
            </div>

        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
<script src="{{ asset('js/pages/cek_kesehatan/cek_kesehatan.js') }}"></script>
<script>
    var ckDatePicker = flatpickr('#ck_tanggal', {
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'D, d F Y',
        allowInput: false,
        locale: 'id',
        disableMobile: true,
    });

    document.getElementById('modalCekKesehatan').addEventListener('shown.bs.modal', function() {
        if (!document.getElementById('ck_id').value) {
            ckDatePicker.setDate(new Date(), true);
        }
    });
</script>
@endsection