'use strict';

const ML_URLS = {
    atlet: '/monitoring-latihan/datatable/atlet',
    pelatih: '/monitoring-latihan/datatable/pelatih',
    persons: '/monitoring-latihan/persons',
    store: '/monitoring-latihan',
    update: (id) => `/monitoring-latihan/${id}`,
    edit: (id) => `/monitoring-latihan/${id}/edit`,
    destroy: (id) => `/monitoring-latihan/${id}`,
};

const mlCols = [
    { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'ps-4' },
    { data: 'nama' },
    { data: 'cabor_name' },
    { data: 'tanggal_fmt' },
    { data: 'durasi_latihan' },
    { data: 'kehadiran_badge', orderable: false },
    { data: 'beban_badge', orderable: false },
    { data: 'kesimpulan_badge', orderable: false },
    { data: 'action', orderable: false, className: 'text-center' },
];

function mlParams(extra = {}) {
    return { cabor_id: $('#mlFilterCabor').val(), person_id: $('#mlFilterPerson').val(), ...extra };
}

let dtMonLat, dtMonLatPelatih;

$(function () {
    // ── Init Atlet DataTable ──────────────────────────────────────────────────
    dtMonLat = $('#tableMonLat').DataTable({
        processing: true, serverSide: true,
        ajax: { url: ML_URLS.atlet, data: (d) => Object.assign(d, mlParams({ type: 'atlet' })) },
        columns: mlCols,
        language: { processing: '<span class="spinner-border spinner-border-sm"></span> Memuat...' },
        responsive: true,
    });

    // ── Pelatih lazy init ─────────────────────────────────────────────────────
    $('a[href="#mlTabPelatih"]').one('shown.bs.tab', function () {
        dtMonLatPelatih = $('#tableMonLatPelatih').DataTable({
            processing: true, serverSide: true,
            ajax: { url: ML_URLS.pelatih, data: (d) => Object.assign(d, mlParams({ type: 'pelatih' })) },
            columns: mlCols,
            language: { processing: '<span class="spinner-border spinner-border-sm"></span> Memuat...' },
            responsive: true,
        });
    });

    // ── Filter ────────────────────────────────────────────────────────────────
    $('#mlFilterCabor').on('change', function () {
        const activeType = $('a[data-bs-toggle="tab"].active').attr('href') === '#mlTabAtlet' ? 'atlet' : 'pelatih';
        loadMLPersons(activeType, $(this).val(), '#mlFilterPerson');
    });

    $('#mlBtnFilter').on('click', () => { dtMonLat?.ajax.reload(); dtMonLatPelatih?.ajax.reload(); });
    $('#mlBtnReset').on('click', () => {
        $('#mlFilterCabor').val('');
        $('#mlFilterPerson').html('<option value="">-- Semua --</option>');
        dtMonLat?.ajax.reload();
        dtMonLatPelatih?.ajax.reload();
    });

    // ── Open modal: Add ───────────────────────────────────────────────────────
    $('#btnTambahMonLat').on('click', function () {
        mlResetForm();
        $('#modalMonLatTitle').text('Input Monitoring Latihan');
        const activeType = $('a[data-bs-toggle="tab"].active').attr('href') === '#mlTabAtlet' ? 'atlet' : 'pelatih';
        $('#ml_person_type').val(activeType).trigger('change');
        $('#modalMonLat').modal('show');
    });

    // ── Person type/cabor change → reload person dropdown ────────────────────
    $('#ml_person_type, #ml_cabor_id').on('change', function () {
        loadMLPersons($('#ml_person_type').val(), $('#ml_cabor_id').val(), '#ml_person_id');
    });

    // ── Save ─────────────────────────────────────────────────────────────────
    $('#btnSimpanMonLat').on('click', function () {
        const id = $('#ml_id').val();
        setMLLoading(true);
        $.ajax({
            url: id ? ML_URLS.update(id) : ML_URLS.store,
            method: 'POST',
            data: $('#formMonLat').serialize() + (id ? '&_method=PUT' : ''),
            success(res) {
                setMLLoading(false);
                $('#modalMonLat').modal('hide');
                Swal.fire({ icon: 'success', title: 'Berhasil', text: res.success, timer: 2000, showConfirmButton: false });
                dtMonLat?.ajax.reload(null, false);
                dtMonLatPelatih?.ajax.reload(null, false);
            },
            error(xhr) {
                setMLLoading(false);
                Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.error || 'Terjadi kesalahan.' });
            }
        });
    });

    // ── Edit ─────────────────────────────────────────────────────────────────
    $(document).on('click', '.editMonLat', function () {
        const id = $(this).data('id');
        $.get(ML_URLS.edit(id), function (data) {
            mlResetForm();
            $('#modalMonLatTitle').text('Edit Monitoring Latihan');
            $('#ml_id').val(data.id);
            $('#ml_person_type').val(data.person_type);
            $('#ml_cabor_id').val(data.cabor_id);
            loadMLPersons(data.person_type, data.cabor_id, '#ml_person_id', data.person_id);
            if (typeof mlDatePicker !== 'undefined') {
                mlDatePicker.setDate(data.tanggal?.split('T')[0] || data.tanggal, true, 'Y-m-d');
            }
            $('#ml_kehadiran').val(data.kehadiran);
            $('#ml_durasi').val(data.durasi_latihan);
            $(`input[name="beban_latihan"][value="${data.beban_latihan}"]`).prop('checked', true);
            $('#ml_denyut').val(data.denyut_nadi_rpe);
            $('#ml_catatan').val(data.catatan_pelatih);
            $(`input[name="kesimpulan"][value="${data.kesimpulan}"]`).prop('checked', true);
            $('#modalMonLat').modal('show');
        });
    });

    // ── Delete ────────────────────────────────────────────────────────────────
    $(document).on('click', '.deleteMonLat', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Hapus data ini?', text: 'Data monitoring latihan akan dihapus.',
            icon: 'warning', showCancelButton: true,
            confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal', confirmButtonColor: '#d33',
        }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    url: ML_URLS.destroy(id), method: 'DELETE',
                    data: { _token: $('meta[name="csrf-token"]').attr('content') },
                    success(res) {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: res.success, timer: 1800, showConfirmButton: false });
                        dtMonLat?.ajax.reload(null, false);
                        dtMonLatPelatih?.ajax.reload(null, false);
                    },
                    error() { Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan.' }); }
                });
            }
        });
    });
});

function loadMLPersons(type, caborId, target, selectedId = null) {
    $.get(ML_URLS.persons, { type, cabor_id: caborId }, function (persons) {
        let opts = '<option value="">-- Pilih --</option>';
        persons.forEach(p => { opts += `<option value="${p.id}" ${selectedId == p.id ? 'selected' : ''}>${p.name}</option>`; });
        $(target).html(opts);
    });
}

function mlResetForm() {
    $('#formMonLat')[0].reset();
    $('#ml_id').val('');
    $('#ml_person_id').html('<option value="">-- Pilih --</option>');
}

function setMLLoading(loading) {
    const $b = $('#btnSimpanMonLat');
    $b.find('.indicator-label').toggleClass('d-none', loading);
    $b.find('.indicator-progress').toggleClass('d-none', !loading);
    $b.prop('disabled', loading);
}
