"use strict";

var KTCoachController = function () {
    var table;

    var initDatatable = function () {
        table = $('#table_pelatihan').DataTable({
            processing: true,
            serverSide: true,
            ajax: window.routes.coachIndex,
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
                    data: 'cabor_name',
                    name: 'cabor_name'
                },
                {
                    data: 'nik',
                    name: 'nik'
                },
                {
                    data: 'gender',
                    name: 'gender'
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

        $("#birth_date").flatpickr({
            dateFormat: "Y-m-d",
            maxDate: "today"
        });

        var fetchFileLink = function (path, title) {
            if (!path) return '<span class="text-muted fs-8">Belum ada file diunggah.</span>';
            let fullPath = window.baseUrl + '/' + path;
            return '<a href="' + fullPath + '" target="_blank" class="btn btn-sm btn-light-primary mt-2"><i class="ki-duotone ki-file-down fs-3"></i> Buka ' + title + ' tersimpan</a>';
        }

        // Add Modal
        $('#btnTambahPelatih').click(function () {
            $('#formCoach').trigger("reset");
            $('#coach_id').val('');
            $('#cabor_id').val('').trigger('change');
            $('#religion').val('').trigger('change');
            $('#gender').val('').trigger('change');
            $('#blood_type').val('').trigger('change');
            $('#username').val('');
            $('#password').val('');
            $('#modalTitle').text('Tambah Data Pelatih');

            // hide all document hints
            $('.document_hint').hide();

            $('[id$="_viewer"]').html('');

            $('.nav-tabs a[href="#kt_tab_akun_pribadi"]').tab('show'); // Reset to first tab

            $('.invalid-feedback').text('');
            $('.form-control, .form-select').removeClass('is-invalid');
        });

        // Edit Modal
        $('body').on('click', '.editCoach', function () {
            var id = $(this).data('id');
            $.get(window.routes.coachIndex + '/' + id + '/edit', function (data) {
                $('#modalTitle').text('Edit Data Pelatih');
                $('#coach_id').val(data.id);

                $('#cabor_id').val(data.cabor_id).trigger('change');
                $('#name').val(data.name);
                $('#nik').val(data.nik);

                // Get username from associated User relation (if handled by eloquent loaded in edit method, but wait:
                // controller's edit method currently returns $pelatihan directly. Let's assume user is not eager loaded, but we need the username.)
                // Since user might not be eager loaded, we should load it. Wait, the controller wasn't modified to load it.
                // Wait! No, there is $data.user that we can fetch.
                if (data.user) {
                    $('#username').val(data.user.username);
                } else {
                    $('#username').val('');
                }
                $('#password').val('');

                $('#birth_place').val(data.birth_place);
                $('#birth_date').val(data.birth_date);
                $('#religion').val(data.religion).trigger('change');
                $('#gender').val(data.gender).trigger('change');
                $('#address').val(data.address);
                $('#blood_type').val(data.blood_type).trigger('change');
                $('#last_education').val(data.last_education);

                // clear the file input text
                $('#photo').val('');
                $('#ktp').val('');
                $('#certificate').val('');
                $('#npwp').val('');
                $('#sk').val('');

                // Viewers
                $('#photo_viewer').html(fetchFileLink(data.photo_path, 'Pas Foto'));
                $('#ktp_viewer').html(fetchFileLink(data.ktp_path, 'KTP'));
                $('#certificate_viewer').html(fetchFileLink(data.certificate_path, 'Sertifikat Pddkn/Lisensi'));
                $('#npwp_viewer').html(fetchFileLink(data.npwp_path, 'NPWP'));
                $('#sk_viewer').html(fetchFileLink(data.sk_path, 'SK Pengangkatan'));

                $('.document_hint').show();

                $('.nav-tabs a[href="#kt_tab_akun_pribadi"]').tab('show'); // Reset to first tab

                $('.invalid-feedback').text('');
                $('.form-control, .form-select').removeClass('is-invalid');
                $('#kt_modal_coach').modal('show');
            }).fail(function (err) {
                Swal.fire('Error', 'Gagal mengambil data', 'error');
            });
        });

        // Submit Form
        $('#formCoach').submit(function (e) {
            e.preventDefault();
            var id = $('#coach_id').val();
            var url = id ? window.routes.coachIndex + "/" + id : window.routes.coachStore;

            var formData = new FormData(this);
            if (id) {
                formData.append('_method', 'PUT');
            }

            var btn = document.getElementById('btnSubmit');
            btn.setAttribute('data-kt-indicator', 'on');
            btn.disabled = true;

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#kt_modal_coach').modal('hide');
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
                error: function (data) {
                    $('.invalid-feedback').text('');
                    $('.form-control, .form-select').removeClass('is-invalid');
                    if (data.status === 422) {
                        $('.nav-tabs a[href="#kt_tab_akun_pribadi"]').tab('show');
                        var errors = data.responseJSON.error;
                        if (typeof errors === 'string') {
                            Swal.fire('Error', errors, 'error');
                        } else {
                            $.each(errors, function (key, value) {
                                if ($('#' + key).hasClass('form-select')) {
                                    $('#' + key).next('.select2-container').addClass('is-invalid');
                                } else {
                                    $('#' + key).addClass('is-invalid');
                                }
                                $('#' + key + 'Error').text(value).show();
                            });
                        }
                    } else if (data.status === 403) {
                        Swal.fire('Akses Ditolak', data.responseJSON.error, 'error');
                    } else {
                        Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
                    }
                },
                complete: function () {
                    btn.removeAttribute('data-kt-indicator');
                    btn.disabled = false;
                }
            });
        });

        // Delete
        $('body').on('click', '.deleteCoach', function () {
            var id = $(this).data("id");
            Swal.fire({
                text: "Anda yakin ingin menghapus data pelatih ini?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Tidak, batalkan",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                }
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        type: "DELETE",
                        url: window.routes.coachIndex + "/" + id,
                        data: {
                            _token: csrf_token
                        },
                        success: function (data) {
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
                        error: function (data) {
                            Swal.fire('Error', data.responseJSON.error || 'Terjadi kesalahan sistem', 'error');
                        }
                    });
                }
            });
        });
    }

    return {
        init: function () {
            initDatatable();
            initFormActions();
        }
    };
}();

$(document).ready(function () {
    KTCoachController.init();
});
