@extends('layouts.main_layout')

@section('content')
<div class="row g-5 g-xl-8">
    <div class="col-xl-12">
        <div class="card card-xl-stretch mb-5 mb-xl-8 border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 text-gray-900">Data Pelatih</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Manajemen biodata lengkap dan dokumen pendukung kepelatihan</span>
                </h3>
                <div class="card-toolbar">
                    <!--begin::Action-->
                    <a href="#" class="btn btn-primary" id="btnTambahPelatih" data-bs-toggle="modal" data-bs-target="#kt_modal_coach">
                        <i class="ki-duotone ki-plus fs-2"></i>Tambah Pelatih
                    </a>
                    <!--end::Action-->
                </div>
            </div>
            <div class="card-body py-4">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="table_pelatihan">
                    <thead>
                        <tr class="fw-bold text-muted bg-light">
                            <th class="ps-4 min-w-50px rounded-start">No</th>
                            <th class="min-w-200px">Nama Pelatih</th>
                            <th class="min-w-150px">Cabor</th>
                            <th class="min-w-150px">NIK</th>
                            <th class="min-w-100px">L/P</th>
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

<!--begin::Modal - Add/Edit Coach-->
<div class="modal fade" id="kt_modal_coach" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-800px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_coach_header">
                <h2 class="fw-bold" id="modalTitle">Tambah Data Pelatih</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <form id="formCoach" class="form" action="#">
                    @csrf
                    <input type="hidden" name="coach_id" id="coach_id">

                    <!--begin::Navs-->
                    <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_tab_akun_pribadi">Akun & Pribadi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_tab_dokumen">Upload Dokumen</a>
                        </li>
                    </ul>
                    <!--end::Navs-->

                    <!--begin::Tab content-->
                    <div class="tab-content" id="myTabContent">

                        <!-- TAB 1: AKUN PRIBADI -->
                        <div class="tab-pane fade show active" id="kt_tab_akun_pribadi" role="tabpanel">

                            <div class="mb-13 text-center">
                                <h1 class="mb-3 text-gray-900 fw-bolder" id="modalTitle">Tambah Data Pelatih</h1>
                                <div class="text-muted fw-semibold fs-5">Lengkapi formulir biodata pelatih di bawah ini berdasarkan KTP</div>
                            </div>

                            <!-- Informasi Akun Section -->
                            <div class="d-flex flex-column mb-8">
                                <h4 class="text-gray-900 fw-bolder mb-3">1. Informasi Akun Login</h4>
                                <div class="separator mb-6"></div>

                                <div class="row g-9 mb-8">
                                    <div class="col-md-6 fv-row">
                                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                            <span>Username Login</span>
                                        </label>
                                        <input type="text" class="form-control form-control-solid fw-semibold" placeholder="Opsional (Otomatis menggunakan NIK jika dikosongkan)" name="username" id="username" />
                                        <div class="invalid-feedback" id="usernameError"></div>
                                        <div class="text-muted fs-8 mt-2">Dianjurkan diisi dengan format <b class="text-primary">nama_pelatih</b> tanpa spasi.</div>
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                            <span>Password</span>
                                        </label>
                                        <input type="password" class="form-control form-control-solid fw-semibold" placeholder="Opsional (Otomatis menggunakan NIK jika dikosongkan)" name="password" id="password" />
                                        <div class="invalid-feedback" id="passwordError"></div>
                                        <div class="text-warning fs-8 mt-2 document_hint" style="display: none;"><i class="ki-duotone ki-information-5 text-warning fs-6"></i> Biarkan kosong jika tidak ingin mengubah password lama.</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Pekerjaan Section -->
                            <div class="d-flex flex-column mb-8">
                                <h4 class="text-gray-900 fw-bolder mb-3">2. Informasi Pekerjaan</h4>
                                <div class="separator mb-6"></div>

                                <div class="row g-9 mb-8">
                                    <div class="col-md-12 fv-row">
                                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                            <span class="required">Cabang Olahraga Induk</span>
                                        </label>
                                        <select class="form-select form-select-solid fw-semibold" data-control="select2" data-hide-search="true" data-placeholder="Pilih Cabang Olahraga..." name="cabor_id" id="cabor_id">
                                            <option value=""></option>
                                            @foreach($cabors as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback" id="cabor_idError"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Identitas Pribadi Section -->
                            <div class="d-flex flex-column mb-8">
                                <h4 class="text-gray-900 fw-bolder mb-3">3. Identitas Pribadi (Sesuai KTP)</h4>
                                <div class="separator mb-6"></div>

                                <div class="row g-9 mb-8">
                                    <div class="col-md-6 fv-row">
                                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                            <span class="required">Nama Lengkap</span>
                                        </label>
                                        <input type="text" class="form-control form-control-solid fw-semibold" placeholder="Masukkan nama" name="name" id="name" />
                                        <div class="invalid-feedback" id="nameError"></div>
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                            <span class="required">Nomor Induk Kependudukan (NIK)</span>
                                        </label>
                                        <input type="text" class="form-control form-control-solid fw-semibold" placeholder="Contoh: 3201xxx" name="nik" id="nik" />
                                        <div class="invalid-feedback" id="nikError"></div>
                                    </div>
                                </div>

                                <div class="row g-9 mb-8">
                                    <div class="col-md-6 fv-row">
                                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                            <span class="required">Tempat Lahir</span>
                                        </label>
                                        <input type="text" class="form-control form-control-solid fw-semibold" placeholder="Contoh: Jakarta" name="birth_place" id="birth_place" />
                                        <div class="invalid-feedback" id="birth_placeError"></div>
                                    </div>
                                    <div class="col-md-6 fv-row">
                                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                            <span class="required">Tanggal Lahir</span>
                                        </label>
                                        <input class="form-control form-control-solid fw-semibold" placeholder="Pilih Tanggal Lahir" name="birth_date" id="birth_date" />
                                        <div class="invalid-feedback" id="birth_dateError"></div>
                                    </div>
                                </div>

                                <div class="row g-9 mb-8">
                                    <div class="col-md-4 fv-row">
                                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                            <span class="required">Agama</span>
                                        </label>
                                        <select class="form-select form-select-solid fw-semibold" data-control="select2" data-hide-search="true" data-placeholder="Pilih Agama" name="religion" id="religion">
                                            <option value=""></option>
                                            <option value="Islam">Islam</option>
                                            <option value="Kristen">Kristen</option>
                                            <option value="Katolik">Katolik</option>
                                            <option value="Hindu">Hindu</option>
                                            <option value="Buddha">Buddha</option>
                                            <option value="Konghucu">Konghucu</option>
                                        </select>
                                        <div class="invalid-feedback" id="religionError"></div>
                                    </div>
                                    <div class="col-md-4 fv-row">
                                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                            <span class="required">Jenis Kelamin</span>
                                        </label>
                                        <select class="form-select form-select-solid fw-semibold" data-control="select2" data-hide-search="true" data-placeholder="Pilih Kelamin" name="gender" id="gender">
                                            <option value=""></option>
                                            <option value="L">Laki-Laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                        <div class="invalid-feedback" id="genderError"></div>
                                    </div>
                                    <div class="col-md-4 fv-row">
                                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                            <span>Golongan Darah</span>
                                        </label>
                                        <select class="form-select form-select-solid fw-semibold" data-control="select2" data-hide-search="true" data-placeholder="Gol. Darah" name="blood_type" id="blood_type">
                                            <option value=""></option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="AB">AB</option>
                                            <option value="O">O</option>
                                        </select>
                                        <div class="invalid-feedback" id="blood_typeError"></div>
                                    </div>
                                </div>

                                <div class="row g-9 mb-8">
                                    <div class="col-md-12 fv-row">
                                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                            <span class="required">Alamat Tempat Tinggal Pribadi</span>
                                        </label>
                                        <textarea class="form-control form-control-solid fw-semibold" rows="3" placeholder="Sesuai KTP" name="address" id="address"></textarea>
                                        <div class="invalid-feedback" id="addressError"></div>
                                    </div>
                                </div>

                                <div class="row g-9">
                                    <div class="col-md-12 fv-row">
                                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                            <span class="required">Riwayat Pendidikan Terakhir</span>
                                        </label>
                                        <input type="text" class="form-control form-control-solid fw-semibold" placeholder="Contoh: S1 Pendidikan Olahraga" name="last_education" id="last_education" />
                                        <div class="invalid-feedback" id="last_educationError"></div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- TAB 2: UPLOAD DOKUMEN -->
                        <div class="tab-pane fade" id="kt_tab_dokumen" role="tabpanel">
                            <!-- Dokumen Pendukung Section -->
                            <div class="d-flex flex-column mb-8">
                                <h4 class="text-gray-900 fw-bolder mb-3">4. Dokumen Pendukung File</h4>
                                <div class="separator mb-6"></div>

                                <div class="alert alert-primary d-flex align-items-center p-4 mb-8">
                                    <i class="ki-duotone ki-information-4 fs-2hx text-primary me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    <div class="d-flex flex-column">
                                        <h4 class="mb-1 text-primary">Informasi Unggahan Dokumen</h4>
                                        <span>Disarankan agar dokumen KTP, NPWP, SK, dan Sertifikat Lisensi diunggah dalam format PDF yang rapi untuk mempermudah pengecekan oleh sistem administrasi. Maks ukuran lampiran hingga 5MB.</span>
                                    </div>
                                </div>

                                <div class="row g-9 mb-8">
                                    <div class="col-md-12 fv-row">
                                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                            <span>a. Foto Resmi Terkini</span>
                                        </label>
                                        <input type="file" class="form-control form-control-solid fw-semibold" name="photo" id="photo" accept=".png,.jpg,.jpeg" />
                                        <div class="invalid-feedback" id="photoError"></div>
                                        <div class="text-warning fs-8 mt-2 document_hint" style="display: none;"><i class="ki-duotone ki-information-5 text-warning fs-6"></i> Biarkan kosong jika tidak ingin mengganti file.</div>
                                        <div class="mt-1" id="photo_viewer"></div>
                                    </div>
                                </div>

                                <div class="row g-9 mb-8">
                                    <div class="col-md-12 fv-row">
                                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                            <span>b. Scan / Foto KTP Asli</span>
                                        </label>
                                        <input type="file" class="form-control form-control-solid fw-semibold" name="ktp" id="ktp" accept=".png,.jpg,.jpeg,.pdf" />
                                        <div class="invalid-feedback" id="ktpError"></div>
                                        <div class="text-warning fs-8 mt-2 document_hint" style="display: none;"><i class="ki-duotone ki-information-5 text-warning fs-6"></i> Biarkan kosong jika tidak ingin mengganti file.</div>
                                        <div class="mt-1" id="ktp_viewer"></div>
                                    </div>
                                </div>

                                <div class="row g-9 mb-8">
                                    <div class="col-md-12 fv-row">
                                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                            <span>c. Sertifikat Kepelatihan Tertinggi</span>
                                        </label>
                                        <input type="file" class="form-control form-control-solid fw-semibold" name="certificate" id="certificate" accept=".png,.jpg,.jpeg,.pdf" />
                                        <div class="invalid-feedback" id="certificateError"></div>
                                        <div class="text-warning fs-8 mt-2 document_hint" style="display: none;"><i class="ki-duotone ki-information-5 text-warning fs-6"></i> Biarkan kosong jika tidak ingin mengganti file.</div>
                                        <div class="mt-1" id="certificate_viewer"></div>
                                    </div>
                                </div>

                                <div class="row g-9 mb-8">
                                    <div class="col-md-12 fv-row">
                                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                            <span>d. Scan / Foto NPWP</span>
                                        </label>
                                        <input type="file" class="form-control form-control-solid fw-semibold" name="npwp" id="npwp" accept=".png,.jpg,.jpeg,.pdf" />
                                        <div class="invalid-feedback" id="npwpError"></div>
                                        <div class="text-warning fs-8 mt-2 document_hint" style="display: none;"><i class="ki-duotone ki-information-5 text-warning fs-6"></i> Biarkan kosong jika tidak ingin mengganti file.</div>
                                        <div class="mt-1" id="npwp_viewer"></div>
                                    </div>
                                </div>

                                <div class="row g-9">
                                    <div class="col-md-12 fv-row">
                                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                                            <span>e. SK Pengangkatan Instansi/Klub</span>
                                        </label>
                                        <input type="file" class="form-control form-control-solid fw-semibold" name="sk" id="sk" accept=".png,.jpg,.jpeg,.pdf" />
                                        <div class="invalid-feedback" id="skError"></div>
                                        <div class="text-warning fs-8 mt-2 document_hint" style="display: none;"><i class="ki-duotone ki-information-5 text-warning fs-6"></i> Biarkan kosong jika tidak ingin mengganti file.</div>
                                        <div class="mt-1" id="sk_viewer"></div>
                                        <div class="text-muted fs-7 mt-3 border-top pt-3">Format PDF, JPG, JPEG, PNG. Max: <b class="text-primary">5MB/file</b>.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end tab pane documents-->
                    </div>
                    <!--end tab content-->

                    <div class="text-center pt-5">
                        <button type="reset" id="btnReset" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" id="btnSubmit" class="btn btn-primary">
                            <span class="indicator-label">Simpan Data Pelatih</span>
                            <span class="indicator-progress">Memproses kelengkapan data...
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
        coachIndex: "{{ route('master.coach.index') }}",
        coachStore: "{{ route('master.coach.store') }}"
    };
    window.baseUrl = "{{ url('/') }}";
    var csrf_token = "{{ csrf_token() }}";
</script>
<script src="{{ asset('js/pages/master/coach.js') }}"></script>
@endsection