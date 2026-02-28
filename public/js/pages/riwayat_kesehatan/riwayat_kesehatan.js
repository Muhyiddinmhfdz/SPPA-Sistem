'use strict';

const RW_URLS = {
    atlet: '/riwayat-kesehatan/data/atlet',
    pelatih: '/riwayat-kesehatan/data/pelatih',
    detail: '/riwayat-kesehatan/detail',
};

function rwParams() {
    return {
        cabor_id: $('#rwFilterCabor').val(),
        search_name: $('#rwFilterNama').val(),
    };
}

const dtColsMain = [
    { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'ps-4' },
    { data: 'name' },
    { data: 'cabor_name' },
    { data: 'jumlah_pemeriksaan', className: 'text-center' },
    { data: 'action', orderable: false, className: 'text-center' },
];

let dtRwAtlet, dtRwPelatih, dtDetail;

// ── Init Atlet DataTable ──────────────────────────────────────────────────────
function initRwAtlet() {
    dtRwAtlet = $('#tableRwAtlet').DataTable({
        processing: true, serverSide: true,
        ajax: { url: RW_URLS.atlet, data: (d) => Object.assign(d, rwParams()) },
        columns: dtColsMain,
        language: { processing: '<span class="spinner-border spinner-border-sm"></span> Memuat...' },
        responsive: true,
    });
}

// ── Init Pelatih DataTable (lazy) ─────────────────────────────────────────────
function initRwPelatih() {
    dtRwPelatih = $('#tableRwPelatih').DataTable({
        processing: true, serverSide: true,
        ajax: { url: RW_URLS.pelatih, data: (d) => Object.assign(d, rwParams()) },
        columns: dtColsMain,
        language: { processing: '<span class="spinner-border spinner-border-sm"></span> Memuat...' },
        responsive: true,
    });
}

$(function () {
    initRwAtlet();

    $('a[href="#rwTabPelatih"]').one('shown.bs.tab', function () {
        initRwPelatih();
    });

    // ── Filter ────────────────────────────────────────────────────────────────
    $('#rwBtnFilter').on('click', function () {
        dtRwAtlet?.ajax.reload();
        dtRwPelatih?.ajax.reload();
    });

    $('#rwBtnReset').on('click', function () {
        $('#rwFilterCabor').val('');
        $('#rwFilterNama').val('');
        dtRwAtlet?.ajax.reload();
        dtRwPelatih?.ajax.reload();
    });

    $('#rwFilterNama').on('keypress', function (e) {
        if (e.which === 13) $('#rwBtnFilter').trigger('click');
    });

    // ── Open Detail Modal ─────────────────────────────────────────────────────
    $(document).on('click', '.btnRiwayat', function () {
        const personId = $(this).data('id');
        const personType = $(this).data('type');
        const personName = $(this).data('name');
        const typeLabel = personType === 'atlet' ? 'Atlet' : 'Pelatih';

        $('#modalRiwayatTitle').text('Riwayat Pemeriksaan – ' + personName);
        $('#modalRiwayatSubtitle').text(typeLabel);

        // Destroy & rebuild detail DataTable
        if (dtDetail) {
            dtDetail.destroy();
            $('#tableDetailRiwayat').empty();
            // Rebuild thead
            $('#tableDetailRiwayat').html(`
                <thead>
                    <tr class="fw-bold text-muted bg-light">
                        <th class="ps-4 rounded-start">No</th>
                        <th>Tanggal</th>
                        <th>Kondisi Harian</th>
                        <th>Tingkat Cedera</th>
                        <th>Riwayat Tindakan Medis</th>
                        <th class="rounded-end">Kesimpulan</th>
                    </tr>
                </thead>
            `);
        }

        dtDetail = $('#tableDetailRiwayat').DataTable({
            processing: true, serverSide: true,
            ajax: {
                url: RW_URLS.detail,
                data: (d) => Object.assign(d, { person_id: personId, person_type: personType }),
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, className: 'ps-4' },
                { data: 'tanggal_fmt' },
                { data: 'kondisi_badge', orderable: false },
                { data: 'cedera_badge', orderable: false },
                { data: 'riwayat_medis_short', orderable: false },
                { data: 'kesimpulan_badge', orderable: false },
            ],
            language: { processing: '<span class="spinner-border spinner-border-sm"></span> Memuat...' },
            order: [[1, 'desc']],
            pageLength: 10,
        });

        $('#modalRiwayat').modal('show');
    });
});
