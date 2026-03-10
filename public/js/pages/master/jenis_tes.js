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
            $('#manageCategoryLabel').text(name);
            $('#item_category_id').val(id);
            $('#formItem')[0].reset();
            $('#item_sasaran').val('').trigger('change');
            $('#formItem .is-invalid').removeClass('is-invalid');
            $('#formItem .select2-container').removeClass('is-invalid');

            loadItems(id);
            $('#kt_modal_manage_items').modal('show');
        });

        $('#formItem').on('submit', function (e) {
            e.preventDefault();
            var btn = $('#btnSubmitItem');
            $('#formItem .is-invalid').removeClass('is-invalid');
            $('#formItem .select2-container').removeClass('is-invalid');
            btn.prop('disabled', true);

            $.ajax({
                url: window.routes.storeItem,
                type: 'POST',
                data: $(this).serialize(),
                success: function (res) {
                    $('#formItem')[0].reset();
                    $('#item_sasaran').val('').trigger('change');
                    $('#formItem .is-invalid').removeClass('is-invalid');
                    $('#formItem .select2-container').removeClass('is-invalid');

                    // keep category id
                    var cId = $('#item_category_id').val();
                    $('#item_category_id').val(cId);

                    loadItems(cId);
                    datatable.ajax.reload(null, false); // refresh count
                    toastr.success(res.success);
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors || {};
                        var firstMessage = null;
                        var fieldMap = {
                            name: '#item_name',
                            jenis_disabilitas_id: '#item_sasaran',
                            satuan: '#item_satuan',
                            physical_test_category_id: '#item_category_id'
                        };

                        $.each(errors, function (key, value) {
                            var el = $(fieldMap[key] || ('#' + key));
                            el.addClass('is-invalid');
                            if (el.is("select")) el.next('.select2-container').addClass('is-invalid');
                            if (!firstMessage && value && value.length) firstMessage = value[0];
                        });

                        toastr.error(firstMessage || "Validasi gagal. Cek kembali data item tes.");
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
            $('#scoreItemContext').text(name);
            $('#score_item_id').val(id);
            $('#formScore')[0].reset();
            $('#formScore .is-invalid').removeClass('is-invalid');

            loadScores(id);
            $('#kt_modal_manage_scores').modal('show');
        });

        $('#formScore').on('submit', function (e) {
            e.preventDefault();
            var btn = $('#btnSubmitScore');
            $('#formScore .is-invalid').removeClass('is-invalid');
            btn.prop('disabled', true);

            $.ajax({
                url: window.routes.storeScore,
                type: 'POST',
                data: $(this).serialize(),
                success: function (res) {
                    $('#formScore')[0].reset();
                    var itemId = $('#score_item_id').val();
                    $('#score_item_id').val(itemId);
                    $('#formScore .is-invalid').removeClass('is-invalid');

                    loadScores(itemId);
                    toastr.success(res.success);
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors || {};
                        var fieldMap = {
                            min_value: '#score_min',
                            max_value: '#score_max',
                            label: '#score_label',
                            score: '#score_value',
                            physical_test_item_id: '#score_item_id'
                        };
                        var firstMessage = null;

                        $.each(errors, function (key, value) {
                            var el = $(fieldMap[key] || ('#' + key));
                            el.addClass('is-invalid');
                            if (!firstMessage && value && value.length) firstMessage = value[0];
                        });

                        toastr.error(firstMessage || "Validasi gagal. Cek kembali data kriteria.");
                    } else {
                        toastr.error("Gagal menyimpan kriteria. Pastikan nilai dan label sudah benar.");
                    }
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

    $('#kt_modal_manage_items').on('shown.bs.modal', function () {
        $('#item_name').trigger('focus');
    });

    $('#kt_modal_manage_scores').on('shown.bs.modal', function () {
        $('#score_label').trigger('focus');
    });

    $('#filter_cabor_id').on('change', function () {
        datatable.ajax.reload();
    });

    /* --- VIEW DETAIL --- */
    var initViewCategory = function () {
        $(document).on('click', '.viewCategory', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            // Reset and open modal
            $('#detailCategoryName').text(name);
            $('#detailCaborBadge').text('Memuat...');
            $('#detailItemCount').text('...');
            $('#detailScoreCount').text('...');
            $('#detailItemsContainer').html(
                '<div class="d-flex justify-content-center py-10">' +
                '<span class="spinner-border text-primary" style="width:2.5rem;height:2.5rem;"></span>' +
                '</div>'
            );
            $('#detailEmptyState').addClass('d-none');
            $('#kt_modal_detail').modal('show');

            // Fetch detail
            $.ajax({
                url: window.routes.getDetail + '/' + id,
                type: 'GET',
                success: function (res) {
                    var cat = res.data;
                    $('#detailCaborBadge').text(cat.cabor ? cat.cabor.name : '-');

                    var items = cat.items || [];
                    var totalScores = 0;
                    items.forEach(function (i) { totalScores += (i.scores || []).length; });

                    $('#detailItemCount').text(items.length + ' Item Tes');
                    $('#detailScoreCount').text(totalScores + ' Kriteria Penilaian');

                    if (items.length === 0) {
                        $('#detailItemsContainer').html('');
                        $('#detailEmptyState').removeClass('d-none');
                        return;
                    }

                    var html = '';
                    items.forEach(function (item, idx) {
                        var disabilitas = item.jenis_disabilitas
                            ? '<span class="badge badge-light-info fw-semibold ms-2">' + item.jenis_disabilitas.nama_jenis + '</span>'
                            : '<span class="badge badge-light-secondary fw-semibold ms-2">Semua Jenis</span>';

                        var satuan = item.satuan
                            ? '<span class="badge badge-light fw-normal ms-1">' + item.satuan + '</span>'
                            : '';

                        var scoreHtml = '';
                        var scores = item.scores || [];

                        if (scores.length > 0) {
                            scoreHtml = '<div class="d-flex flex-column gap-2 mt-3">';
                            scores.forEach(function (s) {
                                var chipClass = 'score-chip-default';
                                if (s.score >= 4) chipClass = 'score-chip-4';
                                else if (s.score == 3) chipClass = 'score-chip-3';
                                else if (s.score == 2) chipClass = 'score-chip-2';
                                else if (s.score == 1) chipClass = 'score-chip-1';

                                var rangeMin = s.min_value !== null ? '≥ ' + s.min_value : '';
                                var rangeMax = s.max_value !== null ? '≤ ' + s.max_value : '';
                                var rangeText = '';
                                if (rangeMin && rangeMax) {
                                    rangeText = s.min_value + ' – ' + s.max_value;
                                } else {
                                    rangeText = rangeMin || rangeMax || '—';
                                }

                                scoreHtml += '<div class="d-flex align-items-center gap-3 flex-wrap">' +
                                    '<span class="score-legend-badge" style="min-width:90px;">' + rangeText + (item.satuan ? ' ' + item.satuan : '') + '</span>' +
                                    '<i class="fas fa-long-arrow-alt-right text-muted" style="font-size:11px;"></i>' +
                                    '<span class="score-row-chip ' + chipClass + '">' + s.label + '</span>' +
                                    '<span class="fw-bold text-muted fs-8 ms-auto">Skor ' + s.score + '</span>' +
                                    '</div>';
                            });
                            scoreHtml += '</div>';
                        } else {
                            scoreHtml = '<div class="detail-no-scores mt-3"><i class="fas fa-info-circle me-2"></i>Belum ada kriteria penilaian untuk item ini.</div>';
                        }

                        html += '<div class="detail-item-card">' +
                            '<div class="d-flex align-items-start gap-3">' +
                            '<div class="detail-item-badge">' + (idx + 1) + '</div>' +
                            '<div class="flex-grow-1">' +
                            '<div class="d-flex align-items-center flex-wrap gap-1">' +
                            '<span class="fw-bold text-gray-900 fs-6">' + item.name + '</span>' +
                            satuan + disabilitas +
                            '</div>' +
                            '<div class="jenis-tes-section-label mt-3 mb-1">Kriteria Penilaian (' + scores.length + ')</div>' +
                            scoreHtml +
                            '</div>' +
                            '</div>' +
                            '</div>';
                    });

                    $('#detailItemsContainer').html(html);
                },
                error: function () {
                    $('#detailItemsContainer').html(
                        '<div class="alert alert-danger">Gagal memuat data detail. Silakan coba lagi.</div>'
                    );
                }
            });
        });
    };

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
            initViewCategory();
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTJenisTesController.init();
});
