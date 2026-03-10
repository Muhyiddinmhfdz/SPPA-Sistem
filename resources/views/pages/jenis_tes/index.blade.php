@extends('layouts.main_layout')

@section('css')
<style>
    .jenis-tes-form-card {
        background: #f8f9fb;
        border: 1px dashed #d8e0ef;
        border-radius: 12px;
        padding: 1.5rem;
    }

    .jenis-tes-context-card {
        background: #eef6ff;
        border: 1px solid #d6e6ff;
        border-radius: 12px;
        padding: 1.1rem 1.4rem;
    }

    .jenis-tes-guide {
        background: #fff8dd;
        border: 1px solid #f6d782;
        border-radius: 10px;
        padding: 0.75rem 1rem;
    }

    .jenis-tes-helper {
        color: #7e8299;
        font-size: 11px;
        line-height: 1.4;
        margin-top: 0.4rem;
    }

    .jenis-tes-section-label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #a1a5b7;
        margin-bottom: 0.5rem;
    }

    .score-legend-box {
        background: linear-gradient(135deg, #f9f5ff 0%, #f0f4ff 100%);
        border: 1px solid #e0d9f6;
        border-radius: 12px;
        padding: 1rem 1.2rem;
    }

    .score-legend-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 20px;
        padding: 3px 10px;
        font-size: 12px;
        font-weight: 600;
        color: #3f4254;
        white-space: nowrap;
    }

    /* --- Detail Modal --- */
    .detail-item-card {
        background: #ffffff;
        border: 1px solid #f0f0f8;
        border-radius: 12px;
        padding: 1.1rem 1.25rem;
        transition: box-shadow 0.2s;
    }

    .detail-item-card:hover {
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
    }

    .detail-item-badge {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #eef6ff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        color: #3699ff;
        flex-shrink: 0;
    }

    .score-row-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .score-chip-4 {
        background: #e8fff3;
        color: #1bc5bd;
        border: 1px solid #b5edd8;
    }

    .score-chip-3 {
        background: #eef1ff;
        color: #6078f4;
        border: 1px solid #c8d0fb;
    }

    .score-chip-2 {
        background: #fff8dd;
        color: #f6a600;
        border: 1px solid #f6d782;
    }

    .score-chip-1 {
        background: #fff5f5;
        color: #f1416c;
        border: 1px solid #f9b3c5;
    }

    .score-chip-default {
        background: #f5f5f5;
        color: #5e6278;
        border: 1px solid #e4e6ef;
    }

    .detail-no-scores {
        background: #fafafa;
        border: 1px dashed #dee2e6;
        border-radius: 10px;
        padding: 0.65rem 1rem;
        color: #a1a5b7;
        font-size: 12px;
    }
</style>
@endsection

@section('content')
<div class="row g-5 g-xl-8">
    <div class="col-xl-12">
        <div class="card card-xl-stretch mb-5 mb-xl-8 border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 text-gray-900">Komponen Tes Fisik</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Manajemen parameter tes fisik, satuan, dan kriteria penilaian</span>
                </h3>
                <div class="card-toolbar d-flex align-items-center gap-3">
                    <div class="w-175px">
                        <select class="form-select form-select-sm form-select-solid fw-bold" id="filter_cabor_id" data-control="select2" data-placeholder="Filter Cabor" data-allow-clear="true">
                            <option></option>
                            @foreach($cabors as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" class="btn btn-sm btn-light-primary fw-bold" data-bs-toggle="modal" data-bs-target="#kt_modal_category" id="btnTambahCategory">
                        <i class="ki-duotone ki-plus fs-2"><span class="path1"></span><span class="path2"></span></i> Tambah Kategori Tes
                    </button>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="table-responsive">
                    <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-4" id="table_category">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 min-w-50px rounded-start">No</th>
                                <th class="min-w-200px">Kategori Tes Fisik</th>
                                <th class="min-w-150px">Cabang Olahraga</th>
                                <th class="min-w-100px text-center">Jml Item Tes</th>
                                <th class="min-w-100px text-center rounded-end">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Kategori Tes -->
<div class="modal fade" id="kt_modal_category" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                <div class="jenis-tes-context-card mb-6 mt-4">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                        <div>
                            <span class="badge badge-light-primary fw-bold mb-2">Master Data</span>
                            <div class="fw-bold text-gray-900 fs-5" id="modalTitle">Tambah Kategori Tes</div>
                            <div class="text-muted fs-7">Tentukan kategori utama berdasarkan cabang olahraga.</div>
                        </div>
                        <div class="jenis-tes-guide fs-8 text-gray-700">
                            Pilih cabang olahraga yang tepat agar item tes tampil secara akurat di dashboard pembinaan.
                        </div>
                    </div>
                </div>

                <div class="jenis-tes-form-card mb-8">
                    <form id="formCategory" class="form">
                        @csrf
                        <input type="hidden" id="category_id" name="id">

                        <div class="d-flex flex-column mb-2">
                            <div class="row g-9 mb-6">
                                <div class="col-md-12 fv-row">
                                    <label class="required fs-6 fw-bold text-gray-700 mb-2">Cabang Olahraga (Cabor)</label>
                                    <select class="form-select form-select-solid fw-semibold" name="cabor_id" id="cabor_id" data-control="select2" data-placeholder="Pilih Cabor" data-dropdown-parent="#kt_modal_category">
                                        <option></option>
                                        @foreach($cabors as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="cabor_idError"></div>
                                    <div class="jenis-tes-helper">Kategori tes akan dikelompokkan berdasarkan cabor yang dipilih.</div>
                                </div>
                            </div>

                            <div class="row g-9">
                                <div class="col-md-12 fv-row">
                                    <label class="required fs-6 fw-bold text-gray-700 mb-2">Nama Kategori Fisik</label>
                                    <input type="text" class="form-control form-control-solid fw-semibold" placeholder="Contoh: Endurance (25%), Kekuatan Otot (15%)" name="name" id="name" />
                                    <div class="invalid-feedback" id="nameError"></div>
                                    <div class="jenis-tes-helper">Masukkan nama kategori tes fisik. Anda bisa menambahkan bobot rasio di dalam kurung jika diperlukan.</div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center pt-8">
                            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" id="btnSubmitCategory" class="btn btn-primary fw-bold">
                                <span class="indicator-label"><i class="ki-duotone ki-check fs-2"></i> Simpan Kategori</span>
                                <span class="indicator-progress">Menyimpan... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Manage Items -->
<div class="modal fade" id="kt_modal_manage_items" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:1000px">
        <div class="modal-content rounded-4 shadow-lg border-0">

            <!-- Header -->
            <div class="modal-header border-0 px-10 pt-8 pb-0">
                <div>
                    <h3 class="fw-bolder text-gray-900 mb-1" id="manageTitle">Manajemen Item Tes</h3>
                    <p class="text-muted fs-7 mb-0">Tambahkan dan kelola item tes fisik untuk kategori ini.</p>
                </div>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <!-- Body -->
            <div class="modal-body px-10 py-8">

                {{-- Context Info Bar --}}
                <div class="jenis-tes-context-card mb-7">
                    <div class="row align-items-center g-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center gap-3">
                                <span class="bg-primary bg-opacity-10 p-3 rounded-2">
                                    <i class="ki-duotone ki-category fs-2 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                </span>
                                <div>
                                    <div class="jenis-tes-section-label">Kategori Aktif</div>
                                    <div class="fw-bold text-gray-900 fs-5 lh-sm" id="manageCategoryLabel">-</div>
                                    <div class="text-muted fs-8 mt-1">Lanjutkan ke kriteria penilaian dari ikon grafik di tabel bawah.</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="jenis-tes-guide fs-7">
                                <i class="fas fa-lightbulb text-warning me-2"></i>
                                <strong>Tips:</strong> Gunakan nama item yang spesifik seperti <em>"6 Minute Walk Test"</em> agar tidak terjadi salah pilih saat pencatatan hasil performa atlet.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Tambah Item --}}
                <div class="jenis-tes-section-label mb-3">Tambah Item Tes Baru</div>
                <div class="jenis-tes-form-card mb-7">
                    <form id="formItem">
                        @csrf
                        <input type="hidden" id="item_category_id" name="physical_test_category_id">
                        <div class="row g-5 align-items-start">
                            <div class="col-xl-5 col-lg-5 col-md-12 fv-row">
                                <label class="required fs-7 fw-bold text-gray-700 mb-2">Nama Item Tes</label>
                                <input type="text" name="name" id="item_name" class="form-control form-control-solid form-control-lg" placeholder="Contoh: 6 Minute Walk Test">
                                <div class="jenis-tes-helper">Nama ini akan muncul saat pengisian performa atlet di lapangan.</div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-8 fv-row">
                                <label class="fs-7 fw-bold text-gray-700 mb-2">Sasaran Disabilitas <span class="text-muted fw-normal">(Opsional)</span></label>
                                <select class="form-select form-select-solid form-select-lg" name="jenis_disabilitas_id" id="item_sasaran" data-control="select2" data-placeholder="Semua Jenis Disabilitas" data-allow-clear="true" data-dropdown-parent="#kt_modal_manage_items">
                                    <option></option>
                                    @foreach($disabilitas as $d)
                                    <option value="{{ $d->id }}">{{ $d->nama_jenis }}</option>
                                    @endforeach
                                </select>
                                <div class="jenis-tes-helper">Kosongkan jika item berlaku untuk semua jenis disabilitas.</div>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-4 fv-row">
                                <label class="fs-7 fw-bold text-gray-700 mb-2">Satuan</label>
                                <input type="text" name="satuan" id="item_satuan" class="form-control form-control-solid form-control-lg" placeholder="meter">
                                <div class="jenis-tes-helper">mis. meter, detik, rep</div>
                            </div>
                            <div class="col-xl-1 col-lg-1 col-md-12 d-flex align-items-center" style="padding-top: 26px;">
                                <button type="submit" id="btnSubmitItem" class="btn btn-primary btn-lg w-100 fw-bold" title="Tambah Item">
                                    <i class="ki-duotone ki-plus fs-1"><span class="path1"></span><span class="path2"></span></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Section header for table --}}
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="jenis-tes-section-label mb-0">Daftar Item Tes dalam Kategori Ini</div>
                    <span class="badge badge-light-info fw-semibold fs-8">
                        <i class="ki-duotone ki-chart-simple fs-7 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Klik ikon grafik untuk atur kriteria penilaian
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-row-dashed table-row-gray-200 align-middle gx-4 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 w-50px rounded-start">#</th>
                                <th>Nama Item Tes</th>
                                <th>Sasaran Disabilitas</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-end pe-4 rounded-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="itemList">
                            <!-- JS content -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal Manage Scores -->
<div class="modal fade" id="kt_modal_manage_scores" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 900px;">
        <div class="modal-content rounded-4 shadow-lg border-0">

            <!-- Header -->
            <div class="modal-header border-0 px-10 pt-8 pb-0">
                <div>
                    <h3 class="fw-bolder text-gray-900 mb-1">Kriteria Penilaian</h3>
                    <p class="text-muted fs-7 mb-0">Item Tes: <span class="fw-bold text-primary" id="itemLabel">-</span></p>
                </div>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <!-- Body -->
            <div class="modal-body px-10 py-8">

                {{-- Context + Legend --}}
                <div class="jenis-tes-context-card mb-7">
                    <div class="row align-items-start g-4">
                        <div class="col-md-5">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <span class="bg-success bg-opacity-10 p-3 rounded-2">
                                    <i class="ki-duotone ki-chart-simple fs-2 text-success"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                </span>
                                <div>
                                    <div class="jenis-tes-section-label">Item Tes Aktif</div>
                                    <div class="fw-bold text-gray-900 fs-6" id="scoreItemContext">-</div>
                                </div>
                            </div>
                            <div class="text-muted fs-7 ps-1">
                                Pemetaan rentang nilai menentukan label dan skor atlet secara otomatis saat input performa.<br>
                                <span class="text-gray-500 fs-8 mt-1 d-block"><i class="fas fa-info-circle me-1"></i>Nilai Min saja = ≥ Min. Nilai Max saja = ≤ Max. Keduanya = rentang antara.</span>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="score-legend-box">
                                <div class="jenis-tes-section-label mb-3">Contoh Pemetaan Nilai (6 Minute Walk)</div>
                                <div class="d-flex flex-column gap-2">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <span class="score-legend-badge">≥ 550m</span>
                                        <i class="fas fa-long-arrow-alt-right text-muted fs-8"></i>
                                        <span class="badge badge-light-success fw-semibold px-3">Sangat Baik</span>
                                        <span class="badge badge-success">Skor 4</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <span class="score-legend-badge">400 – 549m</span>
                                        <i class="fas fa-long-arrow-alt-right text-muted fs-8"></i>
                                        <span class="badge badge-light-primary fw-semibold px-3">Baik</span>
                                        <span class="badge badge-primary">Skor 3</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <span class="score-legend-badge">300 – 399m</span>
                                        <i class="fas fa-long-arrow-alt-right text-muted fs-8"></i>
                                        <span class="badge badge-light-warning fw-semibold px-3">Cukup</span>
                                        <span class="badge badge-warning">Skor 2</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <span class="score-legend-badge">≤ 299m</span>
                                        <i class="fas fa-long-arrow-alt-right text-muted fs-8"></i>
                                        <span class="badge badge-light-danger fw-semibold px-3">Kurang</span>
                                        <span class="badge badge-danger">Skor 1</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Tambah Kriteria --}}
                <div class="jenis-tes-section-label mb-3">Tambah Kriteria Penilaian Baru</div>
                <div class="jenis-tes-form-card mb-7">
                    <form id="formScore">
                        @csrf
                        <input type="hidden" id="score_item_id" name="physical_test_item_id">
                        <div class="row g-5 align-items-start">
                            <div class="col-lg-2 col-md-6 fv-row">
                                <label class="fs-7 fw-bold text-gray-700 mb-2">Nilai Min</label>
                                <input type="number" step="any" name="min_value" id="score_min" class="form-control form-control-solid form-control-lg" placeholder="0">
                                <div class="jenis-tes-helper">Opsional. Kosong = tanpa batas bawah.</div>
                            </div>
                            <div class="col-lg-2 col-md-6 fv-row">
                                <label class="fs-7 fw-bold text-gray-700 mb-2">Nilai Max</label>
                                <input type="number" step="any" name="max_value" id="score_max" class="form-control form-control-solid form-control-lg" placeholder="99">
                                <div class="jenis-tes-helper">Opsional. Kosong = tanpa batas atas.</div>
                            </div>
                            <div class="col-lg-4 col-md-6 fv-row">
                                <label class="required fs-7 fw-bold text-gray-700 mb-2">Kriteria / Label</label>
                                <input type="text" name="label" id="score_label" class="form-control form-control-solid form-control-lg" placeholder="Sangat Baik / Baik / Cukup / Kurang">
                                <div class="jenis-tes-helper">Label yang tampil di laporan performa atlet.</div>
                            </div>
                            <div class="col-lg-2 col-md-6 fv-row">
                                <label class="required fs-7 fw-bold text-gray-700 mb-2">Skor</label>
                                <input type="number" name="score" id="score_value" class="form-control form-control-solid form-control-lg" placeholder="4">
                                <div class="jenis-tes-helper">Angka untuk kalkulasi (mis. 1–4).</div>
                            </div>
                            <div class="col-lg-2 col-md-12 d-flex align-items-center" style="padding-top:26px">
                                <button type="submit" id="btnSubmitScore" class="btn btn-primary btn-lg w-100 fw-bold">
                                    <i class="ki-duotone ki-plus fs-1"><span class="path1"></span><span class="path2"></span></i> Tambah
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Table --}}
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="jenis-tes-section-label mb-0">Daftar Kriteria Penilaian</div>
                    <span class="text-muted fs-8"><i class="fas fa-exclamation-circle me-1 text-warning"></i>Pastikan rentang nilai tidak saling tumpang tindih.</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-row-dashed table-row-gray-200 align-middle gx-4 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 rounded-start">Rentang Nilai</th>
                                <th>Label / Kriteria</th>
                                <th class="text-center">Skor</th>
                                <th class="text-end pe-4 rounded-end">Hapus</th>
                            </tr>
                        </thead>
                        <tbody id="scoreList">
                            <!-- JS content -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal Detail Kategori Tes -->
<div class="modal fade" id="kt_modal_detail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 820px;">
        <div class="modal-content rounded-4 shadow-lg border-0">

            <!-- Header -->
            <div class="modal-header border-0 px-8 pt-8 pb-4" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); border-radius: 16px 16px 0 0;">
                <div class="d-flex align-items-center gap-4">
                    <span class="bg-white bg-opacity-10 p-3 rounded-3">
                        <i class="ki-duotone ki-graph-3 fs-1 text-white opacity-75"><span class="path1"></span><span class="path2"></span></i>
                    </span>
                    <div>
                        <div class="jenis-tes-section-label text-white opacity-60 mb-0">Detail Komponen Tes</div>
                        <h4 class="fw-bolder text-white mb-0" id="detailCategoryName">-</h4>
                        <span class="badge badge-light-primary fw-semibold mt-1" id="detailCaborBadge">-</span>
                    </div>
                </div>
                <div class="btn btn-sm btn-icon btn-active-light-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1 text-white opacity-75"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <!-- Body -->
            <div class="modal-body px-8 py-7">

                <!-- Summary bar -->
                <div class="d-flex align-items-center gap-3 mb-6 flex-wrap">
                    <div class="d-flex align-items-center gap-2 px-4 py-2 rounded-3" style="background:#f0f4ff;">
                        <i class="ki-duotone ki-element-plus fs-4 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                        <span class="fw-bold text-primary fs-7" id="detailItemCount">0 Item Tes</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 px-4 py-2 rounded-3" style="background:#fff8dd;">
                        <i class="ki-duotone ki-star fs-4 text-warning"><span class="path1"></span><span class="path2"></span></i>
                        <span class="fw-bold text-warning fs-7" id="detailScoreCount">0 Kriteria Penilaian</span>
                    </div>
                    <span class="ms-auto text-muted fs-8">Geser ke bawah untuk melihat semua item.</span>
                </div>

                <!-- Item list -->
                <div id="detailItemsContainer" class="d-flex flex-column gap-4">
                    <!-- JS will inject here -->
                </div>

                <!-- Empty state -->
                <div id="detailEmptyState" class="text-center py-14 d-none">
                    <i class="ki-duotone ki-information-5 fs-5hx text-muted opacity-40"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    <div class="fw-bold text-gray-500 fs-5 mt-4">Belum ada item tes</div>
                    <div class="text-muted fs-7 mt-1">Tambahkan item tes melalui tombol Kelola Item Tes.</div>
                </div>

            </div>

            <!-- Footer -->
            <div class="modal-footer border-0 px-8 pt-0 pb-7">
                <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    window.routes = {
        index: "{{ route('master.jenis-tes.index') }}",
        store: "{{ route('master.jenis-tes.store') }}",
        getItems: "{{ url('master/jenis-tes/items') }}",
        storeItem: "{{ route('master.jenis-tes.store-item') }}",
        deleteItem: "{{ url('master/jenis-tes/item-delete') }}",
        getScores: "{{ url('master/jenis-tes/scores') }}",
        storeScore: "{{ route('master.jenis-tes.store-score') }}",
        deleteScore: "{{ url('master/jenis-tes/score-delete') }}",
        getDetail: "{{ url('master/jenis-tes/detail') }}"
    };
</script>
<script src="{{ asset('js/pages/master/jenis_tes.js') }}"></script>
@endsection