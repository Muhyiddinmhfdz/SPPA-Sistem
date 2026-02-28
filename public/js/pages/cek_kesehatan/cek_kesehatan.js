'use strict';

const CK_URLS = {
    atlet: '/cek-kesehatan/datatable/atlet',
    pelatih: '/cek-kesehatan/datatable/pelatih',
    persons: '/cek-kesehatan/persons',
    store: '/cek-kesehatan',
    update: (id) => `/cek-kesehatan/${id}`,
    edit: (id) => `/cek-kesehatan/${id}/edit`,
    destroy: (id) => `/cek-kesehatan/${id}`,
};

const dtCols = [
    { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'ps-4' },
    { data: 'nama' },
    { data: 'cabor_name' },
    { data: 'tanggal_fmt' },
    { data: 'kondisi_badge', orderable: false },
    { data: 'cedera_badge', orderable: false },
    { data: 'kesimpulan_badge', orderable: false },
    { data: 'action', orderable: false, className: 'text-center' },
];

function buildParams(extra = {}) {
    return {
        cabor_id: $('#filterCabor').val(),
        person_id: $('#filterPerson').val(),
        ...extra,
    };
}

// ── DataTables ───────────────────────────────────────────────────────────────
let dtAtlet, dtPelatih;

function initDtAtlet() {
    dtAtlet = $('#tableAtlet').DataTable({
        processing: true, serverSide: true,
        ajax: { url: CK_URLS.atlet, data: (d) => Object.assign(d, buildParams({ type: 'atlet' })) },
        columns: dtCols,
        language: { processing: '<span class="spinner-border spinner-border-sm"></span> Memuat...' },
        responsive: true,
    });
}

function initDtPelatih() {
    dtPelatih = $('#tablePelatih').DataTable({
        processing: true, serverSide: true,
        ajax: { url: CK_URLS.pelatih, data: (d) => Object.assign(d, buildParams({ type: 'pelatih' })) },
        columns: dtCols,
        language: { processing: '<span class="spinner-border spinner-border-sm"></span> Memuat...' },
        responsive: true,
    });
}

$(function () {
    initDtAtlet();

    // Init pelatih table only when tab is shown
    $('a[href="#tab_pelatih"]').one('shown.bs.tab', function () {
        initDtPelatih();
    });

    // ── Filter ────────────────────────────────────────────────────────────────
    $('#filterCabor').on('change', function () {
        const caborId = $(this).val();
        // also update filter person dropdown
        const activeType = $('a[data-bs-toggle="tab"].active').attr('href') === '#tab_atlet' ? 'atlet' : 'pelatih';
        loadPersonDropdown(activeType, caborId, '#filterPerson');
    });

    $('#btnFilter').on('click', function () {
        dtAtlet?.ajax.reload();
        dtPelatih?.ajax.reload();
    });

    $('#btnResetFilter').on('click', function () {
        $('#filterCabor').val('').trigger('change');
        $('#filterPerson').html('<option value="">-- Semua --</option>');
        dtAtlet?.ajax.reload();
        dtPelatih?.ajax.reload();
    });

    // ── Open modal: Add ───────────────────────────────────────────────────────
    $('#btnTambahCekKesehatan').on('click', function () {
        resetForm();
        $('#modalTitle').text('Input Cek Kesehatan');
        $('#ck_tanggal').val(new Date().toISOString().split('T')[0]);
        const activeType = $('a[data-bs-toggle="tab"].active').attr('href') === '#tab_atlet' ? 'atlet' : 'pelatih';
        $('#ck_person_type').val(activeType).trigger('change');
        $('#modalCekKesehatan').modal('show');
    });

    // ── Person type changes → reload person select in modal ──────────────────
    $('#ck_person_type, #ck_cabor_id').on('change', function () {
        const type = $('#ck_person_type').val();
        const caborId = $('#ck_cabor_id').val();
        loadPersonDropdown(type, caborId, '#ck_person_id');
    });

    // ── Save ─────────────────────────────────────────────────────────────────
    $('#btnSimpanCekKesehatan').on('click', function () {
        const id = $('#ck_id').val();
        const url = id ? CK_URLS.update(id) : CK_URLS.store;
        const method = id ? 'PUT' : 'POST';

        setLoading(true);
        $.ajax({
            url, method,
            data: $('#formCekKesehatan').serialize() + (id ? '&_method=PUT' : ''),
            success(res) {
                setLoading(false);
                $('#modalCekKesehatan').modal('hide');
                Swal.fire({ icon: 'success', title: 'Berhasil', text: res.success, timer: 2000, showConfirmButton: false });
                dtAtlet?.ajax.reload(null, false);
                dtPelatih?.ajax.reload(null, false);
            },
            error(xhr) {
                setLoading(false);
                const msg = xhr.responseJSON?.error || 'Terjadi kesalahan.';
                Swal.fire({ icon: 'error', title: 'Gagal', text: msg });
            }
        });
    });

    // ── Edit ─────────────────────────────────────────────────────────────────
    $(document).on('click', '.editCekKesehatan', function () {
        const id = $(this).data('id');
        $.get(CK_URLS.edit(id), function (data) {
            resetForm();
            $('#modalTitle').text('Edit Cek Kesehatan');
            $('#ck_id').val(data.id);
            $('#ck_person_type').val(data.person_type);
            $('#ck_cabor_id').val(data.cabor_id);

            // Load persons then set value
            loadPersonDropdown(data.person_type, data.cabor_id, '#ck_person_id', data.person_id);

            // Set date via flatpickr
            if (typeof ckDatePicker !== 'undefined') {
                ckDatePicker.setDate(data.tanggal?.split('T')[0] || data.tanggal, true, 'Y-m-d');
            } else {
                $('#ck_tanggal').val(data.tanggal?.split('T')[0] || data.tanggal);
            }
            $(`input[name="kondisi_harian"][value="${data.kondisi_harian}"]`).prop('checked', true);
            $(`input[name="tingkat_cedera"][value="${data.tingkat_cedera}"]`).prop('checked', true);
            $(`input[name="kesimpulan"][value="${data.kesimpulan}"]`).prop('checked', true);
            $('#ck_riwayat').val(data.riwayat_medis);
            $('#ck_catatan').val(data.catatan);
            $('#modalCekKesehatan').modal('show');
        });
    });

    // ── Delete ────────────────────────────────────────────────────────────────
    $(document).on('click', '.deleteCekKesehatan', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Hapus data ini?',
            text: 'Data cek kesehatan akan dinonaktifkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
        }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    url: CK_URLS.destroy(id), method: 'DELETE',
                    data: { _token: $('meta[name="csrf-token"]').attr('content') },
                    success(res) {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: res.success, timer: 1800, showConfirmButton: false });
                        dtAtlet?.ajax.reload(null, false);
                        dtPelatih?.ajax.reload(null, false);
                    },
                    error() {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan.' });
                    }
                });
            }
        });
    });
});

// ── Helpers ──────────────────────────────────────────────────────────────────
function loadPersonDropdown(type, caborId, targetSelector, selectedId = null) {
    $.get(CK_URLS.persons, { type, cabor_id: caborId }, function (persons) {
        let opts = '<option value="">-- Pilih --</option>';
        persons.forEach(p => {
            opts += `<option value="${p.id}" ${selectedId == p.id ? 'selected' : ''}>${p.name}</option>`;
        });
        $(targetSelector).html(opts);
    });
}

function resetForm() {
    $('#formCekKesehatan')[0].reset();
    $('#ck_id').val('');
    $('#ck_person_id').html('<option value="">-- Pilih --</option>');
}

function setLoading(loading) {
    const $btn = $('#btnSimpanCekKesehatan');
    $btn.find('.indicator-label').toggleClass('d-none', loading);
    $btn.find('.indicator-progress').toggleClass('d-none', !loading);
    $btn.prop('disabled', loading);
}
