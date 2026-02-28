@extends('layouts.main_layout')

@section('content')
<div class="row g-5 g-xl-8">
    <div class="col-xl-12">
        <div class="card card-xl-stretch mb-5 mb-xl-8 border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 text-gray-900">Data Jenis Disabilitas</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Master data jenis disabilitas berdasarkan klasifikasi</span>
                </h3>
                <div class="card-toolbar">
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_jenis">
                        <i class="ki-duotone ki-plus fs-2"></i>Tambah Jenis Disabilitas
                    </a>
                </div>
            </div>
            <div class="card-body py-4">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="table_jenis">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 min-w-50px rounded-start">No</th>
                                <th class="min-w-80px">Kode</th>
                                <th class="min-w-200px">Nama Klasifikasi</th>
                                <th class="min-w-200px">Nama Jenis Disabilitas</th>
                                <th class="min-w-200px">Deskripsi</th>
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

<!--begin::Modal - Add/Edit Jenis Disabilitas-->
<div class="modal fade" id="kt_modal_jenis" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold" id="modalTitleJenis">Tambah Jenis Disabilitas</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <form id="formJenis" class="form" action="#">
                    @csrf
                    <input type="hidden" name="jenis_id" id="jenis_id">

                    <div class="d-flex flex-column mb-8 fv-row">
                        <label class="required fs-6 fw-semibold mb-2">Klasifikasi Disabilitas</label>
                        <select class="form-select form-select-solid" name="klasifikasi_disabilitas_id" id="klasifikasi_disabilitas_id"
                            data-control="select2" data-dropdown-parent="#kt_modal_jenis" data-placeholder="Pilih Klasifikasi...">
                            <option value=""></option>
                            @foreach ($klasifikasis as $klas)
                            <option value="{{ $klas->id }}">{{ $klas->kode_klasifikasi }} - {{ $klas->nama_klasifikasi }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="klasifikasi_disabilitas_idError"></div>
                    </div>

                    <div class="d-flex flex-column mb-8 fv-row">
                        <label class="required fs-6 fw-semibold mb-2">Nama Jenis Disabilitas</label>
                        <input type="text" class="form-control form-control-solid" placeholder="Contoh: Tuna Netra Total, Cerebral Palsy, dsb."
                            name="nama_jenis" id="nama_jenis" />
                        <div class="invalid-feedback" id="nama_jenisError"></div>
                    </div>

                    <div class="d-flex flex-column mb-8 fv-row">
                        <label class="fs-6 fw-semibold mb-2">Deskripsi <span class="text-muted fs-7">(Opsional)</span></label>
                        <textarea class="form-control form-control-solid" rows="3" name="deskripsi" id="deskripsi"
                            placeholder="Tuliskan keterangan singkat mengenai jenis disabilitas ini"></textarea>
                        <div class="invalid-feedback" id="deskripsiError"></div>
                    </div>

                    <div class="text-center pt-5">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" id="btnSubmitJenis" class="btn btn-primary">
                            <span class="indicator-label">Simpan</span>
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
        jenisIndex: "{{ route('master.jenis-disabilitas.index') }}",
        jenisStore: "{{ route('master.jenis-disabilitas.store') }}"
    };
    var csrf_token = "{{ csrf_token() }}";
</script>
<script src="{{ asset('js/pages/master/jenis_disabilitas.js') }}"></script>
@endsection