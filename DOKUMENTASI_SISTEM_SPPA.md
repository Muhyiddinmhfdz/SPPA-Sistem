# Dokumentasi Studi Sistem SPPA

Dokumen ini merangkum hasil pemetaan source code pada proyek `SPPA-Sistem` (Laravel), meliputi arsitektur, modul bisnis, skema data inti, serta cara menjalankan sistem secara lokal.

## 1. Gambaran Umum

SPPA adalah aplikasi web manajemen pembinaan prestasi atlet disabilitas dengan cakupan:
- Master data organisasi dan SDM olahraga (cabor, pelatih, medis, atlet, klasifikasi/jenis disabilitas).
- Monitoring kesehatan dan monitoring latihan (input + riwayat).
- Pembinaan prestasi (program latihan berbasis komponen dan skor).
- Pencatatan kompetisi.
- Tes performa fisik (master komponen tes + input hasil tes).
- Dashboard statistik lintas modul.

Secara implementasi, aplikasi berbentuk **monolith Laravel** dengan rendering Blade dan interaksi frontend dominan via jQuery + AJAX + DataTables.

## 2. Stack Teknologi

- Backend: `Laravel 12`, `PHP 8.2`.
- Auth: `Laravel Fortify` (login pakai `username`).
- API token: `Laravel Sanctum`.
- RBAC: `spatie/laravel-permission`.
- Activity logging: `spatie/laravel-activitylog`.
- Table server-side: `yajra/laravel-datatables`.
- Import/export Excel: `maatwebsite/excel`.
- PDF/Barcode support: `mpdf/mpdf`, `milon/barcode`.
- Frontend build: `Vite`, `Tailwind CSS`.
- UI runtime utama: Metronic bundle (`public/assets/...`) + jQuery DataTables + SweetAlert.

## 3. Arsitektur Aplikasi

Pola utama:
- `routes/web.php` mendefinisikan route berbasis prefix modul.
- Controller di `app/Http/Controllers` menangani validasi, query Eloquent, dan JSON response untuk AJAX.
- Model di `app/Models` memetakan relasi domain.
- View Blade di `resources/views/pages/*` sebagai container tabel/modal.
- Logic frontend halaman ada di `public/js/pages/*` (datatable init, filter, submit form, edit/delete modal).

Alur request tipikal:
1. User buka halaman modul Blade.
2. JS halaman memanggil endpoint AJAX.
3. Controller membentuk query + DataTables response / JSON detail.
4. UI render tabel, badge status, dan aksi CRUD.

## 4. Struktur Folder Kunci

- `app/Http/Controllers`: seluruh modul bisnis.
- `app/Models`: entitas domain.
- `database/migrations`: skema tabel.
- `database/seeders`: data awal + data dummy.
- `resources/views/layouts/main_layout.blade.php`: layout utama sidebar/header.
- `resources/views/pages/*`: halaman per modul.
- `public/js/pages/*`: script per halaman.
- `routes/web.php`: route web utama.

## 5. Modul Fungsional (Aktif)

## 5.1 Dashboard
- Route: `/dashboard`, `/dashboard/medal-details/{caborId}`.
- Menampilkan statistik cabor, atlet, pelatih, medis, klasifikasi, jenis latihan, medal, aktivitas monitoring, statistik pembinaan, statistik tes performa.

## 5.2 Master Data (`/master/*`)
- User dan Role.
- Cabor.
- Pelatih.
- Medis.
- Atlet.
- Klasifikasi disabilitas.
- Jenis disabilitas.
- Jenis latihan + komponen + skor (`training-type`).
- Parameter/jenis tes fisik + item + skor (`jenis-tes`).

## 5.3 Monitoring Kesehatan
- `cek-kesehatan`: input cek kesehatan untuk `atlet`/`pelatih`.
- `riwayat-kesehatan`: rekap riwayat per orang.

## 5.4 Monitoring Latihan
- `monitoring-latihan`: input monitoring harian/sesi.
- `riwayat-latihan`: riwayat monitoring per orang.

## 5.5 Pembinaan Prestasi
- `pembinaan-prestasi`: input program berdasarkan atlet.
- Nilai komponen latihan dihitung ke skor berdasarkan tabel `training_type_component_scores`.

## 5.6 Kompetisi
- `kompetisi`: pencatatan event, tingkatan, hasil peringkat, hasil medali.

## 5.7 Tes Performa
- `master/jenis-tes`: master kategori tes fisik, item tes, dan rentang skornya.
- `tes-performa`: input sesi tes atlet dan hasil per item tes, termasuk auto-resolve skor dari rentang nilai.

## 6. Auth, Role, dan Keamanan Dasar

- Login menggunakan `username` (`config/fortify.php`).
- Fitur Fortify aktif: registration, reset password, update profile, update password, 2FA.
- Role seeder default: `Super Admin`, `Admin`, `Pelatih`, `Medis`, `Atlet`.
- Guard utama: `web`.
- Hampir seluruh modul dilindungi middleware `auth`.

## 7. Skema Data Inti

Entitas utama:
- `users` (akun + role).
- `cabors`.
- `coaches`, `medis`, `atlets` (profil SDM/atlet, terhubung ke user).
- `klasifikasi_disabilitas`, `jenis_disabilitas`.
- `cek_kesehatan`.
- `monitoring_latihan`.
- `training_types`, `training_type_components`, `training_type_component_scores`.
- `pembinaan_prestasis`, `pembinaan_prestasi_details`.
- `kompetisis`.
- `physical_test_categories`, `physical_test_items`, `physical_test_item_scores`.
- `performance_tests`, `performance_test_results`.

Relasi penting:
- `Cabor` punya banyak `Atlet`, `Coach`, `TrainingType`, `Kompetisi`, `PerformanceTest`.
- `Atlet` belongsTo `User`, `Cabor`, `KlasifikasiDisabilitas`, `JenisDisabilitas`.
- `PembinaanPrestasi` belongsTo `Atlet`, punya banyak `PembinaanPrestasiDetail`.
- `PerformanceTest` belongsTo `Atlet`/`Cabor`, punya banyak `PerformanceTestResult`.

## 8. Setup Lokal (Ringkas)

Prasyarat:
- PHP 8.2+, Composer, Node.js + npm, database (umumnya MySQL di lingkungan ini).

Langkah:
1. `composer install`
2. `cp .env.example .env`
3. Atur konfigurasi DB di `.env`
4. `php artisan key:generate`
5. `php artisan migrate`
6. `php artisan db:seed`
7. `npm install`
8. `npm run dev` (atau `npm run build`)
9. Jalankan server: `php artisan serve`

Shortcut script yang tersedia:
- `composer run setup`
- `composer run dev`
- `composer test`

## 9. Seeder dan Akun Awal

`DatabaseSeeder` memanggil seeder utama domain (role, user, cabor, coach, atlet, monitoring, pembinaan, tes performa, dst).

Akun awal dari `UserSeeder`:
- `superadmin` / `123123123`
- `admin` / `123123123`

## 10. Temuan Teknis Saat Studi

- `README.md` masih template default Laravel, belum berisi dokumentasi proyek.
- Test otomatis masih minimal (hanya `ExampleTest`).
- Terdapat dua pendekatan manajemen user/role:
  - Controller aktif di root namespace (`App\Http\Controllers\UserController`, `RoleController`) dipakai route sekarang.
  - Versi service-oriented di `App\Http\Controllers\Master\*` + `app/Services/*` ada, tapi tidak dipakai route aktif.
- Penyimpanan file belum konsisten:
  - Sebagian pakai disk `storage/public`.
  - Sebagian langsung ke `public/uploads/*`.
- Di view coach ada referensi route import/template (`master.coach.import`, `master.coach.export-template`), tetapi route ini belum terlihat pada `routes/web.php` aktif saat studi.

## 11. Ringkasan

SPPA saat ini sudah mencakup siklus utama pembinaan atlet dari master data, monitoring, evaluasi, hingga kompetisi dengan pola AJAX DataTables yang konsisten. Fondasi domain dan relasi data sudah kuat, namun dokumentasi resmi, automated test, dan konsistensi implementasi (routing/arsitektur/file storage) masih perlu dirapikan agar maintenance jangka panjang lebih aman.

