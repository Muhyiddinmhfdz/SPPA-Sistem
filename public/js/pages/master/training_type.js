"use strict";

var TrainingTypePage = (function () {
    var table;
    var currentTypeId = null;

    var initDataTable = function () {
        table = $("#table_training_type").DataTable({
            processing: true,
            serverSide: true,
            ajax: window.routes.index,
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

    return {
        init: function () {
            initDataTable();
            handleTrainingTypeForm();
            handleComponents();
        },
    };
})();

$(document).ready(function () {
    TrainingTypePage.init();
});
