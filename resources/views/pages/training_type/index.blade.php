@extends('layouts.main_layout')

@section('content')
<div class="row g-5 g-xl-8">
    <div class="col-xl-12">
        <div class="card card-xl-stretch mb-5 mb-xl-8 border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 text-gray-900">Master Jenis Latihan</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Manajemen kategori latihan dan komponen detailnya</span>
                </h3>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-light-primary fw-bold" data-bs-toggle="modal" data-bs-target="#kt_modal_training_type" id="btnTambahType">
                        <i class="ki-duotone ki-plus fs-2"><span class="path1"></span><span class="path2"></span></i> Tambah Jenis Latihan
                    </button>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="table-responsive">
                    <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3" id="table_training_type">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 min-w-50px rounded-start">No</th>
                                <th class="min-w-150px">Nama Jenis Latihan</th>
                                <th class="min-w-150px">Cabang Olahraga</th>
                                <th class="min-w-100px text-center">Jml Komponen</th>
                                <th class="min-w-100px text-center rounded-end">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Jenis Latihan -->
<div class="modal fade" id="kt_modal_training_type" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                <form id="formTrainingType" class="form">
                    @csrf
                    <input type="hidden" id="type_id" name="id">
                    <div class="mb-13 text-center">
                        <h1 class="mb-3 text-gray-900 fw-bolder" id="modalTitle">Tambah Jenis Latihan</h1>
                        <div class="text-muted fw-semibold fs-5">Tentukan kategori latihan untuk cabang olahraga tertentu</div>
                    </div>

                    <div class="d-flex flex-column mb-8">
                        <div class="row g-9 mb-8">
                            <div class="col-md-12 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Cabang Olahraga</label>
                                <select class="form-select form-select-solid fw-semibold" name="cabor_id" id="cabor_id" data-control="select2" data-placeholder="Pilih Cabor" data-dropdown-parent="#kt_modal_training_type">
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
                                <label class="required fs-6 fw-semibold mb-2">Nama Jenis Latihan</label>
                                <input type="text" class="form-control form-control-solid fw-semibold" placeholder="Contoh: Fisik, Teknik, Taktik, dsb" name="name" id="name" />
                                <div class="invalid-feedback" id="nameError"></div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center pt-5">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" id="btnSubmitType" class="btn btn-primary">
                            <span class="indicator-label">Simpan Data</span>
                            <span class="indicator-progress">Tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Manage Components -->
<div class="modal fade" id="kt_modal_manage_components" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-750px">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header pt-7 pb-5 border-0 px-10">
                <h3 class="fw-bold text-gray-900 mb-0" id="manageTitle">Manajemen Komponen Latihan</h3>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body px-10 pb-15">
                <form id="formComponent" class="mb-10">
                    @csrf
                    <input type="hidden" id="comp_training_type_id" name="training_type_id">
                    <div class="d-flex align-items-end gap-3">
                        <div class="flex-grow-1">
                            <label class="required fs-7 fw-bold text-muted mb-2 text-uppercase">Tambah Komponen Baru</label>
                            <input type="text" name="name" id="comp_name" class="form-control form-control-solid" placeholder="Nama komponen (misal: Push Up, Dribbling, dll)">
                        </div>
                        <button type="submit" id="btnSubmitComp" class="btn btn-primary">
                            <i class="ki-duotone ki-plus fs-2"></i> Tambah
                        </button>
                    </div>
                    <div class="invalid-feedback" id="comp_nameError"></div>
                </form>

                <div class="separator separator-dashed mb-8"></div>

                <div class="table-responsive">
                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted fs-7 text-uppercase">
                                <th class="min-w-50px">No</th>
                                <th class="min-w-200px">Nama Komponen</th>
                                <th class="min-w-100px text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="componentList">
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
        index: "{{ route('master.training-type.index') }}",
        store: "{{ route('master.training-type.store') }}",
        getComponents: "{{ url('master/training-type/components') }}",
        storeComponent: "{{ route('master.training-type.store-component') }}",
        deleteComponent: "{{ url('master/training-type/component-delete') }}"
    };
</script>
<script src="{{ asset('js/pages/master/training_type.js') }}"></script>
@endsection