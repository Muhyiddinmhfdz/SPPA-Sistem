"use strict";

var KTTesPerformaController = function () {
    var table;
    var datatable;

    /* ── Datatable ── */
    var initDatatable = function () {
        datatable = $(table).DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[0, 'desc']],
            ajax: {
                url: window.routes.index,
                data: function (d) {
                    d.cabor_id = $('#filter_cabor_id').val();
                    d.atlet_id = $('#filter_atlet_id').val();
                    d.tanggal_from = $('#filter_tanggal_from').val();
                    d.tanggal_to = $('#filter_tanggal_to').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'ps-4' },
                { data: 'tanggal', name: 'tanggal_pelaksanaan' },
                { data: 'atlet_name', name: 'atlet.name' },
                { data: 'cabor_name', name: 'cabor.name' },
                { data: 'disabilitas', name: 'disabilitas', orderable: false },
                { data: 'status_badge', name: 'status_kesehatan', className: 'text-center', orderable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' },
            ],
            language: {
                info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                emptyTable: "Belum ada data hasil tes performa",
                sLengthMenu: "Tampilkan _MENU_ data",
                search: "Cari:"
            }
        });

        // Filter triggers
        $('#filter_cabor_id, #filter_atlet_id, #filter_tanggal_from, #filter_tanggal_to').on('change', function () {
            datatable.ajax.reload();
        });
        $('#btnResetFilter').on('click', function () {
            $('#filter_cabor_id, #filter_atlet_id').val('').trigger('change');
            $('#filter_tanggal_from, #filter_tanggal_to').val('').trigger('change');
        });
    };

    /* ── Atlet selection auto-fill ── */
    var initAtletSelect = function () {
        $('#atlet_id').on('change', function () {
            var atletId = $(this).val();
            if (!atletId) {
                $('#cabor_id, #klasifikasi_disabilitas_id, #jenis_disabilitas_id').val('');
                $('#infoAtletCabor').text('-');
                $('#infoAtletDisabilitas').text('-');
                $('#testItemsPlaceholder').removeClass('d-none');
                $('#testItemsContainer').addClass('d-none').html('');
                return;
            }

            // Auto-fill hidden fields from data attrs
            var opt = $('#atlet_id option:selected');
            $('#cabor_id').val(opt.data('cabor'));
            $('#klasifikasi_disabilitas_id').val(opt.data('klasifikasi'));
            $('#jenis_disabilitas_id').val(opt.data('disabilitas'));

            // Fetch atlet details
            $.get(window.routes.atletData + '/' + atletId, function (res) {
                var atlet = res.atlet;
                $('#infoAtletCabor').text(atlet.cabor ? atlet.cabor.name : '-');
                $('#infoAtletDisabilitas').text(atlet.jenis_disabilitas ? atlet.jenis_disabilitas.nama_jenis : 'Tidak ada data');
            });

            // Load test items
            loadTestItems(atletId);
        });
    };

    /* ── Load test items for atlet ── */
    var loadTestItems = function (atletId) {
        $('#testItemsPlaceholder').addClass('d-none');
        $('#testItemsContainer').removeClass('d-none').html(
            '<div class="d-flex justify-content-center py-8"><span class="spinner-border text-primary"></span></div>'
        );

        $.get(window.routes.testItems + '/' + atletId, function (res) {
            var categories = res.categories;
            if (!categories || categories.length === 0) {
                $('#testItemsContainer').html(
                    '<div class="text-center text-muted py-8"><i class="fas fa-info-circle fs-3 mb-2"></i><br>Tidak ada komponen tes untuk cabor atlet ini.</div>'
                );
                return;
            }

            var html = '';
            categories.forEach(function (cat) {
                if (!cat.items || cat.items.length === 0) return;
                html += '<div class="mb-4">';
                html += '<div class="tp-category-header"><i class="ki-duotone ki-category fs-4 text-white opacity-70 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>' + cat.name + '</div>';

                cat.items.forEach(function (item) {
                    var disabilitas = item.jenis_disabilitas
                        ? '<span class="badge badge-light-info fs-8 me-2">' + item.jenis_disabilitas.nama_jenis + '</span>'
                        : '<span class="badge badge-light-secondary fs-8 me-2">Semua</span>';

                    var scoresData = JSON.stringify(item.scores);

                    html += '<div class="tp-item-row">';
                    html += '<div class="tp-item-name">' + item.name + ' ' + disabilitas + '</div>';
                    html += '<div class="tp-item-satuan text-muted fs-8">' + (item.satuan || '-') + '</div>';
                    html += '<input type="number" step="any" name="results[' + item.id + ']"'
                        + ' class="form-control form-control-solid form-control-sm tp-item-input tp-nilai-input"'
                        + ' placeholder="Nilai" data-item-id="' + item.id + '"'
                        + ' data-scores=\'' + escapeHtml(scoresData) + '\'>';
                    html += '<span class="tp-score-pill tp-score-na tp-item-badge" id="score-badge-' + item.id + '">—</span>';
                    html += '</div>';
                });

                html += '</div>';
            });

            $('#testItemsContainer').html(html);
            initScoreAutoResolve();
        }).fail(function () {
            $('#testItemsContainer').html('<div class="alert alert-danger">Gagal memuat komponen tes.</div>');
        });
    };

    /* ── Auto-resolve score badge on nilai input ── */
    var initScoreAutoResolve = function () {
        $(document).off('input', '.tp-nilai-input').on('input', '.tp-nilai-input', function () {
            var nilai = parseFloat($(this).val());
            var itemId = $(this).data('item-id');
            var scores = $(this).data('scores');
            var badge = $('#score-badge-' + itemId);

            if (isNaN(nilai) || $(this).val() === '') {
                badge.text('—').removeClass().addClass('tp-score-pill tp-score-na tp-item-badge');
                return;
            }

            var matched = null;
            (scores || []).forEach(function (s) {
                if (matched) return;
                var minOk = s.min_value === null || nilai >= s.min_value;
                var maxOk = s.max_value === null || nilai <= s.max_value;
                if (minOk && maxOk) matched = s;
            });

            if (matched) {
                var cls = 'tp-score-na';
                if (matched.score >= 4) cls = 'tp-score-4';
                else if (matched.score == 3) cls = 'tp-score-3';
                else if (matched.score == 2) cls = 'tp-score-2';
                else if (matched.score == 1) cls = 'tp-score-1';
                badge.text(matched.label + ' (' + matched.score + ')').removeClass().addClass('tp-score-pill ' + cls + ' tp-item-badge');
            } else {
                badge.text('Tidak ada kriteria').removeClass().addClass('tp-score-pill tp-score-na tp-item-badge');
            }
        });
    };

    var escapeHtml = function (str) {
        return str.replace(/'/g, '&apos;').replace(/"/g, '&quot;');
    };

    /* ── Save (store/update) ── */
    var initSave = function () {
        $('#btnSavePerformance').on('click', function () {
            var btn = $(this);
            btn.attr('data-kt-indicator', 'on').prop('disabled', true);

            var id = $('#performance_id').val();
            var method = id ? 'PUT' : 'POST';
            var url = id ? window.routes.update + '/' + id : window.routes.store;

            var formData = $('#formPerformance').serializeArray();
            formData.push({ name: '_method', value: method });

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function (res) {
                    $('#kt_modal_performance').modal('hide');
                    datatable.ajax.reload();
                    Swal.fire({ text: res.success, icon: 'success', buttonsStyling: false, confirmButtonText: 'Ok!', customClass: { confirmButton: 'btn btn-primary' } });
                },
                error: function (xhr) {
                    var msg = xhr.responseJSON?.error || xhr.responseJSON?.message || 'Terjadi kesalahan.';
                    Swal.fire({ text: msg, icon: 'error', buttonsStyling: false, confirmButtonText: 'Ok!', customClass: { confirmButton: 'btn btn-danger' } });
                },
                complete: function () {
                    btn.removeAttr('data-kt-indicator').prop('disabled', false);
                }
            });
        });
    };

    /* ── View Detail ── */
    var initView = function () {
        $(document).on('click', '.viewPerformance', function () {
            var id = $(this).data('id');
            $('#performanceDetailContent').html('<div class="d-flex justify-content-center py-10"><span class="spinner-border text-primary" style="width:2.5rem;height:2.5rem;"></span></div>');
            $('#detailAtletName').text('Memuat...');
            $('#detailTanggal').text('');
            $('#kt_modal_performance_detail').modal('show');

            $.get(window.routes.show + '/' + id, function (res) {
                var d = res.data;
                $('#detailAtletName').text(d.atlet ? d.atlet.name : '-');
                $('#detailTanggal').text(d.tanggal_pelaksanaan + ' · ' + (d.cabor ? d.cabor.name : '-'));

                var html = '<div class="row g-4 mb-5">';
                html += '<div class="col-sm-4"><div class="tp-section-label">Penguji</div><div class="fw-bold fs-7">' + (d.penguji || '-') + '</div></div>';
                html += '<div class="col-sm-4"><div class="tp-section-label">Status Kesehatan</div>';
                var sc = { fit: 'success', cidera: 'danger', rehabilitasi: 'warning' };
                html += '<span class="badge badge-light-' + (sc[d.status_kesehatan] || 'secondary') + ' fw-bold">' + (d.status_kesehatan || '-') + '</span></div>';
                html += '<div class="col-sm-4"><div class="tp-section-label">Alat Bantu</div><div class="fw-bold fs-7">' + (d.alat_bantu || 'Tidak ada') + '</div></div>';
                html += '<div class="col-sm-4"><div class="tp-section-label">Spesialisasi</div><div class="fw-bold fs-7">' + (d.spesialisasi || '-') + '</div></div>';
                html += '<div class="col-sm-4"><div class="tp-section-label">Jenis Disabilitas</div><div class="fw-bold fs-7">' + (d.jenis_disabilitas ? d.jenis_disabilitas.nama_jenis : 'Semua') + '</div></div>';
                html += '</div>';

                // Group results by category
                var groups = {};
                (d.results || []).forEach(function (r) {
                    var catName = (r.physical_test_item && r.physical_test_item.category) ? r.physical_test_item.category.name : 'Lainnya';
                    if (!groups[catName]) groups[catName] = [];
                    groups[catName].push(r);
                });

                Object.keys(groups).forEach(function (catName) {
                    html += '<div class="tp-category-header mb-2">' + catName + '</div>';
                    html += '<div class="mb-4">';
                    groups[catName].forEach(function (r) {
                        var score = r.physical_test_item_score;
                        var sc_val = score ? score.score : null;
                        var cls = 'tp-score-na';
                        if (sc_val >= 4) cls = 'tp-score-4';
                        else if (sc_val == 3) cls = 'tp-score-3';
                        else if (sc_val == 2) cls = 'tp-score-2';
                        else if (sc_val == 1) cls = 'tp-score-1';

                        html += '<div class="tp-item-row">';
                        html += '<div class="tp-item-name">' + (r.physical_test_item ? r.physical_test_item.name : '-') + '</div>';
                        html += '<div class="fw-bold fs-7 text-gray-700">' + (r.nilai !== null ? r.nilai : '-') + '</div>';
                        html += '<span class="tp-score-pill ' + cls + ' min-w-100px text-center">' + (score ? score.label + ' (' + sc_val + ')' : '—') + '</span>';
                        html += '</div>';
                    });
                    html += '</div>';
                });

                if (!d.results || d.results.length === 0) {
                    html += '<div class="text-center text-muted py-6">Belum ada hasil tes yang diinputkan.</div>';
                }

                $('#performanceDetailContent').html(html);
            }).fail(function () {
                $('#performanceDetailContent').html('<div class="alert alert-danger">Gagal memuat detail.</div>');
            });
        });
    };

    /* ── Edit ── */
    var initEdit = function () {
        $(document).on('click', '.editPerformance', function () {
            var id = $(this).data('id');
            $.get(window.routes.edit + '/' + id + '/edit', function (res) {
                var d = res.data;
                $('#modalPerformanceTitle').text('Edit Tes Performa');
                $('#performance_id').val(d.id);
                $('#atlet_id').val(d.atlet_id).trigger('change');

                // Wait briefly for atlet change to load test items
                setTimeout(function () {
                    $('#tanggal_pelaksanaan').val(d.tanggal_pelaksanaan);
                    $('#status_kesehatan').val(d.status_kesehatan).trigger('change');
                    $('#alat_bantu').val(d.alat_bantu || '').trigger('change');
                    $('#spesialisasi').val(d.spesialisasi || '');
                    $('#penguji').val(d.penguji || '');

                    // Fill nilai values after items are loaded
                    setTimeout(function () {
                        (d.results || []).forEach(function (r) {
                            $('input[name="results[' + r.physical_test_item_id + ']"]').val(r.nilai).trigger('input');
                        });
                    }, 1200);
                }, 300);

                $('#kt_modal_performance').modal('show');
            });
        });
    };

    /* ── Delete ── */
    var initDelete = function () {
        $(document).on('click', '.deletePerformance', function () {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Hapus data tes ini?',
                text: 'Data yang sudah dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                buttonsStyling: false,
                customClass: { confirmButton: 'btn btn-danger me-3', cancelButton: 'btn btn-light' }
            }).then(function (result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: window.routes.destroy + '/' + id,
                        type: 'POST',
                        data: { _method: 'DELETE', _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (res) {
                            datatable.ajax.reload();
                            Swal.fire({ text: res.success, icon: 'success', buttonsStyling: false, confirmButtonText: 'Ok!', customClass: { confirmButton: 'btn btn-primary' } });
                        },
                        error: function () {
                            Swal.fire({ text: 'Gagal menghapus data.', icon: 'error', buttonsStyling: false, confirmButtonText: 'Ok!', customClass: { confirmButton: 'btn btn-danger' } });
                        }
                    });
                }
            });
        });
    };

    /* ── Reset modal on close ── */
    var initReset = function () {
        $('#kt_modal_performance').on('hidden.bs.modal', function () {
            $('#formPerformance')[0].reset();
            $('#performance_id').val('');
            $('#modalPerformanceTitle').text('Input Tes Performa');
            $('#atlet_id').val('').trigger('change');
            $('#status_kesehatan').val('fit').trigger('change');
            $('#alat_bantu').val('').trigger('change');
            $('#infoAtletCabor, #infoAtletDisabilitas').text('-');
            $('#testItemsPlaceholder').removeClass('d-none');
            $('#testItemsContainer').addClass('d-none').html('');
        });
    };

    return {
        init: function () {
            table = document.querySelector('#table_performance');
            if (!table) return;

            initDatatable();
            initAtletSelect();
            initSave();
            initView();
            initEdit();
            initDelete();
            initReset();
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTTesPerformaController.init();
});
