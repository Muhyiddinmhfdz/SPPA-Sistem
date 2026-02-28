@extends('layouts.main_layout')

@section('content')
<div class="row g-5 g-xl-8">
    <div class="col-xl-12">
        <div class="card card-xl-stretch mb-5 mb-xl-8 border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 text-gray-900">Data Klasifikasi Disabilitas</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Manajemen master klasifikasi disabilitas atlet</span>
                </h3>
                <div class="card-toolbar">
                    <a href="#" class="btn btn-primary" id="btnTambahKlasifikasi" data-bs-toggle="modal" data-bs-target="#kt_modal_klasifikasi">
                        <i class="ki-duotone ki-plus fs-2"></i>Tambah Klasifikasi
                    </a>
                </div>
            </div>
            <div class="card-body py-4">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="table_klasifikasi">
                    <thead>
                        <tr class="fw-bold text-muted bg-light">
                            <th class="ps-4 min-w-50px rounded-start">No</th>
                            <th class="min-w-100px">Kode</th>
                            <th class="min-w-200px">Nama Klasifikasi</th>
                            <th class="min-w-250px">Deskripsi</th>
                            <th class="min-w-100px text-center rounded-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!--begin::Modal - Add/Edit Klasifikasi-->
<div class="modal fade" id="kt_modal_klasifikasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_klasifikasi_header">
                <h2 class="fw-bold" id="modalTitle">Tambah Klasifikasi Disabilitas</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <form id="formKlasifikasi" class="form" action="#">
                    @csrf
                    <input type="hidden" name="klasifikasi_id" id="klasifikasi_id">

                    <div class="d-flex flex-column mb-8 fv-row">
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                            <span class="required">Kode Klasifikasi</span>
                        </label>
                        <input type="text" class="form-control form-control-solid fw-semibold" placeholder="Contoh: T11, F20, dll" name="kode_klasifikasi" id="kode_klasifikasi" />
                        <div class="invalid-feedback" id="kode_klasifikasiError"></div>
                    </div>

                    <div class="d-flex flex-column mb-8 fv-row">
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                            <span class="required">Nama Klasifikasi</span>
                        </label>
                        <input type="text" class="form-control form-control-solid fw-semibold" placeholder="Masukkan nama klasifikasi" name="nama_klasifikasi" id="nama_klasifikasi" />
                        <div class="invalid-feedback" id="nama_klasifikasiError"></div>
                    </div>

                    <div class="d-flex flex-column mb-8 fv-row">
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                            <span>Deskripsi</span>
                        </label>
                        <textarea class="form-control form-control-solid fw-semibold" rows="3" placeholder="Deskripsi/Keterangan mengenai kelas disabilitas ini" name="deskripsi" id="deskripsi"></textarea>
                        <div class="invalid-feedback" id="deskripsiError"></div>
                    </div>

                    <div class="text-center pt-5">
                        <button type="reset" id="btnReset" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" id="btnSubmit" class="btn btn-primary">
                            <span class="indicator-label">Simpan Data</span>
                            <span class="indicator-progress">Memproses...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    window.routes = {
        klasifikasiIndex: "{{ route('master.klasifikasi-disabilitas.index') }}",
        klasifikasiStore: "{{ route('master.klasifikasi-disabilitas.store') }}"
    };
    var csrf_token = "{{ csrf_token() }}";
</script>
<script src="{{ asset('js/pages/master/klasifikasi_disabilitas.js') }}"></script>
@endsection