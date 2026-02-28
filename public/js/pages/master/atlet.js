"use strict";

var KTAtletController = function () {
    var table;
    var datatable;
    var form;

    var initDatatable = function () {
        datatable = $(table).DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[1, 'asc']],
            ajax: {
                url: window.routes.atletIndex,
                type: 'GET'
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'ps-4' },
                { data: 'name', name: 'name' },
                { data: 'cabor_name', name: 'cabor.name' },
                { data: 'klasifikasi_badge', name: 'klasifikasi_disabilitas.kode_klasifikasi' },
                { data: 'user.username', name: 'user.username', defaultContent: '-' },
                { data: 'nik', name: 'nik' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center pe-0' },
            ],
            language: {
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                emptyTable: "Tidak ada data yang tersedia pada tabel ini",
                sLengthMenu: "Tampilkan _MENU_ data",
                search: "Cari:"
            }
        });

        datatable.on('draw', function () {
            handleDeleteRows();
            handleEditRows();
        });
    }

    var handleFormSubmit = function () {
        const submitButton = document.getElementById('btnSubmit');

        $(form).on('submit', function (e) {
            e.preventDefault();

            // Clear previous errors
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            var id = $('#atlet_id').val();
            var url = id ? window.routes.atletIndex + '/' + id : window.routes.atletStore;

            var formData = new FormData(this);
            if (id) {
                formData.append('_method', 'PUT');
            }

            submitButton.setAttribute('data-kt-indicator', 'on');
            submitButton.disabled = true;

            $.ajax({
                url: url,
                type: 'POST', // Always POST for FormData, override with _method=PUT
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    submitButton.removeAttribute('data-kt-indicator');
                    submitButton.disabled = false;

                    $('#kt_modal_atlet').modal('hide');
                    Swal.fire({
                        text: response.success,
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, Mengerti!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    }).then(function () {
                        datatable.ajax.reload(null, false);
                    });
                },
                error: function (xhr) {
                    submitButton.removeAttribute('data-kt-indicator');
                    submitButton.disabled = false;

                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.error || xhr.responseJSON.errors;
                        // Focus back to first tab so user sees error
                        $('.nav-tabs a[href="#kt_tab_akun_pribadi"]').tab('show');

                        $.each(errors, function (key, value) {
                            $('#' + key).addClass('is-invalid');
                            $('#' + key + 'Error').text(value[0]);
                        });
                    } else {
                        Swal.fire({
                            text: "Maaf, terjadi kesalahan. Silakan coba lagi.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, Mengerti!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    }
                }
            });
        });
    }

    var fetchFileLink = function (path, title) {
        if (!path) return '<span class="text-muted fs-8">Belum ada file diunggah.</span>';
        let fullPath = window.baseUrl + '/' + path;
        return '<a href="' + fullPath + '" target="_blank" class="btn btn-sm btn-light-primary mt-2"><i class="ki-duotone ki-file-down fs-3"></i> Buka ' + title + ' tersimpan</a>';
    }

    var handleEditRows = function () {
        const editButtons = document.querySelectorAll('.editAtlet');

        editButtons.forEach(d => {
            d.addEventListener('click', function (e) {
                e.preventDefault();

                const id = this.getAttribute('data-id');
                const url = window.routes.atletIndex + '/' + id + '/edit';

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (response) {
                        $('#modalTitle').text('Edit Data Atlet');
                        $('#atlet_id').val(response.id);

                        // Select2 Elements
                        $('#cabor_id').val(response.cabor_id).trigger('change');
                        $('#klasifikasi_disabilitas_id').val(response.klasifikasi_disabilitas_id).trigger('change');

                        $('#name').val(response.name);
                        $('#jenis_disabilitas').val(response.jenis_disabilitas);
                        $('#nik').val(response.nik);
                        $('#birth_place').val(response.birth_place);
                        $('#birth_date').val(response.birth_date);
                        $('#religion').val(response.religion);
                        $('#gender').val(response.gender);
                        $('#blood_type').val(response.blood_type);
                        $('#address').val(response.address);
                        $('#last_education').val(response.last_education);

                        // User Profile
                        if (response.user) {
                            $('#username').val(response.user.username);
                            $('#email').val(response.user.email);
                        }

                        // Attachments Links
                        $('#photo_path_viewer').html(fetchFileLink(response.photo_path, 'Pas Foto'));
                        $('#ktp_path_viewer').html(fetchFileLink(response.ktp_path, 'KTP'));
                        $('#achievement_certificate_path_viewer').html(fetchFileLink(response.achievement_certificate_path, 'Sertifikat Prestasi'));
                        $('#education_certificate_path_viewer').html(fetchFileLink(response.education_certificate_path, 'Sertifikat Pddkn'));
                        $('#npwp_path_viewer').html(fetchFileLink(response.npwp_path, 'NPWP'));
                        $('#sk_path_viewer').html(fetchFileLink(response.sk_path, 'SK Atlet'));

                        $('#kt_modal_atlet').modal('show');
                        $('.nav-tabs a[href="#kt_tab_akun_pribadi"]').tab('show'); // Reset to first tab
                    },
                    error: function () {
                        Swal.fire({
                            text: "Data tidak ditemukan.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, Mengerti!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    }
                });
            });
        });
    }

    var handleDeleteRows = function () {
        const deleteButtons = document.querySelectorAll('.deleteAtlet');

        deleteButtons.forEach(d => {
            d.addEventListener('click', function (e) {
                e.preventDefault();

                const id = this.getAttribute('data-id');
                const parent = e.target.closest('tr');
                const nama = parent.querySelectorAll('td')[1].innerText;

                Swal.fire({
                    text: "Apakah Anda yakin ingin menghapus atlet " + nama + "? (Ini juga akan menghapus akun login-nya)",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Tidak, Batal",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            url: window.routes.atletIndex + '/' + id,
                            type: 'DELETE',
                            data: {
                                "_token": csrf_token
                            },
                            success: function (response) {
                                Swal.fire({
                                    text: response.success,
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, Mengerti!",
                                    customClass: {
                                        confirmButton: "btn fw-bold btn-primary",
                                    }
                                }).then(function () {
                                    datatable.ajax.reload(null, false);
                                });
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    text: xhr.responseJSON.error || "Maaf, terjadi kesalahan saat menghapus data.",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, Mengerti!",
                                    customClass: {
                                        confirmButton: "btn fw-bold btn-primary",
                                    }
                                });
                            }
                        });
                    }
                });
            });
        });
    }

    var handleResetForm = function () {
        $('#kt_modal_atlet').on('hidden.bs.modal', function () {
            $(form)[0].reset();
            $('#atlet_id').val('');

            // Reset Select2 fields inside modal
            $('#cabor_id').val('').trigger('change');
            $('#klasifikasi_disabilitas_id').val('').trigger('change');

            $('#modalTitle').text('Tambah Data Atlet');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            $('.nav-tabs a[href="#kt_tab_akun_pribadi"]').tab('show'); // Reset to first tab

            // Clear file viewers
            $('[id$="_viewer"]').html('');
        });

        $('#btnReset').on('click', function (e) {
            e.preventDefault();
            $('#kt_modal_atlet').modal('hide');
        });
    }

    return {
        init: function () {
            table = document.querySelector('#table_atlet');
            form = document.querySelector('#formAtlet');

            if (!table) {
                return;
            }

            initDatatable();
            handleFormSubmit();
            handleResetForm();
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTAtletController.init();
});
