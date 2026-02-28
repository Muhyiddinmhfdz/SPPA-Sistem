@extends('layouts.main_layout')

@section('content')
<div class="row g-5 g-xl-8">
    <div class="col-xl-12">
        <div class="card card-xl-stretch mb-5 mb-xl-8 border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 text-gray-900">Data Atlet</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Manajemen master dan biodata Atlet NPCI</span>
                </h3>
                <div class="card-toolbar">
                    <a href="#" class="btn btn-primary" id="btnTambahAtlet" data-bs-toggle="modal" data-bs-target="#kt_modal_atlet">
                        <i class="ki-duotone ki-plus fs-2"></i>Tambah Atlet
                    </a>
                </div>
            </div>
            <div class="card-body py-4">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="table_atlet">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 min-w-50px rounded-start">No</th>
                                <th class="min-w-150px">Nama Lengkap</th>
                                <th class="min-w-100px">Cabor</th>
                                <th class="min-w-100px">Klasifikasi</th>
                                <th class="min-w-100px">Akun User</th>
                                <th class="min-w-100px">NIK</th>
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

<!--begin::Modal - Add/Edit Atlet-->
<div class="modal fade" id="kt_modal_atlet" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_atlet_header">
                <h2 class="fw-bold" id="modalTitle">Tambah Data Atlet</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <form id="formAtlet" class="form" action="#" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="atlet_id" id="atlet_id">

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

                            <h4 class="fw-bold mb-5 text-primary">A. Informasi Akun/Login</h4>
                            <div class="row g-9 mb-8">
                                <div class="col-md-6 fv-row">
                                    <label class="required fs-6 fw-semibold mb-2">Username login</label>
                                    <input type="text" class="form-control form-control-solid" placeholder="Username login" name="username" id="username" />
                                    <div class="invalid-feedback" id="usernameError"></div>
                                </div>
                                <div class="col-md-6 fv-row">
                                    <label class="fs-6 fw-semibold mb-2">Email</label>
                                    <input type="email" class="form-control form-control-solid" placeholder="Email (opsional)" name="email" id="email" />
                                    <div class="invalid-feedback" id="emailError"></div>
                                </div>
                                <div class="col-md-12 fv-row">
                                    <label class="fs-6 fw-semibold mb-2">Sandi (Password)</label>
                                    <input type="password" class="form-control form-control-solid" placeholder="Isi untuk mengubah. (Default 123123123)" name="password" id="password" />
                                    <div class="text-muted fs-7 mt-1">Biarkan kosong jika tidak ingin mengubah password lama. Form baru default 123123123.</div>
                                    <div class="invalid-feedback" id="passwordError"></div>
                                </div>
                            </div>

                            <div class="separator separator-dashed my-8"></div>

                            <h4 class="fw-bold mb-5 text-primary">B. Profil Olahraga</h4>
                            <div class="row g-9 mb-8">
                                <div class="col-md-4 fv-row">
                                    <label class="required fs-6 fw-semibold mb-2">Cabang Olahraga (Cabor)</label>
                                    <select class="form-select form-select-solid" name="cabor_id" id="cabor_id" data-control="select2" data-dropdown-parent="#kt_modal_atlet" data-placeholder="Pilih Cabor">
                                        <option value=""></option>
                                        @foreach ($cabors as $cabor)
                                        <option value="{{ $cabor->id }}">{{ $cabor->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="cabor_idError"></div>
                                </div>
                                <div class="col-md-4 fv-row">
                                    <label class="required fs-6 fw-semibold mb-2">Klasifikasi Disabilitas</label>
                                    <select class="form-select form-select-solid" name="klasifikasi_disabilitas_id" id="klasifikasi_disabilitas_id" data-control="select2" data-dropdown-parent="#kt_modal_atlet" data-placeholder="Pilih Klasifikasi">
                                        <option value=""></option>
                                        @foreach ($klasifikasis as $klas)
                                        <option value="{{ $klas->id }}">{{ $klas->kode_klasifikasi }} - {{ $klas->nama_klasifikasi }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="klasifikasi_disabilitas_idError"></div>
                                </div>
                                <div class="col-md-4 fv-row">
                                    <label class="fs-6 fw-semibold mb-2">Jenis Disabilitas</label>
                                    <input type="text" class="form-control form-control-solid" placeholder="Tuna Netra, dll" name="jenis_disabilitas" id="jenis_disabilitas" />
                                    <div class="invalid-feedback" id="jenis_disabilitasError"></div>
                                </div>
                            </div>

                            <div class="separator separator-dashed my-8"></div>

                            <h4 class="fw-bold mb-5 text-primary">C. Identitas Pribadi</h4>
                            <div class="d-flex flex-column mb-8 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Nama Lengkap (Sesuai KTP)</label>
                                <input type="text" class="form-control form-control-solid" placeholder="Nama Lengkap" name="name" id="name" />
                                <div class="invalid-feedback" id="nameError"></div>
                            </div>

                            <div class="row g-9 mb-8">
                                <div class="col-md-6 fv-row">
                                    <label class="fs-6 fw-semibold mb-2">Tempat Lahir</label>
                                    <input type="text" class="form-control form-control-solid" placeholder="Contoh: Jakarta" name="birth_place" id="birth_place" />
                                    <div class="invalid-feedback" id="birth_placeError"></div>
                                </div>
                                <div class="col-md-6 fv-row">
                                    <label class="fs-6 fw-semibold mb-2">Tanggal Lahir</label>
                                    <input type="date" class="form-control form-control-solid" name="birth_date" id="birth_date" />
                                    <div class="invalid-feedback" id="birth_dateError"></div>
                                </div>
                            </div>

                            <div class="row g-9 mb-8">
                                <div class="col-md-6 fv-row">
                                    <label class="fs-6 fw-semibold mb-2">NIK</label>
                                    <input type="text" class="form-control form-control-solid" placeholder="16 Digit NIK" name="nik" id="nik" maxlength="16" />
                                    <div class="invalid-feedback" id="nikError"></div>
                                </div>
                                <div class="col-md-6 fv-row">
                                    <label class="fs-6 fw-semibold mb-2">Agama</label>
                                    <select class="form-select form-select-solid" name="religion" id="religion">
                                        <option value="">Pilih Agama...</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Kristen">Kristen</option>
                                        <option value="Katolik">Katolik</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Buddha">Buddha</option>
                                        <option value="Konghucu">Konghucu</option>
                                    </select>
                                    <div class="invalid-feedback" id="religionError"></div>
                                </div>
                            </div>

                            <div class="row g-9 mb-8">
                                <div class="col-md-6 fv-row">
                                    <label class="fs-6 fw-semibold mb-2">Jenis Kelamin</label>
                                    <select class="form-select form-select-solid" name="gender" id="gender">
                                        <option value="">Pilih ...</option>
                                        <option value="L">Laki-Laki (L)</option>
                                        <option value="P">Perempuan (P)</option>
                                    </select>
                                    <div class="invalid-feedback" id="genderError"></div>
                                </div>
                                <div class="col-md-6 fv-row">
                                    <label class="fs-6 fw-semibold mb-2">Golongan Darah</label>
                                    <select class="form-select form-select-solid" name="blood_type" id="blood_type">
                                        <option value="">Pilih ...</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="AB">AB</option>
                                        <option value="O">O</option>
                                    </select>
                                    <div class="invalid-feedback" id="blood_typeError"></div>
                                </div>
                            </div>

                            <div class="d-flex flex-column mb-8 fv-row">
                                <label class="fs-6 fw-semibold mb-2">Riwayat Pendidikan (Terakhir)</label>
                                <input type="text" class="form-control form-control-solid" placeholder="Contoh: SMA/S1 Olahraga" name="last_education" id="last_education" />
                                <div class="invalid-feedback" id="last_educationError"></div>
                            </div>

                            <div class="d-flex flex-column mb-8 fv-row">
                                <label class="fs-6 fw-semibold mb-2">Alamat Lengkap KTP</label>
                                <textarea class="form-control form-control-solid" rows="3" name="address" id="address" placeholder="Tulis jalan, RT/RW, kelurahan, kecamatan"></textarea>
                                <div class="invalid-feedback" id="addressError"></div>
                            </div>
                        </div>

                        <!-- TAB 2: UPLOAD DOKUMEN -->
                        <div class="tab-pane fade" id="kt_tab_dokumen" role="tabpanel">

                            <h4 class="fw-bold mb-5 text-primary">D. Berkas Pendukung Atlet</h4>
                            <div class="alert alert-dismissible bg-light-info border border-info d-flex flex-column flex-sm-row w-100 p-5 mb-10">
                                <i class="ki-duotone ki-information fs-2hx text-info me-4 mb-5 mb-sm-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                <div class="d-flex flex-column pe-0 pe-sm-10">
                                    <h5 class="mb-1">Informasi Pengunggah Dokumen</h5>
                                    <span>Seluruh dokumen di bawah wajib diunggah dalam format PDF atau Gambar (JPG/PNG). Ukuran maksimum per file adalah 2 MB.</span>
                                </div>
                            </div>

                            <div class="row g-9 mb-8">
                                <div class="col-md-6 fv-row">
                                    <label class="fs-6 fw-semibold mb-2">File Pas Foto</label>
                                    <input type="file" class="form-control form-control-solid" name="photo_path" id="photo_path" accept=".pdf,.jpg,.jpeg,.png" />
                                    <div class="invalid-feedback" id="photo_pathError"></div>
                                    <div class="mt-1" id="photo_path_viewer"></div>
                                </div>
                                <div class="col-md-6 fv-row">
                                    <label class="fs-6 fw-semibold mb-2">File KTP</label>
                                    <input type="file" class="form-control form-control-solid" name="ktp_path" id="ktp_path" accept=".pdf,.jpg,.jpeg,.png" />
                                    <div class="invalid-feedback" id="ktp_pathError"></div>
                                    <div class="mt-1" id="ktp_path_viewer"></div>
                                </div>
                            </div>

                            <div class="row g-9 mb-8">
                                <div class="col-md-6 fv-row">
                                    <label class="fs-6 fw-semibold mb-2">Sertifikat Prestasi (Tertinggi)</label>
                                    <input type="file" class="form-control form-control-solid" name="achievement_certificate_path" id="achievement_certificate_path" accept=".pdf,.jpg,.jpeg,.png" />
                                    <div class="invalid-feedback" id="achievement_certificate_pathError"></div>
                                    <div class="mt-1" id="achievement_certificate_path_viewer"></div>
                                </div>
                                <div class="col-md-6 fv-row">
                                    <label class="fs-6 fw-semibold mb-2">Dokumen NPWP</label>
                                    <input type="file" class="form-control form-control-solid" name="npwp_path" id="npwp_path" accept=".pdf,.jpg,.jpeg,.png" />
                                    <div class="invalid-feedback" id="npwp_pathError"></div>
                                    <div class="mt-1" id="npwp_path_viewer"></div>
                                </div>
                            </div>

                            <div class="row g-9 mb-8">
                                <div class="col-md-6 fv-row">
                                    <label class="fs-6 fw-semibold mb-2">SK Pengangkatan / Atlet</label>
                                    <input type="file" class="form-control form-control-solid" name="sk_path" id="sk_path" accept=".pdf,.jpg,.jpeg,.png" />
                                    <div class="invalid-feedback" id="sk_pathError"></div>
                                    <div class="mt-1" id="sk_path_viewer"></div>
                                </div>
                                <div class="col-md-6 fv-row">
                                    <label class="fs-6 fw-semibold mb-2">Riwayat / Ijazah Pendidikan Terakhir</label>
                                    <input type="file" class="form-control form-control-solid" name="education_certificate_path" id="education_certificate_path" accept=".pdf,.jpg,.jpeg,.png" />
                                    <div class="invalid-feedback" id="education_certificate_pathError"></div>
                                    <div class="mt-1" id="education_certificate_path_viewer"></div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="text-center pt-5">
                        <button type="reset" id="btnReset" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" id="btnSubmit" class="btn btn-primary">
                            <span class="indicator-label">Simpan Data Atlet</span>
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
        atletIndex: "{{ route('master.atlet.index') }}",
        atletStore: "{{ route('master.atlet.store') }}"
    };
    window.baseUrl = "{{ url('/') }}";
    var csrf_token = "{{ csrf_token() }}";
</script>
<script src="{{ asset('js/pages/master/atlet.js') }}"></script>
@endsection