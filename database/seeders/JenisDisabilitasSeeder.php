<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JenisDisabilitas;
use App\Models\KlasifikasiDisabilitas;

class JenisDisabilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisList = [
            // Tuna Netra
            ['klasifikasi_kode' => 'T11', 'nama_jenis' => 'Tuna Netra Total (Buta Seluruhnya)', 'deskripsi' => 'Tidak mampu melihat sama sekali dalam kondisi apapun.'],
            ['klasifikasi_kode' => 'T12', 'nama_jenis' => 'Tuna Netra Parsial (Low Vision)', 'deskripsi' => 'Masih memiliki sisa penglihatan namun sangat terbatas.'],
            ['klasifikasi_kode' => 'T13', 'nama_jenis' => 'Buta Warna Total', 'deskripsi' => 'Tidak mampu membedakan warna sama sekali.'],
            // Tuna Rungu
            ['klasifikasi_kode' => 'T51', 'nama_jenis' => 'Tuli Total', 'deskripsi' => 'Kehilangan pendengaran secara penuh.'],
            ['klasifikasi_kode' => 'T51', 'nama_jenis' => 'Kurang Mendengar (Hard of Hearing)', 'deskripsi' => 'Mengalami penurunan pendengaran namun tidak total.'],
            // Tuna Daksa
            ['klasifikasi_kode' => 'T42', 'nama_jenis' => 'Amputasi Anggota Gerak Atas', 'deskripsi' => 'Kehilangan satu atau kedua lengan/tangan.'],
            ['klasifikasi_kode' => 'T44', 'nama_jenis' => 'Amputasi Anggota Gerak Bawah', 'deskripsi' => 'Kehilangan satu atau kedua kaki.'],
            ['klasifikasi_kode' => 'T54', 'nama_jenis' => 'Cerebral Palsy', 'deskripsi' => 'Gangguan motorik akibat kondisi otak.'],
            // Intelektual
            ['klasifikasi_kode' => 'II10', 'nama_jenis' => 'Down Syndrome', 'deskripsi' => 'Hambatan kecerdasan dengan kondisi genetik trisomi 21.'],
            ['klasifikasi_kode' => 'II10', 'nama_jenis' => 'Hambatan Intelektual Ringan (IQ 50-75)', 'deskripsi' => 'Skor IQ antara 50 hingga 75.'],
        ];

        foreach ($jenisList as $item) {
            $klasifikasi = KlasifikasiDisabilitas::where('kode_klasifikasi', $item['klasifikasi_kode'])->first();
            if ($klasifikasi) {
                JenisDisabilitas::create([
                    'klasifikasi_disabilitas_id' => $klasifikasi->id,
                    'nama_jenis' => $item['nama_jenis'],
                    'deskripsi' => $item['deskripsi'],
                ]);
            }
        }
    }
}
