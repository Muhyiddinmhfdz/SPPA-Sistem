<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KlasifikasiDisabilitas;

class KlasifikasiDisabilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $klasifikasi = [
            [
                'kode_klasifikasi' => 'T11',
                'nama_klasifikasi' => 'Tuna Netra Total',
                'deskripsi' => 'Atlet memiliki gangguan penglihatan yang sangat rendah atau atlet buta total.'
            ],
            [
                'kode_klasifikasi' => 'T12',
                'nama_klasifikasi' => 'Tuna Netra Parsial',
                'deskripsi' => 'Atlet dapat melihat sebagian. Kemampuan penglihatan atlet ini lebih tinggi dari kelas T11.'
            ],
            [
                'kode_klasifikasi' => 'T13',
                'nama_klasifikasi' => 'Gangguan Penglihatan Ringan',
                'deskripsi' => 'Atlet memiliki kemampuan persepsi yang lebih baik dibandingkan para atlet kelas T12.'
            ],
            [
                'kode_klasifikasi' => 'F20',
                'nama_klasifikasi' => 'Hambatan Kecerdasan (Intelektual)',
                'deskripsi' => 'Atlet di klasifikasi memiliki masalah intelektual yang dapat menghambat dan membatasi kinerja para atlet tersebut saat berolahraga.'
            ],
            [
                'kode_klasifikasi' => 'T35',
                'nama_klasifikasi' => 'Gangguan Kordinasi Berdiri Ringan',
                'deskripsi' => 'Atlet dengan gangguan kordinasi tubuh dan saraf ringan yang masih dapat berlaga dengan posisi berdiri normal namun gerakan tubuh mulai terlihat terganggu.'
            ],
            [
                'kode_klasifikasi' => 'T38',
                'nama_klasifikasi' => 'Gangguan Kordinasi Berdiri',
                'deskripsi' => 'Para atlet mengalami gangguan tubuh sedikit atau minimal. Para atlet juga mengalami gangguan pada saraf.'
            ]
        ];

        foreach ($klasifikasi as $item) {
            KlasifikasiDisabilitas::create($item);
        }
    }
}
