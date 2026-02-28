"use strict";

var KTJenisDisabilitasController = function () {
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
                url: window.routes.jenisIndex,
                type: 'GET'
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'ps-4' },
                { data: 'kode_klasifikasi', name: 'klasifikasi_disabilitas.kode_klasifikasi', className: 'text-center' },
                { data: 'nama_klasifikasi', name: 'klasifikasi_disabilitas.nama_klasifikasi' },
                { data: 'nama_jenis', name: 'nama_jenis' },
                { data: 'deskripsi', name: 'deskripsi', defaultContent: '-' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
            ],
            language: {
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                emptyTable: "Tidak ada data yang tersedia",
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
        const submitButton = document.getElementById('btnSubmitJenis');

        $(form).on('submit', function (e) {
            e.preventDefault();

            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            var id = $('#jenis_id').val();
            var url = id ? window.routes.jenisIndex + '/' + id : window.routes.jenisStore;

            var formData = {
                klasifikasi_disabilitas_id: $('#klasifikasi_disabilitas_id').val(),
                nama_jenis: $('#nama_jenis').val(),
                deskripsi: $('#deskripsi').val(),
                _token: csrf_token,
            };

            if (id) {
                formData['_method'] = 'PUT';
            }

            submitButton.setAttribute('data-kt-indicator', 'on');
            submitButton.disabled = true;

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function (response) {
                    submitButton.removeAttribute('data-kt-indicator');
                    submitButton.disabled = false;
                    $('#kt_modal_jenis').modal('hide');
                    Swal.fire({
                        text: response.success,
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: { confirmButton: "btn btn-primary" }
                    }).then(function () {
                        datatable.ajax.reload(null, false);
                    });
                },
                error: function (xhr) {
                    submitButton.removeAttribute('data-kt-indicator');
                    submitButton.disabled = false;

                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors || {};
                        $.each(errors, function (key, value) {
                            $('#' + key).addClass('is-invalid');
                            $('#' + key + 'Error').text(value[0]);
                        });
                    } else {
                        Swal.fire({
                            text: "Terjadi kesalahan. Silakan coba lagi.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok",
                            customClass: { confirmButton: "btn btn-primary" }
                        });
                    }
                }
            });
        });
    }

    var handleEditRows = function () {
        document.querySelectorAll('.editJenis').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                $.ajax({
                    url: window.routes.jenisIndex + '/' + id + '/edit',
                    type: 'GET',
                    success: function (response) {
                        $('#modalTitleJenis').text('Edit Jenis Disabilitas');
                        $('#jenis_id').val(response.id);
                        $('#klasifikasi_disabilitas_id').val(response.klasifikasi_disabilitas_id).trigger('change');
                        $('#nama_jenis').val(response.nama_jenis);
                        $('#deskripsi').val(response.deskripsi);
                        $('#kt_modal_jenis').modal('show');
                    }
                });
            });
        });
    }

    var handleDeleteRows = function () {
        document.querySelectorAll('.deleteJenis').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const parent = this.closest('tr');
                const nama = parent.querySelectorAll('td')[3].innerText;

                Swal.fire({
                    text: "Hapus jenis disabilitas \"" + nama + "\"?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            url: window.routes.jenisIndex + '/' + id,
                            type: 'DELETE',
                            data: { _token: csrf_token },
                            success: function (response) {
                                Swal.fire({
                                    text: response.success,
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok",
                                    customClass: { confirmButton: "btn fw-bold btn-primary" }
                                }).then(function () {
                                    datatable.ajax.reload(null, false);
                                });
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    text: xhr.responseJSON.error || "Gagal menghapus data.",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok",
                                    customClass: { confirmButton: "btn fw-bold btn-primary" }
                                });
                            }
                        });
                    }
                });
            });
        });
    }

    var handleResetModal = function () {
        $('#kt_modal_jenis').on('hidden.bs.modal', function () {
            $(form)[0].reset();
            $('#jenis_id').val('');
            $('#klasifikasi_disabilitas_id').val('').trigger('change');
            $('#modalTitleJenis').text('Tambah Jenis Disabilitas');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');
        });
    }

    return {
        init: function () {
            table = document.querySelector('#table_jenis');
            form = document.querySelector('#formJenis');
            if (!table) return;
            initDatatable();
            handleFormSubmit();
            handleResetModal();
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTJenisDisabilitasController.init();
});
