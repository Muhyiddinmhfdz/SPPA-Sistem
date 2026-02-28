"use strict";

var KTKlasifikasiController = function () {
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
                url: window.routes.klasifikasiIndex,
                type: 'GET'
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'ps-4' },
                { data: 'kode_klasifikasi', name: 'kode_klasifikasi' },
                { data: 'nama_klasifikasi', name: 'nama_klasifikasi' },
                { data: 'deskripsi', name: 'deskripsi' },
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

            var id = $('#klasifikasi_id').val();
            var url = id ? window.routes.klasifikasiIndex + '/' + id : window.routes.klasifikasiStore;
            var method = id ? 'PUT' : 'POST';

            submitButton.setAttribute('data-kt-indicator', 'on');
            submitButton.disabled = true;

            $.ajax({
                url: url,
                type: method,
                data: $(this).serialize(),
                success: function (response) {
                    submitButton.removeAttribute('data-kt-indicator');
                    submitButton.disabled = false;

                    $('#kt_modal_klasifikasi').modal('hide');
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
                        var errors = xhr.responseJSON.error;
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

    var handleEditRows = function () {
        const editButtons = document.querySelectorAll('.editKlasifikasi');

        editButtons.forEach(d => {
            d.addEventListener('click', function (e) {
                e.preventDefault();

                const id = this.getAttribute('data-id');
                const url = window.routes.klasifikasiIndex + '/' + id + '/edit';

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (response) {
                        $('#modalTitle').text('Edit Klasifikasi Disabilitas');
                        $('#klasifikasi_id').val(response.id);
                        $('#kode_klasifikasi').val(response.kode_klasifikasi);
                        $('#nama_klasifikasi').val(response.nama_klasifikasi);
                        $('#deskripsi').val(response.deskripsi);

                        $('#kt_modal_klasifikasi').modal('show');
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
        const deleteButtons = document.querySelectorAll('.deleteKlasifikasi');

        deleteButtons.forEach(d => {
            d.addEventListener('click', function (e) {
                e.preventDefault();

                const id = this.getAttribute('data-id');
                const parent = e.target.closest('tr');
                const nama = parent.querySelectorAll('td')[2].innerText;

                Swal.fire({
                    text: "Apakah Anda yakin ingin menghapus klasifikasi " + nama + "?",
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
                            url: window.routes.klasifikasiIndex + '/' + id,
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
        $('#kt_modal_klasifikasi').on('hidden.bs.modal', function () {
            $(form)[0].reset();
            $('#klasifikasi_id').val('');
            $('#modalTitle').text('Tambah Klasifikasi Disabilitas');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');
        });

        $('#btnReset').on('click', function (e) {
            e.preventDefault();
            $('#kt_modal_klasifikasi').modal('hide');
        });
    }

    return {
        init: function () {
            table = document.querySelector('#table_klasifikasi');
            form = document.querySelector('#formKlasifikasi');

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
    KTKlasifikasiController.init();
});
