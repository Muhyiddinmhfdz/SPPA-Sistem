@extends('layouts.main_layout')

@section('content')
<div class="row g-5 g-xl-8">
    <div class="col-xl-12">
        <div class="card card-xl-stretch mb-5 mb-xl-8 border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 text-gray-900">Data Cabang Olahraga</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Manajemen master data cabang olahraga</span>
                </h3>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-light-primary fw-bold" data-bs-toggle="modal" data-bs-target="#kt_modal_cabor" id="btnTambahCabor">
                        <i class="ki-duotone ki-plus fs-2"><span class="path1"></span><span class="path2"></span></i> Tambah Cabang Olahraga
                    </button>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="table-responsive">
                    <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3" id="table_cabor">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 min-w-50px rounded-start">No</th>
                                <th class="min-w-200px">Nama Cabor</th>
                                <th class="min-w-150px">Ketua</th>
                                <th class="min-w-150px">Kontak Tlp.</th>
                                <th class="min-w-100px">File SK</th>
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
</div>

<!-- Modal Cabor -->
<div class="modal fade" id="kt_modal_cabor" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-800px">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                <form id="formCabor" class="form" action="#" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="cabor_id" name="id">
                    <div class="mb-13 text-center">
                        <h1 class="mb-3 text-gray-900 fw-bolder" id="modalTitle">Tambah Cabang Olahraga</h1>
                        <div class="text-muted fw-semibold fs-5">Lengkapi formulir profil cabang olahraga di bawah ini</div>
                    </div>

                    <!-- Profil Umum Section -->
                    <div class="d-flex flex-column mb-8">
                        <h4 class="text-gray-900 fw-bolder mb-3">1. Profil Utama</h4>
                        <div class="separator mb-6"></div>
                        <div class="row g-9 mb-8">
                            <div class="col-md-12 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                    <span class="required">Nama Cabang Olahraga</span>
                                </label>
                                <input type="text" class="form-control form-control-solid fw-semibold" placeholder="Contoh: PBSI, PSSI, dsb" name="name" id="name" />
                                <div class="invalid-feedback" id="nameError"></div>
                            </div>
                        </div>

                        <div class="row g-9 mb-8">
                            <div class="col-md-12 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                    <span>Masa Bakti Kepengurusan (Mulai s/d Akhir)</span>
                                </label>
                                <div class="d-flex align-items-center gap-3">
                                    <input class="form-control form-control-solid fw-semibold" name="sk_start_date" id="sk_start_date" placeholder="Pilih Tanggal Mulai" />
                                    <span class="fs-5 fw-bold text-gray-400">s/d</span>
                                    <input class="form-control form-control-solid fw-semibold" name="sk_end_date" id="sk_end_date" placeholder="Pilih Tanggal Akhir" />
                                </div>
                                <div class="invalid-feedback d-block" id="sk_start_dateError"></div>
                                <div class="invalid-feedback d-block" id="sk_end_dateError"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Kepengurusan Section -->
                    <div class="d-flex flex-column mb-8">
                        <h4 class="text-gray-900 fw-bolder mb-3">2. Susunan Pengurus Inti</h4>
                        <div class="separator mb-6"></div>
                        <div class="row g-9">
                            <div class="col-md-4 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                    <span>Nama Ketua</span>
                                </label>
                                <input type="text" class="form-control form-control-solid fw-semibold" placeholder="Nama Ketua" name="chairman_name" id="chairman_name" />
                                <div class="invalid-feedback" id="chairman_nameError"></div>
                            </div>
                            <div class="col-md-4 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                    <span>Nama Sekretaris</span>
                                </label>
                                <input type="text" class="form-control form-control-solid fw-semibold" placeholder="Nama Sekretaris" name="secretary_name" id="secretary_name" />
                                <div class="invalid-feedback" id="secretary_nameError"></div>
                            </div>
                            <div class="col-md-4 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                    <span>Nama Bendahara</span>
                                </label>
                                <input type="text" class="form-control form-control-solid fw-semibold" placeholder="Nama Bendahara" name="treasurer_name" id="treasurer_name" />
                                <div class="invalid-feedback" id="treasurer_nameError"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Kontak & Alamat Section -->
                    <div class="d-flex flex-column mb-8">
                        <h4 class="text-gray-900 fw-bolder mb-3">3. Informasi Kontak & Domisili</h4>
                        <div class="separator mb-6"></div>
                        <div class="row g-9 mb-8">
                            <div class="col-md-12 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                    <span>Alamat Sekretariatan Lengkap</span>
                                </label>
                                <textarea class="form-control form-control-solid fw-semibold" rows="3" placeholder="Masukkan alamat lengkap sekretariatan cabang olahraga" name="secretariat_address" id="secretariat_address"></textarea>
                                <div class="invalid-feedback" id="secretariat_addressError"></div>
                            </div>
                        </div>
                        <div class="row g-9">
                            <div class="col-md-4 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                    <span>Nomor Tlp/WA Aktif</span>
                                </label>
                                <input type="text" class="form-control form-control-solid fw-semibold" placeholder="08xxxx" name="phone_number" id="phone_number" />
                                <div class="invalid-feedback" id="phone_numberError"></div>
                            </div>
                            <div class="col-md-4 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                    <span>Alamat Email</span>
                                </label>
                                <input type="email" class="form-control form-control-solid fw-semibold" placeholder="email@domain.com" name="email" id="email" />
                                <div class="invalid-feedback" id="emailError"></div>
                            </div>
                            <div class="col-md-4 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                    <span>NPWP Organisasi</span>
                                </label>
                                <input type="text" class="form-control form-control-solid fw-semibold" placeholder="Nomor NPWP" name="npwp" id="npwp" />
                                <div class="invalid-feedback" id="npwpError"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistik & Dokumen Section -->
                    <div class="d-flex flex-column mb-8">
                        <h4 class="text-gray-900 fw-bolder mb-3">4. Statistik Internal & Dokumen SK</h4>
                        <div class="separator mb-6"></div>
                        <div class="row g-9 mb-8">
                            <div class="col-md-4 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                    <span>Jml Atlet Aktif (Sesuai SK)</span>
                                </label>
                                <input type="number" class="form-control form-control-solid fw-semibold" placeholder="0" name="active_athletes_count" id="active_athletes_count" min="0" />
                                <div class="invalid-feedback" id="active_athletes_countError"></div>
                            </div>
                            <div class="col-md-4 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                    <span>Jml Pelatih Aktif (Sesuai SK)</span>
                                </label>
                                <input type="number" class="form-control form-control-solid fw-semibold" placeholder="0" name="active_coaches_count" id="active_coaches_count" min="0" />
                                <div class="invalid-feedback" id="active_coaches_countError"></div>
                            </div>
                            <div class="col-md-4 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                    <span>Jml Medis Aktif (Sesuai SK)</span>
                                </label>
                                <input type="number" class="form-control form-control-solid fw-semibold" placeholder="0" name="active_medics_count" id="active_medics_count" min="0" />
                                <div class="invalid-feedback" id="active_medics_countError"></div>
                            </div>
                        </div>

                        <div class="row g-9">
                            <div class="col-md-12 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                    <span>Unggah File Surat Keputusan (SK)</span>
                                </label>
                                <input type="file" class="form-control form-control-solid fw-semibold" name="sk_file" id="sk_file" accept=".pdf,.png,.jpg,.jpeg" />
                                <div class="invalid-feedback" id="sk_fileError"></div>
                                <div class="text-muted fs-7 mt-2">Format yang diizinkan: <b class="text-primary">PDF, JPG, JPEG, PNG</b>. Maksimal ukuran: <b class="text-primary">5MB</b>.</div>
                                <div class="text-warning fs-7 mt-2" id="sk_fileHint" style="display: none;"><i class="ki-duotone ki-information-5 text-warning fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> Biarkan kosong jika Anda tidak ingin mengganti file SK sebelumnya.</div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center pt-5">
                        <button type="reset" id="btnReset" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" id="btnSubmit" class="btn btn-primary">
                            <span class="indicator-label">Simpan Data</span>
                            <span class="indicator-progress">Tunggu sebentar...
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
        caborIndex: "{{ route('master.cabor.index') }}",
        caborStore: "{{ route('master.cabor.store') }}"
    };
</script>
<script src="{{ asset('js/pages/master/cabor.js') }}"></script>
@endsection