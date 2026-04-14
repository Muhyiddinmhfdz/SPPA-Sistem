<!--begin::Modal - Add/Edit Program Latihan-->
<div class="modal fade" id="kt_modal_pembinaan" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_pembinaan_header">
                <h2 class="fw-bold" id="modalTitle">Tambah Program Latihan</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <form id="formPembinaan" class="form" action="#">
                    @csrf
                    <input type="hidden" name="pembinaan_id" id="pembinaan_id">

                    <!-- Section: Data Atlet -->
                    <div class="mb-10">
                        <div class="d-flex align-items-center mb-5">
                            <i class="ki-duotone ki-user fs-2 me-3 text-primary"><span class="path1"></span><span class="path2"></span></i>
                            <h4 class="fw-bold text-gray-800 mb-0">Data Atlet</h4>
                        </div>
                        <div class="row g-6 mb-6">
                            <div class="col-md-8 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Nama Atlet</label>
                                <select class="form-select form-select-solid" name="atlet_id" id="atlet_id" data-control="select2" data-dropdown-parent="#kt_modal_pembinaan" data-placeholder="Pilih Atlet">
                                    <option value=""></option>
                                    @foreach ($atlets as $atlet)
                                    <option value="{{ $atlet->id }}">{{ $atlet->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="atlet_idError"></div>
                            </div>
                            <div class="col-md-4 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Tanggal Latihan</label>
                                <div class="position-relative d-flex align-items-center">
                                    <i class="ki-duotone ki-calendar-8 fs-2 position-absolute mx-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
                                    <input type="text" class="form-control form-control-solid ps-12" name="tanggal" id="tanggal" placeholder="Pilih Tanggal" />
                                </div>
                                <div class="invalid-feedback" id="tanggalError"></div>
                            </div>
                        </div>
                        <div class="row g-6">
                            <div class="col-lg-4 col-md-6 fv-row">
                                <label class="fs-7 fw-bold text-gray-600 mb-1">Cabor</label>
                                <input type="text" class="form-control form-control-flush fw-bold fs-6" id="display_cabor" readonly disabled />
                            </div>
                            <div class="col-lg-4 col-md-6 fv-row">
                                <label class="fs-7 fw-bold text-gray-600 mb-1">Jenis Disabilitas</label>
                                <input type="text" class="form-control form-control-flush fw-bold fs-6" id="display_jenis_disabilitas" readonly disabled />
                            </div>
                            <div class="col-lg-4 col-md-12 fv-row">
                                <label class="fs-7 fw-bold text-gray-600 mb-1">Klasifikasi Disabilitas</label>
                                <input type="text" class="form-control form-control-flush fw-bold fs-6" id="display_klasifikasi" readonly disabled />
                            </div>
                        </div>
                    </div>

                    <div class="separator separator-dashed my-8"></div>

                    <!-- Section: Detail Latihan -->
                    <div class="mb-10">
                        <div class="d-flex align-items-center mb-5">
                            <i class="ki-duotone ki-setting-2 fs-2 me-3 text-primary"><span class="path1"></span><span class="path2"></span></i>
                            <h4 class="fw-bold text-gray-800 mb-0">Detail Latihan</h4>
                        </div>
                        <div class="row g-9 mb-8">
                            <div class="col-md-6 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Periodesasi Latihan</label>
                                <select class="form-select form-select-solid" name="periodesasi_latihan" id="periodesasi_latihan">
                                    <option value="">Pilih ...</option>
                                    <option value="harian">Harian</option>
                                    <option value="mingguan">Mingguan</option>
                                    <option value="bulanan">Bulanan</option>
                                </select>
                                <div class="invalid-feedback" id="periodesasi_latihanError"></div>
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Intensitas Latihan</label>
                                <select class="form-select form-select-solid" name="intensitas_latihan" id="intensitas_latihan">
                                    <option value="">Pilih ...</option>
                                    <option value="ringan">Ringan</option>
                                    <option value="sedang">Sedang</option>
                                    <option value="berat">Berat</option>
                                </select>
                                <div class="invalid-feedback" id="intensitas_latihanError"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Komponen Latihan -->
                    <div class="mb-10">
                        <div class="d-flex align-items-center mb-5">
                            <i class="ki-duotone ki-chart-line-star fs-2 me-3 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            <h4 class="fw-bold text-gray-800 mb-0">Jenis & Komponen Latihan</h4>
                        </div>
                        <div class="table-responsive border rounded">
                            <table class="table table-row-bordered table-row-gray-300 align-middle gs-4 gy-4">
                                <thead>
                                    <tr class="fw-bold fs-7 text-gray-800 border-bottom border-gray-200 bg-light">
                                        <th class="min-w-150px">Jenis Latihan</th>
                                        <th class="min-w-150px">Komponen</th>
                                        <th class="min-w-80px">Nilai</th>
                                        <th class="min-w-100px text-center">Rentang</th>
                                        <th class="min-w-100px">Keterangan</th>
                                        <th class="min-w-60px text-center">Skor</th>
                                    </tr>
                                </thead>
                                <tbody id="training_components_table">
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-10">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="ki-duotone ki-information-5 fs-3x text-gray-300 mb-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                <span>Pilih atlet terlebih dahulu untuk melihat komponen latihan</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="invalid-feedback d-block" id="componentsError"></div>
                    </div>

                    <div class="mb-10 fv-row">
                        <label class="fs-6 fw-semibold mb-2">Target Performa</label>
                        <textarea class="form-control form-control-solid" rows="3" name="target_performa" id="target_performa" placeholder="Tuliskan target performa yang ingin dicapai"></textarea>
                        <div class="invalid-feedback" id="target_performaError"></div>
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
<!--end::Modal-->
