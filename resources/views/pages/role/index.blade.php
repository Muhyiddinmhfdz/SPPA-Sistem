@extends('layouts.main_layout')

@section('content')
<div class="row g-5 g-xl-8">
    <div class="col-xl-12">
        <div class="card card-xl-stretch mb-5 mb-xl-8 border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 text-gray-900">Daftar Role</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Manajemen peran dan hak akses sistem</span>
                </h3>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-light-primary fw-bold" data-bs-toggle="modal" data-bs-target="#kt_modal_role" id="btnTambahRole">
                        <i class="ki-duotone ki-plus fs-2"><span class="path1"></span><span class="path2"></span></i> Tambah Role
                    </button>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="table-responsive">
                    <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3" id="table_role">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 min-w-50px rounded-start">No</th>
                                <th class="min-w-200px">Nama Role</th>
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

<!-- Modal Role -->
<div class="modal fade" id="kt_modal_role" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-500px">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                <form id="formRole" class="form" action="#">
                    @csrf
                    <input type="hidden" id="role_id" name="id">
                    <div class="mb-13 text-center">
                        <h1 class="mb-3 text-gray-900 fw-bolder" id="modalTitle">Tambah Role</h1>
                        <div class="text-muted fw-semibold fs-5">Pastikan nama role jelas dan sesuai peruntukan</div>
                    </div>
                    <div class="d-flex flex-column mb-8 fv-row">
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                            <span class="required">Nama Role</span>
                        </label>
                        <input type="text" class="form-control form-control-solid fw-semibold" placeholder="Masukkan nama role" name="name" id="name" />
                        <div class="invalid-feedback" id="nameError"></div>
                    </div>
                    <div class="text-center">
                        <button type="reset" id="btnReset" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" id="btnSubmit" class="btn btn-primary">
                            <span class="indicator-label">Simpan</span>
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
    $(document).ready(function() {
        // Init Datatable
        var table = $('#table_role').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('master.role.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    className: 'ps-4'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
            ],
            language: {
                emptyTable: "Tidak ada data tersedia",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                lengthMenu: "Tampilkan _MENU_ data",
                search: "Cari:",
                zeroRecords: "Tidak ditemukan data yang sesuai",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            },
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        });

        // Add
        $('#btnTambahRole').click(function() {
            $('#formRole').trigger("reset");
            $('#role_id').val('');
            $('#modalTitle').text('Tambah Role');
            $('.invalid-feedback').text('');
            $('.form-control').removeClass('is-invalid');
        });

        // Edit
        $('body').on('click', '.editRole', function() {
            var id = $(this).data('id');
            $.get("{{ route('master.role.index') }}" + '/' + id + '/edit', function(data) {
                $('#modalTitle').text('Edit Role');
                $('#role_id').val(data.id);
                $('#name').val(data.name);
                $('.invalid-feedback').text('');
                $('.form-control').removeClass('is-invalid');
                $('#kt_modal_role').modal('show');
            }).fail(function(err) {
                Swal.fire('Error', 'Gagal mengambil data', 'error');
            });
        });

        // Submit
        $('#formRole').submit(function(e) {
            e.preventDefault();
            var id = $('#role_id').val();
            var url = id ? "{{ route('master.role.index') }}/" + id : "{{ route('master.role.store') }}";
            var type = id ? "PUT" : "POST";

            var btn = document.getElementById('btnSubmit');
            btn.setAttribute('data-kt-indicator', 'on');
            btn.disabled = true;

            $.ajax({
                type: type,
                url: url,
                data: $(this).serialize(),
                success: function(data) {
                    $('#kt_modal_role').modal('hide');
                    table.draw();
                    Swal.fire({
                        text: data.success,
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, mengerti!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                },
                error: function(data) {
                    $('.invalid-feedback').text('');
                    $('.form-control').removeClass('is-invalid');
                    if (data.status === 422) {
                        var errors = data.responseJSON.error;
                        if (typeof errors === 'string') {
                            Swal.fire('Error', errors, 'error');
                        } else {
                            $.each(errors, function(key, value) {
                                $('#' + key).addClass('is-invalid');
                                $('#' + key + 'Error').text(value);
                            });
                        }
                    } else if (data.status === 403) {
                        Swal.fire('Akses Ditolak', data.responseJSON.error, 'error');
                    } else {
                        Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
                    }
                },
                complete: function() {
                    btn.removeAttribute('data-kt-indicator');
                    btn.disabled = false;
                }
            });
        });

        // Delete
        $('body').on('click', '.deleteRole', function() {
            var id = $(this).data("id");
            Swal.fire({
                text: "Anda yakin ingin menghapus role ini?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Tidak, batalkan",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                }
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('master.role.index') }}/" + id,
                        data: {
                            _token: csrf_token
                        },
                        success: function(data) {
                            table.draw();
                            Swal.fire({
                                text: data.success,
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, mengerti!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                }
                            });
                        },
                        error: function(data) {
                            Swal.fire('Error', data.responseJSON.error || 'Terjadi kesalahan sistem', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection