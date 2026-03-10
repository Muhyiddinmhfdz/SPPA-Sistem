"use strict";

var KTJenisTesController = function () {
    var table;
    var datatable;
    var formCategory;
    var submitCategory;

    var initDatatable = function () {
        datatable = $(table).DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[0, 'desc']],
            stateSave: true,
            ajax: {
                url: window.routes.index,
                data: function (d) {
                    d.cabor_id = $('#filter_cabor_id').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'ps-4' },
                { data: 'name', name: 'name' },
                { data: 'cabor_name', name: 'cabor.name' },
                { data: 'items_count', name: 'items_count', className: 'text-center' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
            ],
            language: {
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                emptyTable: "Belum ada kategori tes fisik",
                sLengthMenu: "Tampilkan _MENU_ data",
                search: "Cari:"
            }
        });
    }

    var handleCategorySubmit = function () {
        $(formCategory).on('submit', function (e) {
            e.preventDefault();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            var btn = $(submitCategory);
            btn.attr('data-kt-indicator', 'on').prop('disabled', true);

            $.ajax({
                url: window.routes.store,
                type: 'POST',
                data: $(this).serialize(),
                success: function (res) {
                    $('#kt_modal_category').modal('hide');
                    datatable.ajax.reload();
                    Swal.fire({ text: res.success, icon: "success", buttonsStyling: false, confirmButtonText: "Ok!", customClass: { confirmButton: "btn btn-primary" } });
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function (key, value) {
                            var el = $('#' + key);
                            el.addClass('is-invalid');
                            if (el.is("select")) {
                                el.next('.select2-container').addClass('is-invalid');
                            }
                            $('#' + key + 'Error').text(value[0]);
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
    }

    /* --- ITEMS --- */
    var initManageItems = function () {
        $(document).on('click', '.manageItems', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            $('#manageTitle').text('Manajemen Item Tes: ' + name);
            $('#item_category_id').val(id);
            $('#formItem')[0].reset();
            $('#item_sasaran').val('').trigger('change');
            $('.is-invalid').removeClass('is-invalid');

            loadItems(id);
            $('#kt_modal_manage_items').modal('show');
        });

        $('#formItem').on('submit', function (e) {
            e.preventDefault();
            var btn = $('#btnSubmitItem');
            btn.prop('disabled', true);

            $.ajax({
                url: window.routes.storeItem,
                type: 'POST',
                data: $(this).serialize(),
                success: function (res) {
                    $('#formItem')[0].reset();
                    $('#item_sasaran').val('').trigger('change');
                    $('.is-invalid').removeClass('is-invalid');

                    // keep category id
                    var cId = $('#item_category_id').val();
                    $('#item_category_id').val(cId);

                    loadItems(cId);
                    datatable.ajax.reload(null, false); // refresh count
                    toastr.success(res.success);
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function (key, value) {
                            var el = $('#' + (key === 'jenis_disabilitas_id' ? 'item_sasaran' : key));
                            el.addClass('is-invalid');
                            if (el.is("select")) el.next('.select2-container').addClass('is-invalid');
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error("Gagal menyimpan item tes. Pastikan isian sudah benar.");
                    }
                },
                complete: function () {
                    btn.prop('disabled', false);
                }
            });
        });

        $(document).on('click', '.deleteItem', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var cId = $('#item_category_id').val();

            Swal.fire({
                text: "Hapus item tes ini?", icon: "warning", showCancelButton: true,
                confirmButtonText: "Ya!", cancelButtonText: "Batal",
                customClass: { confirmButton: "btn btn-danger", cancelButton: "btn btn-active-light" }
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: window.routes.deleteItem + '/' + id,
                        type: 'DELETE',
                        data: { _token: csrf_token },
                        success: function (res) {
                            loadItems(cId);
                            datatable.ajax.reload(null, false);
                            toastr.success(res.success);
                        }
                    });
                }
            });
        });
    }

    var loadItems = function (categoryId) {
        var tbody = $('#itemList');
        tbody.empty().append('<tr><td colspan="5" class="text-center">Loading...</td></tr>');

        $.ajax({
            url: window.routes.getItems + '/' + categoryId,
            type: 'GET',
            success: function (res) {
                tbody.empty();
                if (res.items.length === 0) {
                    tbody.append('<tr><td colspan="5" class="text-center text-muted">Belum ada item tes.</td></tr>');
                    return;
                }

                res.items.forEach(function (item, index) {
                    var jenisDisabilitasHtml = '-';
                    if (item.jenis_disabilitas) {
                        jenisDisabilitasHtml = `<span class="badge badge-light-info fw-bold">${item.jenis_disabilitas.nama_jenis}</span>`;
                    }
                    var tr = `
                        <tr>
                            <td>${index + 1}</td>
                            <td><span class="text-gray-800 fw-bold">${item.name}</span></td>
                            <td>${jenisDisabilitasHtml}</td>
                            <td>${item.satuan || '-'}</td>
                            <td class="text-end">
                                <a href="#" data-id="${item.id}" data-name="${item.name}" class="btn btn-sm btn-icon btn-light-success manageScores" title="Atur Nilai"><i class="ki-duotone ki-chart-simple fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i></a>
                                <a href="#" data-id="${item.id}" class="btn btn-sm btn-icon btn-light-danger deleteItem" title="Hapus"><i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></a>
                            </td>
                        </tr>
                    `;
                    tbody.append(tr);
                });
            }
        });
    }

    /* --- SCORES --- */
    var initManageScores = function () {
        $(document).on('click', '.manageScores', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            $('#itemLabel').text(name);
            $('#score_item_id').val(id);
            $('#formScore')[0].reset();

            loadScores(id);
            $('#kt_modal_manage_scores').modal('show');
        });

        $('#formScore').on('submit', function (e) {
            e.preventDefault();
            var btn = $('#btnSubmitScore');
            btn.prop('disabled', true);

            $.ajax({
                url: window.routes.storeScore,
                type: 'POST',
                data: $(this).serialize(),
                success: function (res) {
                    $('#formScore')[0].reset();
                    var itemId = $('#score_item_id').val();
                    $('#score_item_id').val(itemId);

                    loadScores(itemId);
                    toastr.success(res.success);
                },
                error: function (xhr) {
                    toastr.error("Gagal menyiman kriteria. Pastikan nilai / label diisi dengan benar.");
                },
                complete: function () {
                    btn.prop('disabled', false);
                }
            });
        });

        $(document).on('click', '.deleteScore', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var itemId = $('#score_item_id').val();

            $.ajax({
                url: window.routes.deleteScore + '/' + id,
                type: 'DELETE',
                data: { _token: csrf_token },
                success: function (res) {
                    loadScores(itemId);
                    toastr.success(res.success);
                }
            });
        });
    }

    var loadScores = function (itemId) {
        var tbody = $('#scoreList');
        tbody.empty().append('<tr><td colspan="4" class="text-center">Loading...</td></tr>');

        $.ajax({
            url: window.routes.getScores + '/' + itemId,
            type: 'GET',
            success: function (res) {
                tbody.empty();
                if (res.scores.length === 0) {
                    tbody.append('<tr><td colspan="4" class="text-center text-muted">Belum ada kriteria penilaian.</td></tr>');
                    return;
                }

                res.scores.forEach(function (sc) {
                    var rangeText = '-';
                    if (sc.min_value !== null && sc.max_value !== null) {
                        rangeText = sc.min_value + ' - ' + sc.max_value;
                    } else if (sc.min_value !== null) {
                        rangeText = '&ge; ' + sc.min_value;
                    } else if (sc.max_value !== null) {
                        rangeText = '&le; ' + sc.max_value;
                    }

                    var tr = `
                        <tr>
                            <td><span class="badge badge-light-primary fs-6">${rangeText}</span></td>
                            <td><span class="text-gray-800 fw-semibold">${sc.label}</span></td>
                            <td class="text-center"><span class="badge badge-primary fs-5">${sc.score}</span></td>
                            <td class="text-end">
                                <a href="#" data-id="${sc.id}" class="btn btn-sm btn-icon btn-light-danger deleteScore"><i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></a>
                            </td>
                        </tr>
                    `;
                    tbody.append(tr);
                });
            }
        });
    }

    // Modal Reset
    $('#kt_modal_category').on('hidden.bs.modal', function () {
        $('#formCategory')[0].reset();
        $('#category_id').val('');
        $('#cabor_id').val('').trigger('change');
        $('.is-invalid').removeClass('is-invalid');
        $('.select2-container').removeClass('is-invalid');
    });

    $('#filter_cabor_id').on('change', function () {
        datatable.ajax.reload();
    });

    return {
        init: function () {
            table = document.querySelector('#table_category');
            if (!table) return;

            formCategory = document.querySelector('#formCategory');
            submitCategory = document.querySelector('#btnSubmitCategory');

            initDatatable();
            handleCategorySubmit();
            initManageItems();
            initManageScores();
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTJenisTesController.init();
});
