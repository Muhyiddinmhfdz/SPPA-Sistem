'use strict';

const RL_URLS = {
    atlet: '/riwayat-latihan/data/atlet',
    pelatih: '/riwayat-latihan/data/pelatih',
    detail: '/riwayat-latihan/detail',
};

function rlParams() {
    return { cabor_id: $('#rlFilterCabor').val(), search_name: $('#rlFilterNama').val() };
}

const rlCols = [
    { data: 'DT_RowIndex', orderable: false, className: 'ps-4' },
    { data: 'name' },
    { data: 'cabor_name' },
    { data: 'jumlah_latihan', className: 'text-center' },
    { data: 'action', orderable: false, className: 'text-center' },
];

let dtRlAtlet, dtRlPelatih, dtDetailLatihan;

$(function () {
    dtRlAtlet = $('#tableRlAtlet').DataTable({
        processing: true, serverSide: true,
        ajax: { url: RL_URLS.atlet, data: (d) => Object.assign(d, rlParams()) },
        columns: rlCols,
        language: { processing: '<span class="spinner-border spinner-border-sm"></span> Memuat...' },
    });

    $('a[href="#rlTabPelatih"]').one('shown.bs.tab', function () {
        dtRlPelatih = $('#tableRlPelatih').DataTable({
            processing: true, serverSide: true,
            ajax: { url: RL_URLS.pelatih, data: (d) => Object.assign(d, rlParams()) },
            columns: rlCols,
            language: { processing: '<span class="spinner-border spinner-border-sm"></span> Memuat...' },
        });
    });

    $('#rlBtnFilter').on('click', () => { dtRlAtlet?.ajax.reload(); dtRlPelatih?.ajax.reload(); });
    $('#rlBtnReset').on('click', () => {
        $('#rlFilterCabor').val(''); $('#rlFilterNama').val('');
        dtRlAtlet?.ajax.reload(); dtRlPelatih?.ajax.reload();
    });
    $('#rlFilterNama').on('keypress', function (e) { if (e.which === 13) $('#rlBtnFilter').trigger('click'); });

    // ── Open Detail Modal ─────────────────────────────────────────────────────
    $(document).on('click', '.btnRiwayatLatihan', function () {
        const personId = $(this).data('id');
        const personType = $(this).data('type');
        const personName = $(this).data('name');

        $('#modalRLTitle').text('Riwayat Latihan – ' + personName);
        $('#modalRLSubtitle').text(personType === 'atlet' ? 'Atlet' : 'Pelatih');

        if (dtDetailLatihan) {
            dtDetailLatihan.destroy();
            $('#tableDetailLatihan').html(`
                <thead>
                    <tr class="fw-bold text-muted bg-light">
                        <th class="ps-4 rounded-start">No</th>
                        <th>Tanggal</th>
                        <th>Kehadiran</th>
                        <th>Durasi</th>
                        <th>Beban</th>
                        <th>Denyut Nadi/RPE</th>
                        <th>Catatan Pelatih</th>
                        <th class="rounded-end">Kesimpulan</th>
                    </tr>
                </thead>
            `);
        }

        dtDetailLatihan = $('#tableDetailLatihan').DataTable({
            processing: true, serverSide: true,
            ajax: {
                url: RL_URLS.detail,
                data: (d) => Object.assign(d, { person_id: personId, person_type: personType }),
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, className: 'ps-4' },
                { data: 'tanggal_fmt' },
                { data: 'kehadiran_badge', orderable: false },
                { data: 'durasi_latihan' },
                { data: 'beban_badge', orderable: false },
                { data: 'denyut_nadi_rpe', defaultContent: '-' },
                { data: 'catatan_short', orderable: false },
                { data: 'kesimpulan_badge', orderable: false },
            ],
            language: { processing: '<span class="spinner-border spinner-border-sm"></span> Memuat...' },
            pageLength: 10,
        });

        $('#modalRiwayatLatihan').modal('show');
    });
});
