"use strict";

var KTCaborController = function () {
    var table;

    var initDatatable = function () {
        table = $('#table_cabor').DataTable({
            processing: true,
            serverSide: true,
            ajax: window.routes.caborIndex,
            columns: [
                {
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
                    data: 'chairman_name',
                    name: 'chairman_name'
                },
                {
                    data: 'phone_number',
                    name: 'phone_number'
                },
                {
                    data: 'file_sk',
                    name: 'file_sk',
                    orderable: false,
                    searchable: false
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
    }

    var initFormActions = function () {
        // Init Flatpickr
        $("#sk_start_date").flatpickr({
            dateFormat: "Y-m-d",
        });

        $("#sk_end_date").flatpickr({
            dateFormat: "Y-m-d",
        });

        // Add Modal
        $('#btnTambahCabor').click(function() {
            $('#formCabor').trigger("reset");
            $('#cabor_id').val('');
            $('#modalTitle').text('Tambah Cabang Olahraga');
            $('#sk_fileHint').hide();
            $('#sk_start_date').val('').trigger('change');
            $('#sk_end_date').val('').trigger('change');
            $('.invalid-feedback').text('');
            $('.form-control').removeClass('is-invalid');
        });

        // Edit Modal
        $('body').on('click', '.editCabor', function() {
            var id = $(this).data('id');
            $.get(window.routes.caborIndex + '/' + id + '/edit', function(data) {
                $('#modalTitle').text('Edit Cabang Olahraga');
                $('#cabor_id').val(data.id);
                $('#name').val(data.name);
                $('#sk_start_date').val(data.sk_start_date);
                $('#sk_end_date').val(data.sk_end_date);
                $('#chairman_name').val(data.chairman_name);
                $('#secretary_name').val(data.secretary_name);
                $('#treasurer_name').val(data.treasurer_name);
                $('#secretariat_address').val(data.secretariat_address);
                $('#phone_number').val(data.phone_number);
                $('#email').val(data.email);
                $('#npwp').val(data.npwp);
                $('#active_athletes_count').val(data.active_athletes_count);
                $('#active_coaches_count').val(data.active_coaches_count);
                $('#active_medics_count').val(data.active_medics_count);

                $('#sk_file').val('');
                $('#sk_fileHint').show();

                $('.invalid-feedback').text('');
                $('.form-control').removeClass('is-invalid');
                $('#kt_modal_cabor').modal('show');
            }).fail(function(err) {
                Swal.fire('Error', 'Gagal mengambil data', 'error');
            });
        });

        // Submit Form
        $('#formCabor').submit(function(e) {
            e.preventDefault();
            var id = $('#cabor_id').val();
            var url = id ? window.routes.caborIndex + "/" + id : window.routes.caborStore;

            var formData = new FormData(this);
            if (id) {
                formData.append('_method', 'PUT');
            }

            var btn = document.getElementById('btnSubmit');
            btn.setAttribute('data-kt-indicator', 'on');
            btn.disabled = true;

            $.ajax({
                type: 'POST', // always POST for FormData, with _method=PUT overrides
                url: url,
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#kt_modal_cabor').modal('hide');
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
                                $('#' + key + 'Error').text(value).show();
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
        $('body').on('click', '.deleteCabor', function() {
            var id = $(this).data("id");
            Swal.fire({
                text: "Anda yakin ingin menghapus data cabang olahraga ini?",
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
                        url: window.routes.caborIndex + "/" + id,
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
    }

    return {
        init: function() {
            initDatatable();
            initFormActions();
        }
    };
}();

$(document).ready(function() {
    KTCaborController.init();
});
