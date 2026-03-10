"use strict";

var KTPembinaanPrestasi = function () {
    var table;
    var datatable;

    var initDatatable = function () {
        datatable = $(table).DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[0, 'desc']],
            stateSave: true,
            ajax: {
                url: window.routes.pembinaanIndex,
                data: function (d) {
                    d.cabor_id = $('#filter_cabor_id').val();
                    d.atlet_id = $('#filter_atlet_id').val();
                    d.periodesasi_latihan = $('#filter_periode').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'tanggal', name: 'tanggal' },
                { data: 'atlet_name', name: 'atlet_name' },
                { data: 'cabor_name', name: 'cabor_name' },
                { data: 'periodesasi_latihan', name: 'periodesasi_latihan' },
                { data: 'intensitas_latihan', name: 'intensitas_latihan' },
                { data: 'training_type', name: 'training_type' },
                { data: 'training_component', name: 'training_component' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
            ],
            columnDefs: [
                {
                    targets: 3, // Periodisitas
                    render: function (data) {
                        return '<span class="text-capitalize">' + data + '</span>';
                    }
                },
                {
                    targets: 4, // Intensitas
                    render: function (data) {
                        let badge = 'badge-light-primary';
                        if (data === 'sedang') badge = 'badge-light-warning';
                        if (data === 'berat') badge = 'badge-light-danger';
                        return '<span class="badge ' + badge + ' text-capitalize">' + data + '</span>';
                    }
                }
            ]
        });
    };

    var componentCriteria = {};

    var renderTrainingTable = function (types, values = {}) {
        var container = $('#training_components_table');
        container.empty();
        componentCriteria = {};

        if (types.length === 0) {
            container.append('<tr><td colspan="5" class="text-center text-muted py-10">Tidak ada komponen latihan untuk cabor ini.</td></tr>');
            return;
        }

        types.forEach(function (type) {
            if (type.components && type.components.length > 0) {
                type.components.forEach(function (comp, index) {
                    componentCriteria[comp.id] = comp.scores || [];

                    var row = '<tr>';

                    // Only show training type name on the first row of its components
                    if (index === 0) {
                        row += '<td rowspan="' + type.components.length + '" class="fw-bold text-gray-800">' + type.name + '</td>';
                    }

                    var val = values[comp.id] || '';
                    var scoreInfo = calculateScoreLocal(comp.id, val);

                    row += '<td>' + comp.name + '</td>';
                    row += '<td><input type="text" class="form-control form-control-solid form-control-sm trainer-input text-center" data-id="' + comp.id + '" name="components[' + comp.id + ']" value="' + val + '" placeholder="..." /></td>';
                    row += '<td class="text-center"><span class="badge badge-light fw-bold range-cell-' + comp.id + '">' + (scoreInfo.range || '-') + '</span></td>';
                    row += '<td><span class="text-gray-800 fw-semibold label-cell-' + comp.id + '">' + (scoreInfo.label || '-') + '</span></td>';
                    row += '<td class="text-center"><span class="badge badge-primary fs-7 score-cell-' + comp.id + '">' + (scoreInfo.score || '-') + '</span></td>';
                    row += '</tr>';

                    container.append(row);
                });
            }
        });

        // Add event listener for real-time score calculation
        $('.trainer-input').on('input', function () {
            var id = $(this).data('id');
            var val = $(this).val();
            var info = calculateScoreLocal(id, val);

            $('.range-cell-' + id).text(info.range || '-');
            $('.label-cell-' + id).text(info.label || '-');
            $('.score-cell-' + id).text(info.score || '-');
        });
    };

    var calculateScoreLocal = function (componentId, value) {
        if (value === undefined || value === null || value === '') return { score: null, label: null, range: null };

        // Convert to string and handle Indonesian decimal (comma to dot)
        var strVal = value.toString().replace(',', '.');
        var numericValue = parseFloat(strVal);

        if (isNaN(numericValue)) return { score: null, label: null, range: null };

        var criteria = componentCriteria[componentId] || [];
        // Criteria is already sorted by score DESC from the backend relationship

        for (var i = 0; i < criteria.length; i++) {
            var criterion = criteria[i];
            var min = criterion.min_value !== null ? parseFloat(criterion.min_value) : null;
            var max = criterion.max_value !== null ? parseFloat(criterion.max_value) : null;

            var match = false;
            var rangeStr = '-';

            if (min !== null && max !== null) {
                rangeStr = min + ' - ' + max;
                if (numericValue >= min && numericValue <= max) match = true;
            } else if (min !== null) {
                rangeStr = '>= ' + min;
                if (numericValue >= min) match = true;
            } else if (max !== null) {
                rangeStr = '<= ' + max;
                if (numericValue <= max) match = true;
            }

            if (match) {
                return {
                    score: criterion.score,
                    label: criterion.label,
                    range: rangeStr
                };
            }
        }

        return { score: null, label: null, range: null };
    };

    var handleAthleteChange = function () {
        $('#atlet_id').on('change', function () {
            var atletId = $(this).val();
            if (atletId) {
                $.ajax({
                    url: window.routes.trainingData,
                    type: 'GET',
                    data: { atlet_id: atletId },
                    success: function (res) {
                        $('#display_cabor').val(res.cabor_name);
                        $('#display_jenis_disabilitas').val(res.jenis_disabilitas || '-');
                        $('#display_klasifikasi').val(res.klasifikasi || '-');

                        renderTrainingTable(res.types);
                    }
                });
            } else {
                $('#display_cabor, #display_jenis_disabilitas, #display_klasifikasi').val('');
                $('#training_components_table').empty().append('<tr><td colspan="6" class="text-center text-muted py-10"><div class="d-flex flex-column align-items-center"><i class="ki-duotone ki-information-5 fs-3x text-gray-300 mb-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i><span>Pilih atlet terlebih dahulu untuk melihat komponen latihan</span></div></td></tr>');
            }
        });
    };

    var handleSubmit = function () {
        $('#formPembinaan').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            var id = $('#pembinaan_id').val();
            var url = id ? window.routes.pembinaanIndex + '/' + id : window.routes.pembinaanStore;

            if (id) {
                formData.append('_method', 'PUT');
            }

            var btn = $('#btnSubmit');
            btn.attr('data-kt-indicator', 'on').prop('disabled', true);

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: { 'X-CSRF-TOKEN': csrf_token },
                success: function (res) {
                    $('#kt_modal_pembinaan').modal('hide');
                    datatable.ajax.reload();
                    Swal.fire({ text: res.success, icon: "success", buttonsStyling: false, confirmButtonText: "Ok!", customClass: { confirmButton: "btn btn-primary" } });
                },
                error: function (xhr) {
                    var errors = xhr.responseJSON.errors;
                    $('.invalid-feedback').text('');
                    $('.form-control, .form-select').removeClass('is-invalid');

                    if (errors) {
                        var errorMsg = '';
                        var hasComponentError = false;
                        $.each(errors, function (key, value) {
                            if (key.includes('components')) {
                                $('#componentsError').text('Pastikan nilai latihan valid.');
                                if (!hasComponentError) {
                                    errorMsg += 'Pastikan nilai komponen latihan valid.<br>';
                                    hasComponentError = true;
                                }
                            } else {
                                $('#' + key).addClass('is-invalid');
                                $('#' + key + 'Error').text(value[0]);
                                errorMsg += value[0] + '<br>';
                            }
                        });

                        Swal.fire({
                            html: errorMsg,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, Mengerti!",
                            customClass: { confirmButton: "btn btn-primary" }
                        });
                    } else {
                        Swal.fire({ text: xhr.responseJSON.error || "Terjadi kesalahan.", icon: "error", buttonsStyling: false, confirmButtonText: "Ok!", customClass: { confirmButton: "btn btn-primary" } });
                    }
                },
                complete: function () {
                    btn.removeAttr('data-kt-indicator').prop('disabled', false);
                }
            });
        });
    };

    var handleEdit = function () {
        $(document).on('click', '.editPembinaan', function () {
            var id = $(this).data('id');
            $.ajax({
                url: window.routes.pembinaanIndex + '/' + id + '/edit',
                type: 'GET',
                success: function (res) {
                    var m = res.pembinaan;
                    $('#modalTitle').text('Edit Program Pembinaan');
                    $('#pembinaan_id').val(m.id);

                    // Set basic fields
                    $('#atlet_id').val(m.atlet_id).trigger('change.select2'); // Trigger select2 specifically

                    // Manually trigger display updates since the normal change listener might not have data yet
                    $('#display_cabor').val(m.atlet.cabor.name);
                    $('#display_jenis_disabilitas').val(res.jenis_disabilitas || '-');
                    $('#display_klasifikasi').val(res.klasifikasi || '-');

                    $('#periodesasi_latihan').val(m.periodesasi_latihan);
                    $('#intensitas_latihan').val(m.intensitas_latihan);

                    var fpTanggal = document.querySelector("#tanggal")._flatpickr;
                    if (fpTanggal) {
                        fpTanggal.setDate(m.tanggal ? moment(m.tanggal).format('YYYY-MM-DD') : '');
                    } else {
                        $('#tanggal').val(m.tanggal ? moment(m.tanggal).format('YYYY-MM-DD') : '');
                    }

                    $('#target_performa').val(m.target_performa);

                    // Render table with values
                    renderTrainingTable(res.types, res.values);

                    $('#kt_modal_pembinaan').modal('show');
                }
            });
        });
    };

    var handleDelete = function () {
        $(document).on('click', '.deletePembinaan', function () {
            var id = $(this).data('id');
            Swal.fire({
                text: "Hapus data ini?", icon: "warning", showCancelButton: true,
                confirmButtonText: "Ya!", cancelButtonText: "Batal",
                customClass: { confirmButton: "btn btn-danger", cancelButton: "btn btn-active-light" }
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: window.routes.pembinaanIndex + '/' + id,
                        type: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': csrf_token },
                        success: function (res) {
                            datatable.ajax.reload();
                            Swal.fire({ text: res.success, icon: "success", confirmButtonText: "Ok!", customClass: { confirmButton: "btn btn-primary" } });
                        }
                    });
                }
            });
        });
    };

    var handleModalReset = function () {
        $('#kt_modal_pembinaan').on('hidden.bs.modal', function () {
            $('#formPembinaan')[0].reset();
            $('#pembinaan_id').val('');
            $('#modalTitle').text('Tambah Program Pembinaan');
            $('#atlet_id').val('').trigger('change.select2');

            $('#display_cabor, #display_jenis_disabilitas, #display_klasifikasi').val('');
            $('#training_components_table').empty().append('<tr><td colspan="6" class="text-center text-muted py-10"><div class="d-flex flex-column align-items-center"><i class="ki-duotone ki-information-5 fs-3x text-gray-300 mb-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i><span>Pilih atlet terlebih dahulu untuk melihat komponen latihan</span></div></td></tr>');

            $('.invalid-feedback').text('');
            $('.form-control, .form-select').removeClass('is-invalid');
        });
    };

    var handleFilters = function () {
        var allAtlets = $('#filter_atlet_id').html(); // Store all options

        $('#filter_cabor_id').on('change', function () {
            var caborId = $(this).val();
            var atletSelect = $('#filter_atlet_id');

            // Reset to show all first, then filter
            atletSelect.html(allAtlets);
            atletSelect.val(null).trigger('change');

            if (caborId) {
                atletSelect.find('option').each(function () {
                    var optionCabor = $(this).data('cabor');
                    // Keep the empty option and options that match the cabor
                    if (optionCabor && optionCabor != caborId) {
                        $(this).remove();
                    }
                });
            }

            // Re-initialize select2
            atletSelect.select2({
                placeholder: "Filter Atlet",
                allowClear: true
            });

            datatable.ajax.reload();
        });

        $('#filter_atlet_id, #filter_periode').on('change', function () {
            datatable.ajax.reload();
        });

        $('#btnResetFilter').on('click', function () {
            $('#filter_cabor_id').val(null).trigger('change');
            $('#filter_atlet_id').val(null).trigger('change');
            $('#filter_periode').val(null).trigger('change');
            datatable.ajax.reload();
        });
    };

    return {
        init: function () {
            table = document.querySelector('#table_pembinaan');
            if (!table) return;

            initDatatable();
            handleFilters();

            $("#tanggal").flatpickr({
                altInput: true,
                altFormat: "d F, Y",
                dateFormat: "Y-m-d",
            });

            handleAthleteChange();
            handleSubmit();
            handleEdit();
            handleDelete();
            handleModalReset();
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTPembinaanPrestasi.init();
});
