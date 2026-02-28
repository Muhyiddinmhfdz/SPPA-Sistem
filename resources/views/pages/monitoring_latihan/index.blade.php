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

    .option-card.indigo {
        --oc-color: #7239ea;
    }

    /* ── Modal Header gradient ───────────────────────── */
    .modal-header-gradient {
        background: linear-gradient(135deg, #7239ea 0%, #5014d0 100%);
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
</style>
@endsection

@section('content')

{{-- FILTER --}}
<div class="card filter-card shadow-sm mb-5 px-6 py-5">
    <div class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label fw-semibold text-gray-700 fs-7">Cabang Olahraga</label>
            <select id="mlFilterCabor" class="form-select form-select-sm form-select-solid">
                <option value="">-- Semua Cabor --</option>
                @foreach($cabors as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold text-gray-700 fs-7">Nama Atlet / Pelatih</label>
            <select id="mlFilterPerson" class="form-select form-select-sm form-select-solid">
                <option value="">-- Semua --</option>
                @foreach($atlets as $a)
                <option value="{{ $a->id }}">{{ $a->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button id="mlBtnFilter" class="btn btn-sm btn-primary w-100">
                <i class="ki-duotone ki-magnifier fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>Filter
            </button>
        </div>
        <div class="col-md-2">
            <button id="mlBtnReset" class="btn btn-sm btn-light w-100">Reset</button>
        </div>
    </div>
</div>

{{-- TABS --}}
<div class="card shadow-sm">
    <div class="card-header border-0 pt-5">
        <ul class="nav nav-line-tabs nav-line-tabs-2x border-transparent fw-semibold">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#mlTabAtlet">
                    <i class="ki-duotone ki-people fs-4 me-1 text-success"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                    Atlet
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#mlTabPelatih">
                    <i class="ki-duotone ki-teacher fs-4 me-1 text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    Pelatih
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body pt-4">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="mlTabAtlet">
                <div class="table-responsive">
                    <table id="tableMonLat" class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4 fs-7">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 rounded-start">No</th>
                                <th>Nama Atlet</th>
                                <th>Cabor</th>
                                <th>Tanggal</th>
                                <th>Kehadiran</th>
                                <th>Durasi</th>
                                <th>Beban Latihan</th>
                                <th>Kesimpulan</th>
                                <th class="text-center rounded-end">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="mlTabPelatih">
                <div class="table-responsive">
                    <table id="tableMonLatPelatih" class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4 fs-7">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 rounded-start">No</th>
                                <th>Nama Pelatih</th>
                                <th>Cabor</th>
                                <th>Tanggal</th>
                                <th>Kehadiran</th>
                                <th>Durasi</th>
                                <th>Beban Latihan</th>
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

{{-- ===== MODAL ===== --}}
<div class="modal fade" id="modalMonLat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:14px; overflow:hidden; border:none;">

            {{-- GRADIENT HEADER --}}
            <div class="modal-header-gradient d-flex align-items-start justify-content-between">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="ki-duotone ki-barbell fs-1 text-white"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span></i>
                        <h4 class="modal-title fw-bold mb-0" id="modalMonLatTitle">Formulir Monitoring Latihan</h4>
                    </div>
                    <p class="header-meta mb-0">Catat riwayat kehadiran dan intensitas sesi latihan</p>
                </div>
                <button type="button" class="btn-close mt-1" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-6 py-5">
                <form id="formMonLat" novalidate>
                    @csrf
                    <input type="hidden" id="ml_id" name="ml_id">

                    {{-- ── SECTION: IDENTITAS ─────────────────── --}}
                    <div class="section-label">
                        <i class="ki-duotone ki-profile-user fs-5 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                        Identitas & Tanggal
                    </div>

                    <div class="row g-4 mb-5">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-7 required">Jenis</label>
                            <select class="form-select form-select-solid" id="ml_person_type" name="person_type">
                                <option value="atlet">🏊 Atlet</option>
                                <option value="pelatih">🎯 Pelatih</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-7 required">Cabor</label>
                            <select class="form-select form-select-solid" id="ml_cabor_id" name="cabor_id">
                                <option value="">-- Pilih Cabor --</option>
                                @foreach($cabors as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-7 required">Nama</label>
                            <select class="form-select form-select-solid" id="ml_person_id" name="person_id">
                                <option value="">-- Pilih Nama --</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-4 mb-5 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold fs-7 required">Tanggal Latihan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="ki-duotone ki-calendar-2 fs-4 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></span>
                                <input type="text" class="form-control form-control-solid border-0 ps-2" id="ml_tanggal" name="tanggal" placeholder="Pilih tanggal..." autocomplete="off" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-7 required">Kehadiran</label>
                            <select class="form-select form-select-solid" name="kehadiran" id="ml_kehadiran">
                                <option value="hadir">✅ Hadir</option>
                                <option value="tidak_hadir">❌ Tidak Hadir</option>
                                <option value="izin">📝 Izin</option>
                                <option value="sakit">🤒 Sakit</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold fs-7 required">Durasi (Menit)</label>
                            <input type="number" class="form-control form-control-solid" id="ml_durasi" name="durasi_latihan" placeholder="Contoh: 120">
                        </div>
                    </div>

                    {{-- ── SECTION: BEBAN LATIHAN ─────────────── --}}
                    <div class="section-label">
                        <i class="ki-duotone ki-chart-line-star fs-5 text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        Intensitas & Beban Latihan
                    </div>
                    <p class="text-muted fs-7 mb-3">Tentukan tingkat beban latihan pada sesi ini</p>

                    <div class="option-card-group mb-5">
                        <div class="option-card green">
                            <input type="radio" name="beban_latihan" id="bl_ringan" value="ringan">
                            <label for="bl_ringan">
                                <span style="font-size:1.8rem">🟢</span>
                                <span>Ringan</span>
                                <small class="text-muted fw-normal" style="font-size:11px">Nadi: 120-140 bpm</small>
                            </label>
                        </div>
                        <div class="option-card yellow">
                            <input type="radio" name="beban_latihan" id="bl_sedang" value="sedang">
                            <label for="bl_sedang">
                                <span style="font-size:1.8rem">🟡</span>
                                <span>Sedang</span>
                                <small class="text-muted fw-normal" style="font-size:11px">Nadi: 140-160 bpm</small>
                            </label>
                        </div>
                        <div class="option-card red">
                            <input type="radio" name="beban_latihan" id="bl_berat" value="berat">
                            <label for="bl_berat">
                                <span style="font-size:1.8rem">🔴</span>
                                <span>Berat</span>
                                <small class="text-muted fw-normal" style="font-size:11px">Nadi: >160 bpm</small>
                            </label>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold fs-7">Denyut Nadi / RPE (Rate of Perceived Exertion)</label>
                            <input type="text" class="form-control form-control-solid" id="ml_denyut" name="denyut_nadi_rpe" placeholder="Contoh: 145 bpm / RPE 8 (Sangat Melelahkan)">
                        </div>
                    </div>

                    {{-- ── SECTION: CATATAN & KESIMPULAN ──────── --}}
                    <div class="section-label">
                        <i class="ki-duotone ki-notepad-edit fs-5 text-primary"><span class="path1"></span><span class="path2"></span></i>
                        Catatan & Kesimpulan
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-semibold fs-7">Catatan Pelatih</label>
                        <textarea class="form-control form-control-solid" id="ml_catatan" name="catatan_pelatih" rows="2" placeholder="Evaluasi teknis atau catatan khusus sesi ini..."></textarea>
                    </div>

                    <div class="row g-3 mb-2">
                        <div class="col-md-6">
                            <label class="kesimpulan-card ks-baik w-100 d-block">
                                <input type="radio" name="kesimpulan" value="ya" id="ks_ya">
                                <div class="d-flex align-items-center gap-3">
                                    <span style="font-size:1.8rem">🏁</span>
                                    <div>
                                        <div class="fw-bold text-success">Lanjut Program</div>
                                        <div class="text-muted fs-8">Atlet/Pelatih siap untuk sesi berikutnya</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="kesimpulan-card ks-sedang w-100 d-block">
                                <input type="radio" name="kesimpulan" value="tidak" id="ks_tidak">
                                <div class="d-flex align-items-center gap-3">
                                    <span style="font-size:1.8rem">🔄</span>
                                    <div>
                                        <div class="fw-bold text-warning">Evaluasi Program</div>
                                        <div class="text-muted fs-8">Perlu penyesuaian intensitas latihan</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer border-top px-6 py-4" style="background:#f9f9f9;">
                <button type="button" class="btn btn-light fw-semibold" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary fw-semibold px-8" id="btnSimpanMonLat">
                    <span class="indicator-label">Simpan Monitoring</span>
                    <span class="indicator-progress d-none">Menyimpan... <span class="spinner-border spinner-border-sm ms-2 align-middle"></span></span>
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
<script src="{{ asset('js/pages/monitoring_latihan/monitoring_latihan.js') }}"></script>
<script>
    var mlDatePicker = flatpickr('#ml_tanggal', {
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'D, d F Y',
        allowInput: false,
        locale: 'id',
        disableMobile: true,
    });

    document.getElementById('modalMonLat').addEventListener('shown.bs.modal', function() {
        if (!document.getElementById('ml_id').value) {
            mlDatePicker.setDate(new Date(), true);
        }
    });
</script>
@endsection