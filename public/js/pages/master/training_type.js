"use strict";

var TrainingTypePage = (function () {
    var table;
    var currentTypeId = null;

    var initDataTable = function () {
        table = $("#table_training_type").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: window.routes.index,
                data: function (d) {
                    d.cabor_id = $("#filter_cabor_id").val();
                }
            },
            columns: [
                { data: "DT_RowIndex", name: "DT_RowIndex", class: "ps-4", orderable: false, searchable: false },
                { data: "name", name: "name" },
                { data: "cabor_name", name: "cabor_name" },
                { data: "components_count", name: "components_count", class: "text-center" },
                { data: "action", name: "action", class: "text-center", orderable: false, searchable: false },
            ],
            language: {
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                processing: "Sedang memproses...",
            },
        });

        $("#filter_cabor_id").on("change", function () {
            table.ajax.reload();
        });
    };

    var handleTrainingTypeForm = function () {
        $("#btnTambahType").click(function () {
            $("#formTrainingType")[0].reset();
            $("#type_id").val("");
            $("#cabor_id").val(null).trigger("change");
            $("#modalTitle").text("Tambah Jenis Latihan");
        });

        $("#formTrainingType").submit(function (e) {
            e.preventDefault();
            var id = $("#type_id").val();
            var url = id ? window.routes.index + "/" + id : window.routes.store;
            var method = id ? "PUT" : "POST";

            var btn = $("#btnSubmitType");
            btn.attr("data-kt-indicator", "on").prop("disabled", true);

            $.ajax({
                url: url,
                method: method,
                data: $(this).serialize(),
                success: function (res) {
                    Swal.fire({ text: res.success, icon: "success", buttonsStyling: false, confirmButtonText: "Ok, mengerti!", customClass: { confirmButton: "btn btn-primary" } });
                    $("#kt_modal_training_type").modal("hide");
                    table.ajax.reload();
                },
                error: function (err) {
                    var msg = err.responseJSON ? err.responseJSON.error : "Terjadi kesalahan";
                    Swal.fire({ text: msg, icon: "error", buttonsStyling: false, confirmButtonText: "Ok", customClass: { confirmButton: "btn btn-danger" } });
                },
                complete: function () {
                    btn.removeAttr("data-kt-indicator").prop("disabled", false);
                },
            });
        });

        $(document).on("click", ".editTrainingType", function () {
            var id = $(this).data("id");
            $.get(window.routes.index + "/" + id + "/edit", function (data) {
                $("#type_id").val(data.id);
                $("#name").val(data.name);
                $("#cabor_id").val(data.cabor_id).trigger("change");
                $("#modalTitle").text("Edit Jenis Latihan");
                $("#kt_modal_training_type").modal("show");
            });
        });

        $(document).on("click", ".deleteTrainingType", function () {
            var id = $(this).data("id");
            Swal.fire({
                text: "Apakah Anda yakin ingin menonaktifkan jenis latihan ini?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
                customClass: { confirmButton: "btn btn-danger", cancelButton: "btn btn-active-light" },
            }).then(function (result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: window.routes.index + "/" + id,
                        method: "DELETE",
                        data: { _token: $('meta[name="csrf-token"]').attr("content") },
                        success: function (res) {
                            Swal.fire({ text: res.success, icon: "success", confirmButtonText: "Ok", customClass: { confirmButton: "btn btn-primary" } });
                            table.ajax.reload();
                        },
                    });
                }
            });
        });
    };

    var handleViewDetail = function () {
        $(document).on("click", ".viewTrainingType", function () {
            var id = $(this).data("id");
            var container = $("#view_components_container");
            container.html('<div class="text-center py-10"><span class="spinner-border text-primary"></span></div>');
            $("#kt_modal_view_training_type").modal("show");

            $.get(window.routes.show + "/" + id, function (res) {
                $("#view_type_name").text(res.name);
                $("#view_cabor_name").text(res.cabor ? res.cabor.name : "-");

                var html = "";
                if (!res.components || res.components.length === 0) {
                    html = '<div class="text-center text-muted py-5">Belum ada komponen latihan untuk jenis ini.</div>';
                } else {
                    res.components.forEach(function (comp) {
                        html += `
                            <div class="mb-8">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="symbol symbol-30px symbol-circle me-3">
                                        <span class="symbol-label bg-light-primary">
                                            <i class="ki-duotone ki-abstract-26 fs-4 text-primary"><span class="path1"></span><span class="path2"></span></i>
                                        </span>
                                    </div>
                                    <h4 class="text-gray-800 fw-bold mb-0">${comp.name}</h4>
                                </div>
                                <div class="ps-10">
                                    <table class="table table-sm table-row-dashed table-row-gray-200 align-middle gs-0 gy-2">
                                        <thead>
                                            <tr class="fw-bold text-muted fs-8 text-uppercase">
                                                <th class="min-w-100px italic">Rentang Nilai</th>
                                                <th class="min-w-150px">Keterangan</th>
                                                <th class="w-80px text-center">Skor</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;

                        if (!comp.scores || comp.scores.length === 0) {
                            html += '<tr><td colspan="3" class="text-center text-muted fs-8">Belum ada kriteria penilaian.</td></tr>';
                        } else {
                            comp.scores.forEach(function (score) {
                                var range = "";
                                if (score.min_value !== null && score.max_value !== null) {
                                    range = `${score.min_value} - ${score.max_value}`;
                                } else if (score.min_value !== null) {
                                    range = `&ge; ${score.min_value}`;
                                } else if (score.max_value !== null) {
                                    range = `&le; ${score.max_value}`;
                                } else {
                                    range = "-";
                                }

                                html += `
                                    <tr>
                                        <td class="fs-7 fw-bold text-gray-800">${range}</td>
                                        <td class="fs-7 text-gray-600">${score.label}</td>
                                        <td class="text-center">
                                            <span class="badge badge-light-success fs-8 fw-bold">${score.score}</span>
                                        </td>
                                    </tr>`;
                            });
                        }

                        html += `
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        `;
                    });
                }
                container.html(html);
            });
        });
    };

    var handleComponents = function () {
        $(document).on("click", ".manageComponents", function () {
            var id = $(this).data("id");
            currentTypeId = id;
            $("#comp_training_type_id").val(id);
            loadComponents(id);
            $("#kt_modal_manage_components").modal("show");
        });

        function loadComponents(typeId) {
            $.get(window.routes.getComponents + "/" + typeId, function (data) {
                var html = "";
                if (data.length === 0) {
                    html = '<tr><td colspan="3" class="text-center text-muted fs-7 py-5">Belum ada komponen latihan.</td></tr>';
                } else {
                    data.forEach(function (item, index) {
                        html += `
                            <tr>
                                <td class="fw-bold">${index + 1}</td>
                                <td class="text-gray-900 fw-semibold">${item.name}</td>
                                <td class="text-end">
                                    <button class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm manageCriteria me-1" data-id="${item.id}" data-name="${item.name}" title="Manage Scoring Criteria">
                                        <i class="ki-duotone ki-chart-line-star fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    </button>
                                    <button class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm deleteComponent" data-id="${item.id}">
                                        <i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                }
                $("#componentList").html(html);
            });
        }

        $("#formComponent").submit(function (e) {
            e.preventDefault();
            var btn = $("#btnSubmitComp");
            btn.attr("data-kt-indicator", "on").prop("disabled", true);

            $.ajax({
                url: window.routes.storeComponent,
                method: "POST",
                data: $(this).serialize(),
                success: function (res) {
                    $("#comp_name").val("");
                    loadComponents(currentTypeId);
                    table.ajax.reload(null, false); // Reload main table without resetting pagination
                },
                error: function (err) {
                    var msg = err.responseJSON ? err.responseJSON.error : "Gagal menambahkan komponen";
                    Swal.fire({ text: msg, icon: "error", confirmButtonText: "Ok", customClass: { confirmButton: "btn btn-danger" } });
                },
                complete: function () {
                    btn.removeAttr("data-kt-indicator").prop("disabled", false);
                },
            });
        });

        $(document).on("click", ".deleteComponent", function () {
            var id = $(this).data("id");
            $.ajax({
                url: window.routes.deleteComponent + "/" + id,
                method: "DELETE",
                data: { _token: $('meta[name="csrf-token"]').attr("content") },
                success: function (res) {
                    loadComponents(currentTypeId);
                    table.ajax.reload(null, false);
                },
            });
        });
    };

    var handleCriteria = function () {
        var currentCompId = null;

        $(document).on("click", ".manageCriteria", function () {
            var id = $(this).data("id");
            var name = $(this).data("name");
            currentCompId = id;
            $("#crit_component_id").val(id);
            $("#compLabel").text(name);
            loadCriteria(id);
            $("#kt_modal_manage_criteria").modal("show");
        });

        function loadCriteria(compId) {
            $.get(window.routes.getScores + "/" + compId, function (data) {
                var html = "";
                if (data.length === 0) {
                    html = '<tr><td colspan="4" class="text-center text-muted fs-7 py-5">Belum ada kriteria penilaian.</td></tr>';
                } else {
                    data.forEach(function (item) {
                        var range = "";
                        if (item.min_value !== null && item.max_value !== null) {
                            range = `${item.min_value} - ${item.max_value}`;
                        } else if (item.min_value !== null) {
                            range = `&ge; ${item.min_value}`;
                        } else if (item.max_value !== null) {
                            range = `&le; ${item.max_value}`;
                        } else {
                            range = "-";
                        }

                        html += `
                            <tr>
                                <td class="text-gray-900 fw-bold">${range}</td>
                                <td class="text-gray-700 fw-semibold">${item.label}</td>
                                <td class="text-center">
                                    <span class="badge badge-light-success fw-bold fs-7">${item.score}</span>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm deleteCriteria" data-id="${item.id}">
                                        <i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                }
                $("#criteriaList").html(html);
            });
        }

        $("#formCriteria").submit(function (e) {
            e.preventDefault();
            var btn = $("#btnSubmitCriteria");
            btn.prop("disabled", true);

            $.ajax({
                url: window.routes.storeScore,
                method: "POST",
                data: $(this).serialize(),
                success: function (res) {
                    $("#formCriteria")[0].reset();
                    $("#crit_component_id").val(currentCompId); // Re-set because reset() cleared it
                    loadCriteria(currentCompId);
                },
                error: function (err) {
                    var msg = err.responseJSON ? err.responseJSON.error : "Gagal menambahkan kriteria";
                    Swal.fire({ text: msg, icon: "error", confirmButtonText: "Ok", customClass: { confirmButton: "btn btn-danger" } });
                },
                complete: function () {
                    btn.prop("disabled", false);
                },
            });
        });

        $(document).on("click", ".deleteCriteria", function () {
            var id = $(this).data("id");
            $.ajax({
                url: window.routes.deleteScore + "/" + id,
                method: "DELETE",
                data: { _token: $('meta[name="csrf-token"]').attr("content") },
                success: function (res) {
                    loadCriteria(currentCompId);
                },
            });
        });
    };

    return {
        init: function () {
            initDataTable();
            handleTrainingTypeForm();
            handleViewDetail();
            handleComponents();
            handleCriteria();
        },
    };
})();

$(document).ready(function () {
    TrainingTypePage.init();
});
