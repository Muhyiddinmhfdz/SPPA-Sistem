"use strict";

var KTKompetisiController = function () {
    var table;
    var datatable;
    var form;
    var submitButton;

    var initDatatable = function () {
        datatable = $(table).DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[5, 'desc']],
            ajax: {
                url: window.routes.index,
                type: 'GET',
                data: function (d) {
                    d.cabor_id = $('#filter_cabor_id').val();
                    d.atlet_id = $('#filter_atlet_id').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'ps-4' },
                { data: 'nama_kompetisi', name: 'nama_kompetisi' },
                { data: 'atlet_name', name: 'atlet.name' },
                { data: 'cabor_name', name: 'cabor.name' },
                { data: 'tingkatan', name: 'tingkatan' },
                { data: 'tanggal', name: 'waktu_pelaksanaan' },
                { data: 'medali_badge', name: 'hasil_medali', className: 'text-center' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center pe-0' },
            ],
            language: {
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                emptyTable: "Belum ada riwayat kompetisi",
                sLengthMenu: "Tampilkan _MENU_ data",
                search: "Cari:"
            }
        });

        datatable.on('draw', function () {
            handleEditRows();
            handleDeleteRows();
        });

        // Filter handlers
        $('#filter_cabor_id').on('change', function () {
            var caborId = $(this).val();
            var atletSelect = $('#filter_atlet_id');
            atletSelect.empty().append('<option value=""></option>');

            if (caborId) {
                $.ajax({
                    url: window.routes.atlets + '/' + caborId,
                    type: 'GET',
                    success: function (response) {
                        response.forEach(function (atlet) {
                            atletSelect.append('<option value="' + atlet.id + '">' + atlet.name + '</option>');
                        });
                        atletSelect.trigger('change');
                    }
                });
            } else {
                atletSelect.trigger('change');
            }
            datatable.ajax.reload();
        });

        $('#filter_atlet_id').on('change', function () {
            datatable.ajax.reload();
        });

        $('#btnResetFilter').on('click', function () {
            $('#filter_cabor_id').val('').trigger('change');
            $('#filter_atlet_id').empty().append('<option value=""></option>').trigger('change');
            datatable.ajax.reload();
        });
    }

    var handleCaborChange = function () {
        $('#cabor_id').on('change', function () {
            var caborId = $(this).val();
            var atletSelect = $('#atlet_id');
            atletSelect.empty().append('<option value=""></option>');

            if (caborId) {
                $.ajax({
                    url: window.routes.atlets + '/' + caborId,
                    type: 'GET',
                    success: function (response) {
                        response.forEach(function (atlet) {
                            atletSelect.append('<option value="' + atlet.id + '">' + atlet.name + '</option>');
                        });
                        atletSelect.val(atletSelect.data('selected')).trigger('change');
                        atletSelect.data('selected', ''); // Clear after use
                    }
                });
            }
        });
    }

    var handleFormSubmit = function () {
        $(form).on('submit', function (e) {
            e.preventDefault();

            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            var id = $('#kompetisi_id').val();
            var url = id ? window.routes.index + '/' + id : window.routes.store;
            var method = id ? 'PUT' : 'POST';

            submitButton.setAttribute('data-kt-indicator', 'on');
            submitButton.disabled = true;

            $.ajax({
                url: url,
                type: 'POST',
                data: $(this).serialize() + (id ? '&_method=PUT' : ''),
                success: function (response) {
                    submitButton.removeAttribute('data-kt-indicator');
                    submitButton.disabled = false;
                    $('#modalKompetisi').modal('hide');

                    Swal.fire({
                        text: response.success,
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, Mengerti!",
                        customClass: { confirmButton: "btn btn-primary" }
                    }).then(function () {
                        datatable.ajax.reload(null, false);
                    });
                },
                error: function (xhr) {
                    submitButton.removeAttribute('data-kt-indicator');
                    submitButton.disabled = false;

                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        var errorMsg = '';
                        $.each(errors, function (key, value) {
                            var input = $('[name="' + key + '"]');
                            input.addClass('is-invalid');
                            input.parent().append('<div class="invalid-feedback">' + value[0] + '</div>');
                            errorMsg += value[0] + '<br>';
                        });

                        Swal.fire({
                            html: errorMsg,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, Mengerti!",
                            customClass: { confirmButton: "btn btn-primary" }
                        });
                    } else {
                        Swal.fire({
                            text: "Maaf, terjadi kesalahan.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, Mengerti!",
                            customClass: { confirmButton: "btn btn-primary" }
                        });
                    }
                }
            });
        });
    }

    var handleEditRows = function () {
        $('.editKompetisi').on('click', function (e) {
            e.preventDefault();
            var id = $(this).data('id');

            $.ajax({
                url: window.routes.index + '/' + id + '/edit',
                type: 'GET',
                success: function (response) {
                    $('#modalTitle').text('Edit Data Kompetisi');
                    $('#kompetisi_id').val(response.id);
                    $('#cabor_id').val(response.cabor_id).trigger('change');
                    $('#atlet_id').data('selected', response.atlet_id); // Will be picked up by cabor change trigger

                    $('#nama_kompetisi').val(response.nama_kompetisi);
                    $('#tingkatan').val(response.tingkatan);
                    $('#waktu_pelaksanaan').val(response.waktu_pelaksanaan.split('T')[0]);
                    $('#tempat_pelaksanaan').val(response.tempat_pelaksanaan);
                    $('#jumlah_peserta').val(response.jumlah_peserta);
                    $('#hasil_peringkat').val(response.hasil_peringkat);
                    $('#kesimpulan_evaluasi').val(response.kesimpulan_evaluasi);

                    // Radio medali
                    $('input[name="hasil_medali"][value="' + response.hasil_medali + '"]').prop('checked', true);

                    $('#modalKompetisi').modal('show');
                }
            });
        });
    }

    var handleDeleteRows = function () {
        $('.deleteKompetisi').on('click', function (e) {
            e.preventDefault();
            var id = $(this).data('id');

            Swal.fire({
                text: "Apakah Anda yakin ingin menghapus data kompetisi ini?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal",
                customClass: {
                    confirmButton: "btn btn-danger",
                    cancelButton: "btn btn-active-light"
                }
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: window.routes.index + '/' + id,
                        type: 'DELETE',
                        data: { _token: csrf_token },
                        success: function (response) {
                            Swal.fire({ text: response.success, icon: "success", buttonsStyling: false, confirmButtonText: "Ok", customClass: { confirmButton: "btn btn-primary" } });
                            datatable.ajax.reload(null, false);
                        }
                    });
                }
            });
        });
    }

    var initPlugins = function () {
        flatpickr('#waktu_pelaksanaan', {
            dateFormat: 'Y-m-d',
            locale: 'id'
        });
    }

    return {
        init: function () {
            table = document.querySelector('#table_kompetisi');
            form = document.querySelector('#formKompetisi');
            submitButton = document.querySelector('#btnSubmit');

            if (!table) return;

            initDatatable();
            handleCaborChange();
            handleFormSubmit();
            initPlugins();

            $('#btnTambahKompetisi').on('click', function () {
                $('#modalTitle').text('Tambah Data Kompetisi');
                $(form)[0].reset();
                $('#kompetisi_id').val('');
                $('#cabor_id').val('').trigger('change');
                $('#atlet_id').empty().append('<option value=""></option>').trigger('change');
                $('#modalKompetisi').modal('show');
            });
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTKompetisiController.init();
});
