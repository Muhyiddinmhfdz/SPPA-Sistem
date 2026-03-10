@extends('layouts.main_layout')

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
                    <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3" id="table_category">
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
                <form id="formCategory" class="form">
                    @csrf
                    <input type="hidden" id="category_id" name="id">
                    <div class="mb-13 text-center">
                        <h1 class="mb-3 text-gray-900 fw-bolder" id="modalTitle">Tambah Kategori Tes</h1>
                        <div class="text-muted fw-semibold fs-5">Tentukan kategori utama (Contoh: Endurance, Kekuatan Otot)</div>
                    </div>

                    <div class="d-flex flex-column mb-8">
                        <div class="row g-9 mb-8">
                            <div class="col-md-12 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Cabang Olahraga</label>
                                <select class="form-select form-select-solid fw-semibold" name="cabor_id" id="cabor_id" data-control="select2" data-placeholder="Pilih Cabor" data-dropdown-parent="#kt_modal_category">
                                    <option></option>
                                    @foreach($cabors as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="cabor_idError"></div>
                            </div>
                        </div>

                        <div class="row g-9">
                            <div class="col-md-12 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Nama Kategori</label>
                                <input type="text" class="form-control form-control-solid fw-semibold" placeholder="Ketik nama kategori..." name="name" id="name" />
                                <div class="invalid-feedback" id="nameError"></div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center pt-5">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" id="btnSubmitCategory" class="btn btn-primary">
                            <span class="indicator-label">Simpan Data</span>
                            <span class="indicator-progress">Tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Manage Items -->
<div class="modal fade" id="kt_modal_manage_items" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-900px">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header pt-7 pb-5 border-0 px-10">
                <h3 class="fw-bold text-gray-900 mb-0" id="manageTitle">Manajemen Item Tes</h3>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body px-10 pb-15">
                <div class="bg-light rounded p-5 mb-8">
                    <form id="formItem">
                        @csrf
                        <input type="hidden" id="item_category_id" name="physical_test_category_id">
                        <div class="row g-5 align-items-end">
                            <div class="col-md-4 fv-row">
                                <label class="required fs-7 fw-bold text-gray-700 mb-2 text-uppercase">Nama Item Tes</label>
                                <input type="text" name="name" id="item_name" class="form-control form-control-solid" placeholder="Contoh: 6 Minute Walk Test">
                            </div>
                            <div class="col-md-4 fv-row">
                                <label class="fs-7 fw-bold text-gray-700 mb-2 text-uppercase">Sasaran Disabilitas</label>
                                <select class="form-select form-select-solid" name="jenis_disabilitas_id" id="item_sasaran" data-control="select2" data-placeholder="Semua Disabilitas (Opsional)" data-allow-clear="true" data-dropdown-parent="#kt_modal_manage_items">
                                    <option></option>
                                    @foreach($disabilitas as $d)
                                    <option value="{{ $d->id }}">{{ $d->nama_jenis }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 fv-row">
                                <label class="fs-7 fw-bold text-gray-700 mb-2 text-uppercase">Satuan</label>
                                <input type="text" name="satuan" id="item_satuan" class="form-control form-control-solid" placeholder="Misal: meter">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" id="btnSubmitItem" class="btn btn-primary w-100 fw-bold">
                                    <i class="ki-duotone ki-plus fs-2"></i> Tambah
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="separator separator-dashed mb-8"></div>

                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted fs-7 text-uppercase">
                                <th class="min-w-50px">No</th>
                                <th class="min-w-200px">Nama Item</th>
                                <th class="min-w-150px">Sasaran Disabilitas</th>
                                <th class="min-w-100px">Satuan</th>
                                <th class="min-w-150px text-end">Aksi</th>
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
    <div class="modal-dialog modal-dialog-centered mw-850px">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header pt-7 pb-5 border-0 px-10">
                <h3 class="fw-bold text-gray-900 mb-0" id="scoreTitle">Kriteria Penilaian: <span id="itemLabel"></span></h3>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body px-10 pb-15">
                <div class="bg-light rounded p-5 mb-8">
                    <form id="formScore">
                        @csrf
                        <input type="hidden" id="score_item_id" name="physical_test_item_id">
                        <div class="row g-5 align-items-end">
                            <div class="col-md-2 fv-row">
                                <label class="fs-7 fw-bold text-gray-700 mb-2 text-uppercase">Min <i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" title="Kosongkan jika tidak ada batas bawah"></i></label>
                                <input type="number" step="any" name="min_value" class="form-control form-control-solid" placeholder="0">
                            </div>
                            <div class="col-md-2 fv-row">
                                <label class="fs-7 fw-bold text-gray-700 mb-2 text-uppercase">Max <i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" title="Kosongkan jika tidak ada batas atas"></i></label>
                                <input type="number" step="any" name="max_value" class="form-control form-control-solid" placeholder="99">
                            </div>
                            <div class="col-md-4 fv-row">
                                <label class="required fs-7 fw-bold text-gray-700 mb-2 text-uppercase">Kriteria (Label)</label>
                                <input type="text" name="label" class="form-control form-control-solid" placeholder="Misal: Sangat Baik">
                            </div>
                            <div class="col-md-2 fv-row">
                                <label class="required fs-7 fw-bold text-gray-700 mb-2 text-uppercase">Skor (Nilai)</label>
                                <input type="number" name="score" class="form-control form-control-solid" placeholder="4">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" id="btnSubmitScore" class="btn btn-primary w-100 fw-bold">
                                    <i class="ki-duotone ki-plus fs-2"></i> Tambah
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="separator separator-dashed mb-8"></div>

                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted fs-7 text-uppercase">
                                <th class="min-w-150px">Rentang Nilai</th>
                                <th class="min-w-200px">Kriteria</th>
                                <th class="min-w-100px text-center">Skor</th>
                                <th class="min-w-80px text-end">Aksi</th>
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
        deleteScore: "{{ url('master/jenis-tes/score-delete') }}"
    };
</script>
<script src="{{ asset('js/pages/master/jenis_tes.js') }}"></script>
@endsection