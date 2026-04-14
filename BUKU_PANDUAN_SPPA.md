# 📕 Buku Panduan Penggunaan Fitur Sistem SPPA
### **Panduan Operasional Lengkap - Klik-demi-Klik**

Sistem Pembinaan Prestasi Atlet (SPPA) adalah platform terpadu untuk manajemen data, monitoring, dan evaluasi prestasi atlet. Panduan ini fokus pada cara penggunaan fitur-fitur sistem secara detail.

---

## 📋 Daftar Isi
1. [Bab 1: Pendahuluan & Konsep Dasar](#bab-1)
2. [Bab 2: Akses & Pengaturan Akun](#bab-2)
3. [Bab 3: Eksplorasi Dashboard](#bab-3)
4. [Bab 4: Manajemen Pengguna & Keamanan (Khusus Admin)](#bab-4)
5. [Bab 5: Master Data Dasar (Referensial)](#bab-5)
6. [Bab 6: Manajemen SDM Terpadu (Pelatih, Medis, Atlet)](#bab-6)
7. [Bab 7: Operasional Monitoring (Kesehatan & Latihan)](#bab-7)
8. [Bab 8: Program Pembinaan & Evaluasi](#bab-8)
9. [Bab 9: Modul Tes Performa (Fisik)](#bab-9)
10. [Bab 10: Modul Kompetisi & Prestasi](#bab-10)
11. [Bab 11: Laporan & Ekspor Data](#bab-11)
12. [Bab 12: Tips Navigasi & Troubleshooting](#bab-12)

---

<a name="bab-1"></a>
## 1. Pendahuluan & Konsep Dasar
Sistem SPPA bekerja dengan alur data yang saling berkaitan:
- **Master Data**: Pondasi awal (Cabor, Klasifikasi, Jenis Tes).
- **SDM**: Database personil yang terhubung ke Master Data.
- **Monitoring**: Pencatatan harian kondisi SDM.
- **Evaluasi**: Hasil akhir dan pengukuran prestasi.

> [!IMPORTANT]
> Pastikan Data Master sudah terisi lengkap sebelum memasukkan data personil untuk menghindari kesalahan relasi data.

### 1.1 Teknologi & Arsitektur Sistem
- **Backend**: Laravel 12.0 dengan PHP 8.2+
- **Database**: MySQL 5.7+ dengan relasi kompleks
- **Frontend**: Blade Templates + jQuery + DataTables + Tailwind CSS
- **Authentication**: Laravel Fortify (login berbasis username)
- **Authorization**: RBAC dengan Spatie Laravel Permission
- **UI Framework**: Metronic Bundle dengan Bootstrap 5

### 1.2 Struktur Database Utama
```
users (Akun Login) ──→ atlets/coaches/medis (Personil)
                      ↓
                    cabors (Cabang Olahraga)
                      ↓
            ├─→ training_types (Jenis Latihan)
            ├─→ kompetisis (Kompetisi)
            ├─→ performance_tests (Tes Performa)
            ├─→ cek_kesehatan (Monitoring Kesehatan)
            └─→ monitoring_latihan (Monitoring Latihan)
```

---

<a name="bab-2"></a>
## 2. Akses & Pengaturan Akun
### 2.1 Proses Login Utama
1. Masukkan alamat URL sistem pada browser.
2. Ketik **Username** pada kolom isian pertama.
3. Ketik **Password** pada kolom isian kedua.
4. Klik tombol **Login** (berwarna biru).

### 2.2 Keluar dari Sistem (Logout)
1. Klik **Nama/Foto Profil** di pojok kanan atas.
2. Klik opsi **Keluar** (ikon pintu keluar).

### 2.3 Reset Password
1. Di halaman login, klik **Lupa Password?**.
2. Masukkan alamat email yang terdaftar.
3. Sistem akan mengirim link reset via email.
4. Ikuti instruksi di email untuk membuat password baru.

### 2.4 Pengaturan Profil
1. Klik nama profil → **Pengaturan Profil**.
2. Update informasi pribadi, foto profil, dan password.
3. Klik **Simpan Perubahan**.

---

<a name="bab-3"></a>
## 3. Eksplorasi Dashboard
Dashboard memberikan ringkasan statistik organisasi secara *real-time*.

### 3.1 Widget Utama
- **Jumlah Cabor**: Total cabang olahraga aktif.
- **Jumlah Atlet**: Total atlet terdaftar.
- **Jumlah Pelatih**: Total pelatih aktif.
- **Jumlah Medis**: Total tenaga medis.
- *Klik pada widget untuk menuju halaman detail terkait.*

### 3.2 Filter Berdasarkan Cabor
- Gunakan dropdown di bagian atas dashboard.
- Pilih cabang olahraga tertentu untuk menyaring statistik.

### 3.3 Grafik & Tren Performa
- **Tren Nilai Rata-rata Atlet**: Menampilkan perkembangan performa.
- **Statistik Medali**: Breakdown perolehan medali.
- **Statistik Tes Performa**: Tren hasil tes fisik.

### 3.4 Log Aktivitas
- Menampilkan aktivitas terbaru pengguna sistem.
- Klik **Lihat Detail** untuk informasi lengkap.

---

<a name="bab-4"></a>
## 4. Manajemen Pengguna & Keamanan (Khusus Admin)

### 4.1 Manajemen Role (Izin Akses)

#### 4.1.1 Membuat Role Baru
1. **Akses Menu**: Klik menu **Sistem & Pengguna** → **Data Role**
2. **Tambah Role**: Klik tombol biru **+ Tambah Role** di pojok kanan atas
3. **Form Input**:
   - **Nama Role**: Masukkan nama role (contoh: "Pengurus Cabor", "Koordinator Medis")
   - **Deskripsi**: Opsional, jelaskan fungsi role
4. **Konfigurasi Permissions**:
   - **Master Data**: Centang untuk akses Data Cabor, Klasifikasi, dll
   - **Manajemen SDM**: Atlet, Pelatih, Medis management
   - **Monitoring**: Kesehatan, Latihan, Riwayat
   - **Program**: Pembinaan, Tes Performa, Kompetisi
   - **Sistem**: User management, Role management
5. **Simpan**: Klik **Simpan Data** (hijau)

#### 4.1.2 Mengedit Role
1. Pada tabel Data Role, klik ikon **Edit** (pensil) di kolom Aksi
2. Ubah nama role atau permission sesuai kebutuhan
3. Klik **Update Data**

#### 4.1.3 Menghapus Role
1. Klik ikon **Hapus** (sampah) di kolom Aksi
2. Konfirmasi penghapusan pada popup SweetAlert
3. **Catatan**: Role yang sedang digunakan user tidak bisa dihapus

#### 4.1.4 Permissions Detail
- **view_***: Melihat data saja
- **create_***: Membuat data baru
- **edit_***: Mengedit data existing
- **delete_***: Menghapus data
- **manage_***: Full access (view + create + edit + delete)

### 4.2 Manajemen User (Akun Login)

#### 4.2.1 Membuat User Baru
1. **Akses**: Menu **Sistem & Pengguna** → **Data User**
2. **Tambah User**: Klik **+ Tambah User**
3. **Form Input**:
   - **Nama Lengkap**: Nama lengkap user
   - **Username**: Username untuk login (unik)
   - **Email**: Alamat email (opsional)
   - **Password**: Password minimal 8 karakter
   - **Konfirmasi Password**: Ulangi password
   - **Role**: Pilih role dari dropdown
   - **Status**: Aktif/Tidak Aktif
4. **Simpan**: Klik **Simpan Data**

#### 4.2.2 Mengedit User
1. Klik ikon **Edit** pada tabel
2. Ubah data sesuai kebutuhan
3. **Password**: Kosongkan jika tidak ingin mengubah
4. Klik **Update Data**

#### 4.2.3 Reset Password User
1. Edit user yang ingin direset password
2. Masukkan password baru di field password
3. Klik **Update Data**
4. **Atau**: User bisa reset sendiri via "Lupa Password"

#### 4.2.4 Menonaktifkan User
1. Edit user
2. Ubah **Status** menjadi "Tidak Aktif"
3. User tidak bisa login setelah dinonaktifkan

#### 4.2.5 Filter & Pencarian User
- **Cari**: Gunakan kotak pencarian untuk nama/username
- **Filter Role**: Dropdown filter berdasarkan role
- **Status**: Filter aktif/tidak aktif

### 4.3 Konfigurasi Keamanan

#### 4.3.1 Two-Factor Authentication (2FA)
1. Login sebagai user
2. Klik nama profil → **Pengaturan Profil**
3. Scroll ke bagian **Two-Factor Authentication**
4. Klik **Enable** untuk mengaktifkan
5. Scan QR code dengan aplikasi authenticator
6. Masukkan kode verifikasi
7. **Aktif**: Setelah ini, login memerlukan kode 2FA

#### 4.3.2 Session Management
- **Auto Logout**: Sistem logout otomatis setelah 120 menit tidak aktif
- **Concurrent Sessions**: Maksimal 1 session per user
- **Session Database**: Semua session disimpan di database untuk tracking

#### 4.3.3 Password Policy
- Minimal 8 karakter
- Kombinasi huruf besar, kecil, angka, simbol
- Tidak boleh sama dengan username
- History password (tidak boleh sama dengan 3 password sebelumnya)

---

<a name="bab-5"></a>
## 5. Master Data Dasar (Referensial)

### 5.1 Data Cabor (Cabang Olahraga)

#### 5.1.1 Membuat Cabor Baru
1. **Akses**: Menu **Master Data** → **Data Cabor**
2. **Tambah**: Klik **+ Tambah Cabor**
3. **Tab Informasi Umum**:
   - **Nama Cabor**: Nama cabang olahraga (Football, Basketball, dll)
   - **Deskripsi**: Penjelasan singkat tentang cabor
4. **Tab Struktur Organisasi**:
   - **Ketua**: Nama ketua cabor
   - **Sekretaris**: Nama sekretaris
   - **Bendahara**: Nama bendahara
5. **Tab Kontak & Administrasi**:
   - **Alamat Sekretariat**: Alamat lengkap
   - **Telepon**: Nomor telepon
   - **Email**: Alamat email resmi
   - **NPWP**: Nomor pokok wajib pajak (opsional)
6. **Tab Surat Keputusan (SK)**:
   - **Tanggal Mulai SK**: Tanggal SK diterbitkan
   - **Tanggal Berakhir SK**: Tanggal berakhir masa jabatan
   - **Upload File SK**: PDF file SK (max 5MB)
7. **Simpan**: Klik **Simpan Data**

#### 5.1.2 Upload & Manajemen Dokumen SK
- **Format**: Hanya PDF
- **Ukuran**: Maksimal 5MB
- **Lokasi**: `storage/app/cabors/`
- **Download**: Klik nama file untuk download
- **Replace**: Upload ulang untuk mengganti file

#### 5.1.3 Edit & Update Cabor
1. Klik ikon **Edit** pada tabel
2. Ubah data sesuai kebutuhan
3. Upload SK baru jika perlu
4. Klik **Update Data**

#### 5.1.4 Aktivasi/Nonaktifkan Cabor
- **Status**: Toggle aktif/tidak aktif
- **Imbas**: Cabor tidak aktif tidak muncul di dropdown atlet

#### 5.1.5 Export Data Cabor
1. Klik tombol **Export** (hijau) di atas tabel
2. Pilih format: Excel atau PDF
3. File terdownload otomatis

### 5.2 Klasifikasi & Jenis Disabilitas

#### 5.2.1 Klasifikasi Disabilitas
1. **Akses**: Menu **Master Data** → **Klasifikasi Disabilitas**
2. **Tambah**: Klik **+ Tambah Klasifikasi**
3. **Form**:
   - **Kode**: Kode klasifikasi (T11, F20, S12, dll)
   - **Nama**: Nama lengkap klasifikasi
   - **Deskripsi**: Penjelasan detail
4. **Simpan**: Klik **Simpan Data**

**Kode Klasifikasi Standar:**
- **T**: Tunagrahita (Intellectual Disability)
- **F**: Tunarungu (Deaf)
- **S**: Tunadaksa (Amputee)
- **B**: Tunanetra (Blind)
- **C**: Cerebral Palsy

#### 5.2.2 Jenis Disabilitas
1. **Akses**: Menu **Master Data** → **Jenis Disabilitas**
2. **Tambah**: Klik **+ Tambah Jenis**
3. **Form**:
   - **Nama**: Nama jenis disabilitas (Tunagrahita, Tunarungu, dll)
   - **Deskripsi**: Penjelasan detail
4. **Simpan**: Klik **Simpan Data**

#### 5.2.3 Relasi dengan Atlet
- **Wajib**: Setiap atlet HARUS memiliki 1 klasifikasi + 1 jenis disabilitas
- **Auto-fill**: Saat pilih atlet, klasifikasi otomatis terpilih
- **Filter**: Digunakan untuk filter tes performa dan kompetisi
- **Validasi**: Tidak bisa simpan atlet tanpa klasifikasi/jenis disabilitas

### 5.3 Jenis Latihan & Komponen

#### 5.3.1 Membuat Jenis Latihan
1. **Akses**: Menu **Master Data** → **Jenis Latihan**
2. **Tambah**: Klik **+ Tambah Jenis Latihan**
3. **Form**:
   - **Nama**: Nama jenis latihan (Latihan Dasar, Latihan Khusus)
   - **Cabor**: Pilih cabor terkait
   - **Deskripsi**: Penjelasan program latihan
   - **Status**: Aktif/Tidak Aktif
4. **Simpan**: Klik **Simpan Data**

#### 5.3.2 Menambah Komponen Latihan
1. Pada tabel jenis latihan, klik ikon **Komponen** (gear)
2. Klik **+ Tambah Komponen**
3. **Form Komponen**:
   - **Nama Komponen**: Daya Tahan, Kelincahan, Kekuatan, dll
   - **Deskripsi**: Penjelasan komponen
4. **Konfigurasi Skor**:
   - Klik **+ Tambah Rentang Skor**
   - **Min Value**: Nilai minimum (contoh: 0)
   - **Max Value**: Nilai maksimum (contoh: 30)
   - **Skor**: Nilai skor (contoh: 1)
   - **Deskripsi**: Penjelasan skor
5. **Simpan**: Klik **Simpan Komponen**

#### 5.3.3 Contoh Konfigurasi Skor
```
Komponen: "Daya Tahan" (Push-up Test)
├── 0-10 kali = Skor 1 (Kurang)
├── 11-20 kali = Skor 2 (Cukup)
├── 21-30 kali = Skor 3 (Baik)
└── 31+ kali = Skor 4 (Sangat Baik)
```

#### 5.3.4 Edit & Hapus Komponen
- **Edit**: Klik ikon edit pada komponen
- **Hapus**: Klik ikon hapus (dengan konfirmasi)
- **Imbas**: Menghapus komponen mempengaruhi data pembinaan existing

### 5.4 Jenis Tes Fisik

#### 5.4.1 Membuat Kategori Tes
1. **Akses**: Menu **Master Data** → **Jenis Tes**
2. **Tambah Kategori**: Klik **+ Tambah Kategori**
3. **Form**:
   - **Nama Kategori**: Tes Kekuatan, Tes Daya Tahan, dll
   - **Deskripsi**: Penjelasan kategori
   - **Status**: Aktif/Tidak Aktif

#### 5.4.2 Menambah Item Tes
1. Klik ikon **Item Tes** pada kategori
2. Klik **+ Tambah Item Tes**
3. **Form Item**:
   - **Nama Item**: Bench Press, Squat Jump, dll
   - **Unit**: kg, cm, detik, kali
   - **Deskripsi**: Instruksi tes
4. **Konfigurasi Skor**:
   - **Min Value**: Nilai minimum
   - **Max Value**: Nilai maksimum
   - **Skor**: Nilai skor otomatis
   - **Deskripsi**: Kategori performa

#### 5.4.3 Auto-Scoring System
- Sistem otomatis menghitung skor berdasarkan rentang nilai
- Contoh: Input 45kg → otomatis skor 2 (range 41-50kg)
- Digunakan pada modul Tes Performa

---

<a name="bab-6"></a>
## 6. Manajemen SDM Terpadu (Pelatih, Medis, Atlet)

### 6.1 Alur Pengisian Form Manual

#### 6.1.1 Membuat Data Atlet Baru
1. **Akses**: Menu **SDM** → **Data Atlet**
2. **Tambah**: Klik **+ Tambah Atlet**
3. **Tab Akun & Pribadi**:
   - **Username**: Kosongkan untuk auto-generate dari NIK
   - **Password**: Auto-generate (bisa diubah nanti)
   - **NIK**: 16 digit nomor KTP (unik)
   - **Nama Lengkap**: Nama lengkap atlet
   - **Tempat Lahir**: Kota kelahiran
   - **Tanggal Lahir**: Tanggal lahir
   - **Jenis Kelamin**: Laki-laki/Perempuan
   - **Alamat**: Alamat lengkap
   - **Telepon**: Nomor HP aktif
   - **Email**: Email aktif (opsional)
4. **Tab Data Atlet**:
   - **Cabor**: Pilih cabang olahraga
   - **Klasifikasi Disabilitas**: Pilih klasifikasi (T11, F20, dll)
   - **Jenis Disabilitas**: Pilih jenis (Tunagrahita, dll)
   - **Tinggi Badan**: Dalam cm
   - **Berat Badan**: Dalam kg
   - **Golongan Darah**: A/B/AB/O
   - **Riwayat Prestasi**: Prestasi sebelumnya
5. **Tab Upload Dokumen**:
   - **Foto Profil**: JPG/PNG max 2MB
   - **KTP**: PDF/JPG/PNG max 5MB
   - **Sertifikat**: PDF max 5MB (opsional)
6. **Simpan**: Klik **Simpan Data**

#### 6.1.2 Membuat Data Pelatih
1. **Akses**: Menu **SDM** → **Data Pelatih**
2. **Tambah**: Klik **+ Tambah Pelatih**
3. **Tab Akun & Pribadi**: Sama dengan atlet
4. **Tab Data Pelatih**:
   - **Cabor**: Pilih cabor yang dilatih
   - **Spesialisasi**: Bidang keahlian (Teknik, Fisik, dll)
   - **Pengalaman**: Tahun pengalaman
   - **Sertifikasi**: Sertifikat kepelatihan
5. **Tab Upload Dokumen**: Foto, KTP, Sertifikat
6. **Simpan**: Klik **Simpan Data**

#### 6.1.3 Membuat Data Medis
1. **Akses**: Menu **SDM** → **Data Medis**
2. **Tambah**: Klik **+ Tambah Medis**
3. **Tab Akun & Pribadi**: Sama dengan atlet
4. **Tab Data Medis**:
   - **Spesialisasi**: Dokter Umum, Fisioterapis, dll
   - **STR**: Nomor Surat Tanda Registrasi
   - **SIP**: Nomor Surat Izin Praktik
   - **Pengalaman**: Tahun pengalaman
5. **Tab Upload Dokumen**: Foto, KTP, STR, SIP
6. **Simpan**: Klik **Simpan Data**

### 6.2 Fitur Export & Import Massal (Excel)

#### 6.2.1 Export Template Excel
1. **Akses**: Pada halaman Data Atlet/Pelatih/Medis
2. **Export Template**: Klik tombol **Export Template** (hijau)
3. **Format File**: Template Excel (.xlsx) terdownload otomatis
4. **Kolom Template**:
   - **NIK**: 16 digit angka
   - **Nama**: Nama lengkap
   - **Tempat_Lahir**: Kota kelahiran
   - **Tanggal_Lahir**: Format YYYY-MM-DD
   - **Jenis_Kelamin**: L/P
   - **Alamat**: Alamat lengkap
   - **Telepon**: Nomor HP
   - **Email**: Email (opsional)
   - **Cabor**: Nama cabor (untuk atlet)
   - **Klasifikasi_Disabilitas**: Kode klasifikasi (untuk atlet)
   - **Jenis_Disabilitas**: Nama jenis (untuk atlet)
   - **Tinggi_Badan**: Dalam cm
   - **Berat_Badan**: Dalam kg
   - **Golongan_Darah**: A/B/AB/O

#### 6.2.2 Mengisi Template Excel
1. **Buka File**: Buka template yang didownload
2. **Sheet Utama**: Isi data mulai dari baris ke-2
3. **Validasi Data**:
   - NIK: Pastikan 16 digit, unik
   - Tanggal: Format YYYY-MM-DD (2024-01-15)
   - Cabor: Harus sesuai nama di sistem
   - Klasifikasi: Kode yang valid (T11, F20, dll)
4. **Simpan**: Simpan sebagai .xlsx

#### 6.2.3 Proses Import Data
1. **Import**: Klik tombol **Import [SDM]** (biru)
2. **Pilih File**: Upload file Excel yang sudah diisi
3. **Validasi Otomatis**: Sistem memvalidasi setiap baris
4. **Preview**: Jika ada error, sistem tampilkan daftar kesalahan
5. **Konfirmasi**: Jika valid, klik **Import Data**

#### 6.2.4 Menangani Error Import
- **NIK Duplikat**: Cek apakah NIK sudah ada di sistem
- **Format Tanggal Salah**: Pastikan format YYYY-MM-DD
- **Cabor Tidak Ditemukan**: Cek ejaan nama cabor
- **Klasifikasi Invalid**: Pastikan kode klasifikasi benar
- **Solusi**: Perbaiki data di Excel, lalu import ulang

#### 6.2.5 Export Data Existing
1. **Export Data**: Klik tombol **Export** (hijau) di atas tabel
2. **Format**: Pilih Excel atau PDF
3. **Filter**: Export bisa difilter berdasarkan cabor/status
4. **Download**: File terdownload otomatis

### 6.3 Validasi Data Penting

#### 6.3.1 Validasi NIK
- **Format**: 16 digit angka (contoh: 1234567890123456)
- **Unik**: Tidak boleh sama dengan data lain di sistem
- **Wajib**: Semua SDM (Atlet, Pelatih, Medis) harus memiliki NIK
- **Error**: "NIK sudah terdaftar" atau "NIK harus 16 digit"

#### 6.3.2 Validasi Email
- **Format**: user@domain.com
- **Opsional**: Bisa dikosongkan
- **Unik**: Jika diisi, harus unik di sistem
- **Error**: "Format email tidak valid"

#### 6.3.3 Validasi Telepon
- **Format**: 08xxxxxxxxxx atau +62xxxxxxxxxx
- **Opsional**: Bisa dikosongkan
- **Error**: "Format nomor telepon tidak valid"

#### 6.3.4 Validasi File Upload
- **Foto Profil**: JPG/PNG, max 2MB, min 100x100px
- **Dokumen**: PDF/JPG/PNG, max 5MB per file
- **Jumlah**: Maksimal 5 file per SDM
- **Error**: "Ukuran file terlalu besar" atau "Format file tidak didukung"

#### 6.3.5 Validasi Data Atlet
- **Cabor**: Wajib dipilih
- **Klasifikasi Disabilitas**: Wajib dipilih
- **Jenis Disabilitas**: Wajib dipilih
- **Tanggal Lahir**: Tidak boleh di masa depan
- **Error**: "Data klasifikasi disabilitas wajib diisi"

#### 6.3.6 Validasi Data Pelatih
- **Cabor**: Wajib dipilih (bisa multiple)
- **Spesialisasi**: Opsional tapi direkomendasikan
- **Error**: "Minimal satu cabor harus dipilih"

#### 6.3.7 Validasi Data Medis
- **Spesialisasi**: Wajib dipilih
- **STR/SIP**: Opsional tapi direkomendasikan untuk validasi
- **Error**: "Spesialisasi wajib dipilih"

#### 6.3.8 Error Handling
- **Form Validation**: Error tampil di bawah field yang bermasalah
- **Server Error**: Jika gagal simpan, cek koneksi database
- **File Upload Error**: Pastikan folder storage writable
- **Duplicate Check**: Sistem cek duplikasi sebelum simpan

---

<a name="bab-7"></a>
## 7. Operasional Monitoring (Kesehatan & Latihan)

### 7.1 Pencatatan Kesehatan

#### 7.1.1 Input Test Kesehatan Atlet
1. **Akses**: Menu **Monitoring** → **Test Kesehatan**
2. **Tambah**: Klik **+ Input Test Kesehatan**
3. **Pilih Cabor**: Dropdown cabor (wajib dipilih dulu)
4. **Pilih Atlet**: Dropdown atlet berdasarkan cabor terpilih
5. **Form Parameter Medis**:
   - **Tanggal Test**: Default hari ini
   - **Kondisi Harian**: Sehat/Lelah/Cidera
   - **Tingkat Cedera**: Tidak Cidera/Ringan/Sedang/Berat
   - **Riwayat Medis**: Catatan kesehatan/detail cedera
   - **Kesimpulan**: Baik/Sedang/Berat
   - **Catatan Medis**: Instruksi khusus dari dokter
6. **Simpan**: Klik **Simpan Data**

#### 7.1.2 Input Test Kesehatan Pelatih
1. **Akses**: Menu **Monitoring** → **Test Kesehatan**
2. **Tab Pelatih**: Klik tab "Pelatih"
3. **Pilih Pelatih**: Dropdown nama pelatih
4. **Form**: Sama dengan atlet
5. **Simpan**: Klik **Simpan Data**

#### 7.1.3 Validasi Input Kesehatan
- **Cabor Wajib**: Harus dipilih sebelum pilih atlet
- **Atlet/Pelatih Wajib**: Harus dipilih
- **Tanggal**: Tidak boleh di masa depan
- **Kesimpulan**: Otomatis berdasarkan kondisi dan tingkat cedera

#### 7.1.4 Edit Data Kesehatan
1. Pada tabel, klik ikon **Edit** (pensil)
2. Ubah data sesuai kebutuhan
3. Klik **Update Data**

#### 7.1.5 Hapus Data Kesehatan
1. Klik ikon **Hapus** (sampah)
2. Konfirmasi penghapusan
3. **Peringatan**: Data kesehatan yang dihapus tidak bisa dikembalikan

### 7.2 Riwayat Kesehatan

#### 7.2.1 Melihat Riwayat Atlet
1. **Akses**: Menu **Monitoring** → **Riwayat Kesehatan**
2. **Tab Atlet**: Klik tab "Atlet"
3. **Filter Cabor**: Pilih cabor dari dropdown
4. **Pilih Atlet**: Pilih atlet dari dropdown
5. **Timeline Kesehatan**: Sistem tampilkan timeline kondisi kesehatan
6. **Detail Setiap Entry**:
   - Tanggal test
   - Kondisi harian
   - Tingkat cedera
   - Kesimpulan medis
   - Catatan dokter

#### 7.2.2 Melihat Riwayat Pelatih
1. **Tab Pelatih**: Klik tab "Pelatih"
2. **Pilih Pelatih**: Dropdown nama pelatih
3. **Timeline**: Sama dengan atlet
4. **Fokus**: Kondisi fisik pelatih untuk mengajar

#### 7.2.3 Analisis Tren Kesehatan
- **Grafik Kondisi**: Tren kondisi harian (Sehat/Lelah/Cidera)
- **Statistik Cedera**: Persentase tingkat cedera
- **Riwayat Medis**: Timeline riwayat kesehatan
- **Export Riwayat**: Export PDF untuk laporan medis

#### 7.2.4 Filter & Pencarian
- **Filter Tanggal**: Rentang tanggal tertentu
- **Filter Kondisi**: Hanya tampilkan kondisi tertentu
- **Filter Kesimpulan**: Baik/Sedang/Berat
- **Search**: Cari berdasarkan nama atlet/pelatih

#### 7.2.5 Export Laporan Kesehatan
1. Klik tombol **Export** (hijau)
2. Pilih format: PDF atau Excel
3. Pilih periode: Bulanan/Semester/Tahunan
4. Download otomatis

### 7.3 Monitoring Latihan

#### 7.3.1 Input Monitoring Latihan Atlet
1. **Akses**: Menu **Monitoring** → **Monitoring Latihan** → **Input Monitoring**
2. **Tambah**: Klik **+ Input Monitoring Baru**
3. **Pilih Cabor**: Dropdown cabor
4. **Pilih Atlet**: Dropdown atlet berdasarkan cabor
5. **Form Detail Latihan**:
   - **Tanggal Latihan**: Default hari ini
   - **Sesi**: Pagi/Sore
   - **Kehadiran**: Hadir/Tidak Hadir/Izin/Sakit
   - **Durasi Latihan**: Format JJ:MM (02:30 untuk 2 jam 30 menit)
   - **Beban Latihan**: Ringan/Sedang/Berat
   - **Denyut Nadi Awal**: BPM sebelum latihan
   - **Denyut Nadi Akhir**: BPM setelah latihan
   - **RPE (Rate of Perceived Exertion)**: Skala 1-10
   - **Catatan Pelatih**: Evaluasi performa latihan
   - **Kesimpulan**: Ya/Tidak (lanjutkan program atau evaluasi)
6. **Simpan**: Klik **Simpan Data**

#### 7.3.2 Input Monitoring Latihan Pelatih
1. **Tab Pelatih**: Klik tab "Pelatih"
2. **Pilih Pelatih**: Dropdown nama pelatih
3. **Form**: Sama dengan atlet tapi fokus pada kemampuan mengajar
4. **Simpan**: Klik **Simpan Data**

#### 7.3.3 Validasi Input Monitoring
- **Cabor Wajib**: Harus dipilih
- **Atlet/Pelatih Wajib**: Harus dipilih
- **Durasi**: Format JJ:MM valid
- **Denyut Nadi**: Range 40-200 BPM
- **RPE**: Range 1-10

#### 7.3.4 Bulk Input Monitoring
1. Klik **Bulk Input** (untuk input massal)
2. Pilih cabor dan tanggal
3. Tabel atlet muncul dengan checkbox
4. Centang atlet yang hadir
5. Isi detail latihan untuk semua
6. Simpan sekaligus

#### 7.3.5 Edit & Hapus Monitoring
- **Edit**: Klik ikon edit pada tabel
- **Hapus**: Klik ikon hapus dengan konfirmasi
- **Bulk Delete**: Centang multiple, klik "Hapus Terpilih"

### 7.4 Riwayat Latihan

#### 7.4.1 Melihat Riwayat Atlet
1. **Akses**: Menu **Monitoring** → **Riwayat Latihan**
2. **Tab Atlet**: Klik tab "Atlet"
3. **Filter Cabor**: Pilih cabor
4. **Pilih Atlet**: Pilih atlet
5. **Tabel Riwayat**: Tampilkan semua sesi latihan
6. **Kolom Data**:
   - Tanggal & Sesi
   - Kehadiran
   - Durasi
   - Beban Latihan
   - Denyut Nadi (Awal/Akhir)
   - RPE Score
   - Catatan Pelatih
   - Kesimpulan

#### 7.4.2 Melihat Riwayat Pelatih
1. **Tab Pelatih**: Klik tab "Pelatih"
2. **Pilih Pelatih**: Dropdown pelatih
3. **Riwayat**: Fokus pada performa mengajar

#### 7.4.3 Analisis Pola Latihan
- **Statistik Kehadiran**: Persentase hadir/tidak hadir
- **Rata-rata Durasi**: Rata-rata jam latihan per minggu
- **Tren Beban**: Perkembangan beban latihan
- **Denyut Nadi**: Tren denyut nadi sebelum/sesudah
- **RPE Average**: Rata-rata perceived exertion

#### 7.4.4 Filter Advanced
- **Rentang Tanggal**: Pilih periode tertentu
- **Filter Sesi**: Pagi/Sore/All
- **Filter Kehadiran**: Hadir/Tidak Hadir/All
- **Filter Beban**: Ringan/Sedang/Berat
- **Search**: Cari berdasarkan catatan

#### 7.4.5 Export Laporan Latihan
1. Klik **Export** (hijau)
2. Pilih format: PDF/Excel
3. Pilih tipe laporan:
   - **Individual**: Laporan per atlet
   - **Group**: Laporan per cabor
   - **Summary**: Ringkasan statistik
4. Pilih periode
5. Download otomatis

#### 7.4.6 Dashboard Monitoring
- **Kehadiran Hari Ini**: Real-time kehadiran
- **Alert Cedera**: Atlet dengan kondisi kurang baik
- **Statistik Mingguan**: Ringkasan performa
- **Grafik Tren**: Perkembangan kondisi atlet

---

<a name="bab-8"></a>
## 8. Program Pembinaan & Evaluasi

### 8.1 Konfigurasi Jenis Latihan (Master)

#### 8.1.1 Membuat Jenis Latihan Baru
1. **Akses**: Menu **Program Pembinaan** → **Jenis Latihan**
2. **Tambah**: Klik **+ Tambah Jenis Latihan**
3. **Form**:
   - **Nama Jenis Latihan**: Latihan Dasar, Latihan Khusus, dll
   - **Cabor**: Pilih cabor terkait
   - **Deskripsi**: Penjelasan program latihan
   - **Status**: Aktif/Tidak Aktif
4. **Simpan**: Klik **Simpan Data**

#### 8.1.2 Menambah Komponen Latihan
1. Pada tabel jenis latihan, klik ikon **Komponen** (gear)
2. Klik **+ Tambah Komponen**
3. **Form Komponen**:
   - **Nama Komponen**: Daya Tahan, Kelincahan, Kekuatan, dll
   - **Deskripsi**: Penjelasan komponen
   - **Bobot**: Persentase bobot dalam penilaian (0-100%)
4. **Konfigurasi Rentang Skor**:
   - Klik **+ Tambah Rentang**
   - **Min Value**: Nilai minimum
   - **Max Value**: Nilai maksimum
   - **Skor**: Nilai skor (1-4)
   - **Keterangan**: Kurang/Cukup/Baik/Sangat Baik
5. **Simpan**: Klik **Simpan Komponen**

#### 8.1.3 Contoh Konfigurasi Komponen
```
Komponen: "Push-up Test" (Daya Tahan)
├── Bobot: 25%
├── Rentang Skor:
│   ├── 0-5 kali = Skor 1 (Kurang)
│   ├── 6-10 kali = Skor 2 (Cukup)
│   ├── 11-15 kali = Skor 3 (Baik)
│   └── 16+ kali = Skor 4 (Sangat Baik)
```

#### 8.1.4 Edit & Hapus Komponen
- **Edit**: Klik ikon edit pada komponen
- **Hapus**: Klik ikon hapus (dengan konfirmasi)
- **Validasi**: Total bobot semua komponen harus 100%

### 8.2 Input Hasil Pembinaan

#### 8.2.1 Membuat Program Pembinaan Baru
1. **Akses**: Menu **Program Pembinaan** → **Input Program**
2. **Tambah**: Klik **+ Tambah Program Pembinaan**
3. **Pilih Atlet**: Dropdown atlet (cabor otomatis terpilih)
4. **Form Detail Program**:
   - **Tanggal Program**: Tanggal pelaksanaan
   - **Periodesasi**: Harian/Mingguan/Bulanan
   - **Intensitas**: Ringan/Sedang/Berat
   - **Jenis Latihan**: Pilih dari master data
   - **Target Performa**: Deskripsi tujuan program
   - **Catatan Program**: Instruksi khusus
5. **Simpan**: Klik **Simpan Program**

#### 8.2.2 Input Hasil Pembinaan
1. Pada program yang sudah dibuat, klik ikon **Input Hasil** (clipboard)
2. **Form Penilaian**:
   - Sistem tampilkan semua komponen dari jenis latihan
   - **Input Nilai**: Masukkan nilai aktual (angka)
   - **Skor Otomatis**: Sistem hitung skor berdasarkan rentang
   - **Keterangan**: Otomatis berdasarkan skor
3. **Total Skor**: Sistem hitung rata-rata tertimbang berdasarkan bobot
4. **Kesimpulan**: Baik/Cukup/Kurang berdasarkan total skor
5. **Simpan**: Klik **Simpan Hasil**

#### 8.2.3 Validasi Input Pembinaan
- **Atlet Wajib**: Harus dipilih
- **Jenis Latihan Wajib**: Harus dipilih
- **Nilai Numerik**: Harus angka valid
- **Rentang Valid**: Nilai harus dalam range komponen
- **Bobot**: Total bobot komponen harus 100%

#### 8.2.4 Edit & Hapus Pembinaan
- **Edit**: Klik ikon edit pada hasil pembinaan
- **Hapus**: Klik ikon hapus dengan konfirmasi
- **Bulk Input**: Input hasil untuk multiple atlet sekaligus

#### 8.2.5 Riwayat Pembinaan
1. **Akses**: Menu **Program Pembinaan** → **Riwayat Pembinaan**
2. **Filter Atlet**: Pilih atlet
3. **Tabel Riwayat**: Tampilkan semua program dan hasil
4. **Kolom**: Tanggal, Jenis Latihan, Intensitas, Total Skor, Kesimpulan

#### 8.2.6 Analisis Performa
- **Grafik Tren**: Perkembangan skor per komponen
- **Rata-rata**: Rata-rata skor per periode
- **Benchmark**: Bandingkan dengan atlet lain
- **Rekomendasi**: Sistem berikan saran berdasarkan tren

---

<a name="bab-9"></a>
## 9. Modul Tes Performa (Fisik)

### 9.1 Konfigurasi Item Tes

#### 9.1.1 Membuat Kategori Tes Baru
1. **Akses**: Menu **Tes & Evaluasi** → **Komponen Tes Fisik**
2. **Tambah Kategori**: Klik **+ Tambah Kategori**
3. **Form**:
   - **Nama Kategori**: Tes Kekuatan, Tes Daya Tahan, dll
   - **Deskripsi**: Penjelasan kategori tes
   - **Status**: Aktif/Tidak Aktif
4. **Simpan**: Klik **Simpan Kategori**

#### 9.1.2 Menambah Item Tes Fisik
1. Pada kategori, klik ikon **Item Tes** (plus)
2. Klik **+ Tambah Item Tes**
3. **Form Item**:
   - **Nama Item**: Bench Press, Squat Jump, dll
   - **Unit Pengukuran**: kg, cm, detik, kali
   - **Deskripsi**: Instruksi pelaksanaan tes
   - **Kategori Khusus**: Ya/Tidak (untuk klasifikasi disabilitas tertentu)
4. **Konfigurasi Rentang Skor**:
   - Klik **+ Tambah Rentang**
   - **Min Value**: Nilai minimum
   - **Max Value**: Nilai maksimum
   - **Skor**: Nilai skor otomatis (1-5)
   - **Kategori**: Kurang/Cukup/Baik/Sangat Baik/Excellence
5. **Simpan**: Klik **Simpan Item**

#### 9.1.3 Contoh Konfigurasi Item Tes
```
Item: "Bench Press" (kg)
├── Unit: kg
├── Rentang Skor:
│   ├── 0-20kg = Skor 1 (Kurang)
│   ├── 21-40kg = Skor 2 (Cukup)
│   ├── 41-60kg = Skor 3 (Baik)
│   ├── 61-80kg = Skor 4 (Sangat Baik)
│   └── 81+kg = Skor 5 (Excellence)
```

#### 9.1.4 Validasi Konfigurasi
- **Rentang Berurutan**: Min < Max untuk setiap rentang
- **Skor Unik**: Setiap rentang punya skor berbeda
- **Coverage**: Rentang harus cover semua kemungkinan nilai
- **Unit Konsisten**: Satuan pengukuran harus sesuai

### 9.2 Mencatat Hasil Tes Atlet

#### 9.2.1 Membuat Tes Performa Baru
1. **Akses**: Menu **Tes & Evaluasi** → **Input Tes Performa**
2. **Tambah**: Klik **+ Input Tes Baru**
3. **Pilih Atlet**: Dropdown atlet
4. **Data Otomatis**: Cabor dan klasifikasi terisi otomatis
5. **Form Detail Tes**:
   - **Tanggal Tes**: Default hari ini
   - **Jenis Tes**: Pilih kategori tes
   - **Lokasi Tes**: Tempat pelaksanaan
   - **Penguji**: Nama pelatih/medis yang menguji
   - **Catatan**: Instruksi khusus
6. **Simpan**: Klik **Simpan Tes**

#### 9.2.2 Input Hasil Tes Fisik
1. Pada tes yang dibuat, klik ikon **Input Hasil** (clipboard)
2. **Tabel Item Tes**: Sistem tampilkan semua item dari kategori terpilih
3. **Input Nilai**:
   - **Kolom Nilai**: Masukkan hasil tes (angka)
   - **Kolom Unit**: Otomatis sesuai konfigurasi
   - **Skor Otomatis**: Sistem hitung skor berdasarkan rentang
   - **Kategori**: Otomatis berdasarkan skor
4. **Total Skor**: Rata-rata semua item tes
5. **Kesimpulan**: Overall performa atlet
6. **Simpan**: Klik **Simpan Hasil**

#### 9.2.3 Validasi Input Tes
- **Atlet Wajib**: Harus dipilih
- **Jenis Tes Wajib**: Harus dipilih
- **Nilai Numerik**: Harus angka valid
- **Range Valid**: Nilai harus dalam rentang yang dikonfigurasi
- **Unit Sesuai**: Harus sesuai dengan konfigurasi item

#### 9.2.4 Edit & Hapus Hasil Tes
- **Edit**: Klik ikon edit pada hasil tes
- **Hapus**: Klik ikon hapus dengan konfirmasi
- **History**: Sistem simpan history perubahan

#### 9.2.5 Bulk Input Tes
1. Klik **Bulk Input** untuk tes massal
2. Pilih cabor dan tanggal tes
3. Pilih multiple atlet
4. Input hasil untuk semua atlet sekaligus
5. Simpan semua data

#### 9.2.6 Filter Tes Berdasarkan Klasifikasi
- **Auto-filter**: Tes khusus klasifikasi tertentu otomatis muncul
- **Contoh**: Tes T11 berbeda dengan Tes F20
- **Validasi**: Sistem cegah input tes yang tidak sesuai klasifikasi

### 9.3 Melihat Hasil Tes

#### 9.3.1 Riwayat Tes Per Atlet
1. **Akses**: Menu **Tes & Evaluasi** → **Hasil Tes Performa**
2. **Tab Atlet**: Klik tab "Atlet"
3. **Filter Cabor**: Pilih cabor
4. **Pilih Atlet**: Pilih atlet dari dropdown
5. **Tabel Riwayat**: Tampilkan semua hasil tes
6. **Kolom Data**:
   - Tanggal Tes
   - Jenis Tes
   - Item Tes
   - Nilai
   - Skor
   - Kategori
   - Total Skor
   - Kesimpulan

#### 9.3.2 Analisis Tren Performa
- **Grafik Perkembangan**: Tren skor per item tes
- **Perbandingan**: Bandingkan dengan atlet lain
- **Benchmark**: Bandingkan dengan standar nasional
- **Prediksi**: Proyeksi performa berdasarkan tren

#### 9.3.3 Filter Advanced
- **Rentang Tanggal**: Pilih periode tertentu
- **Filter Jenis Tes**: Pilih kategori tes
- **Filter Skor**: Range skor tertentu
- **Filter Klasifikasi**: Berdasarkan klasifikasi disabilitas
- **Search**: Cari berdasarkan nama atlet

#### 9.3.4 Export Laporan Tes
1. Klik **Export** (hijau)
2. Pilih format: PDF/Excel
3. Pilih tipe laporan:
   - **Individual**: Laporan per atlet
   - **Group**: Laporan per cabor/klasifikasi
   - **Comparative**: Perbandingan multiple atlet
4. Pilih periode
5. Download otomatis

#### 9.3.5 Dashboard Tes Performa
- **Rata-rata Skor**: Per cabor dan klasifikasi
- **Top Performer**: Atlet dengan skor tertinggi
- **Improvement**: Atlet dengan peningkatan terbesar
- **Alert**: Atlet yang perlu perhatian khusus

#### 9.3.6 Statistik Tes Fisik
- **Distribusi Skor**: Histogram skor per item
- **Correlation**: Hubungan antara item tes
- **Reliability**: Konsistensi hasil tes
- **Validity**: Validitas tes terhadap performa kompetisi

---

<a name="bab-10"></a>
## 10. Modul Kompetisi & Prestasi

### 10.1 Pencatatan Kompetisi

#### 10.1.1 Membuat Data Kompetisi Baru
1. **Akses**: Menu **Modul Kompetisi**
2. **Tambah**: Klik **+ Tambah Kompetisi**
3. **Tab Informasi Kompetisi**:
   - **Nama Kompetisi**: Nama event resmi
   - **Tingkatan**: Internasional/Nasional/Provinsi/Kabupaten
   - **Cabor**: Pilih cabor yang berpartisipasi
   - **Jenis Kompetisi**: Individual/Team
   - **Tanggal Mulai**: Tanggal mulai event
   - **Tanggal Selesai**: Tanggal selesai event
   - **Tempat**: Lokasi penyelenggaraan
   - **Penyelenggara**: Nama organisasi penyelenggara
   - **Jumlah Peserta**: Total peserta
   - **Status**: Rencana/Berlangsung/Selesai/Dibatalkan
4. **Tab Kontak & Administrasi**:
   - **PIC Kompetisi**: Penanggung jawab
   - **Telepon PIC**: Nomor HP PIC
   - **Email PIC**: Email PIC
   - **Website**: Link website resmi (opsional)
5. **Simpan**: Klik **Simpan Kompetisi**

#### 10.1.2 Menambah Peserta Kompetisi
1. Pada kompetisi yang dibuat, klik ikon **Peserta** (users)
2. Klik **+ Tambah Peserta**
3. **Pilih Atlet**: Dropdown atlet dari cabor terkait
4. **Detail Partisipasi**:
   - **Nomor Start**: Nomor peserta
   - **Kelas**: Kelas kompetisi (jika ada)
   - **Posisi**: Posisi dalam team (untuk team event)
5. **Simpan**: Klik **Simpan Peserta**

#### 10.1.3 Input Hasil Kompetisi
1. Pada peserta, klik ikon **Input Hasil** (medal)
2. **Form Hasil**:
   - **Peringkat**: 1/2/3/4-10/Finalis/Peserta
   - **Medali**: Emas/Perak/Perunggu/Tidak Ada
   - **Skor**: Poin yang diperoleh
   - **Waktu/Catatan**: Hasil spesifik (waktu, jarak, dll)
   - **Prestasi Tambahan**: Record baru, dll
3. **Simpan**: Klik **Simpan Hasil**

#### 10.1.4 Validasi Input Kompetisi
- **Tanggal Valid**: Tanggal selesai >= tanggal mulai
- **Cabor Match**: Atlet harus dari cabor yang dipilih
- **Peringkat Unik**: Tidak boleh ada peringkat sama untuk atlet berbeda
- **Medali Konsisten**: Medali harus sesuai peringkat (1=Emas, 2=Perak, 3=Perunggu)

#### 10.1.5 Edit & Hapus Kompetisi
- **Edit**: Klik ikon edit pada kompetisi
- **Hapus**: Klik ikon hapus (hati-hati, akan hapus semua data terkait)
- **Status Update**: Update status kompetisi sesuai progress

### 10.2 Pelaporan Prestasi

#### 10.2.1 Dashboard Prestasi
1. **Akses**: Menu **Modul Kompetisi** → **Dashboard Prestasi**
2. **Statistik Utama**:
   - **Total Medali**: Breakdown Emas/Perak/Perunggu
   - **Jumlah Kompetisi**: Total event yang diikuti
   - **Persentase Kemenangan**: Win rate per cabor
   - **Top Atlet**: Atlet dengan medali terbanyak
3. **Grafik Tren**:
   - Perkembangan medali per tahun
   - Perbandingan per cabor
   - Tren partisipasi kompetisi

#### 10.2.2 Laporan Per Atlet
1. **Akses**: Menu **Modul Kompetisi** → **Prestasi Atlet**
2. **Pilih Atlet**: Dropdown atlet
3. **Riwayat Kompetisi**: Semua event yang diikuti
4. **Statistik Personal**:
   - Total medali
   - Peringkat terbaik
   - Tren performa
   - Special achievement (record, dll)

#### 10.2.3 Laporan Per Cabor
1. **Akses**: Menu **Modul Kompetisi** → **Prestasi Cabor**
2. **Pilih Cabor**: Dropdown cabor
3. **Statistik Cabor**:
   - Total atlet aktif
   - Jumlah kompetisi
   - Medali perolehan
   - Peringkat nasional
4. **Perbandingan**: Bandingkan dengan cabor lain

#### 10.2.4 Laporan Per Tingkatan
- **Internasional**: Kompetisi internasional
- **Nasional**: PON, Kejuaraan Nasional
- **Provinsi**: Kompetisi antar provinsi
- **Kabupaten/Kota**: Kompetisi lokal

#### 10.2.5 Export Laporan Prestasi
1. Klik **Export** (hijau)
2. Pilih format: PDF/Excel/PPT
3. Pilih tipe laporan:
   - **Summary**: Ringkasan prestasi
   - **Detail**: Detail per kompetisi
   - **Comparative**: Perbandingan performa
   - **Achievement**: Daftar pencapaian
4. Pilih periode: Bulanan/Tahunan/Semua
5. Download otomatis

#### 10.2.6 Analisis Prestasi
- **SWOT Analysis**: Strength, Weakness, Opportunity, Threat
- **Gap Analysis**: Bandingkan target vs realisasi
- **Trend Analysis**: Analisis tren performa
- **Predictive**: Prediksi prestasi mendatang

#### 10.2.7 Achievement & Record
- **New Record**: Pencapaian rekor baru
- **Milestone**: Pencapaian milestone penting
- **Qualification**: Kualifikasi untuk event besar
- **Award**: Penghargaan khusus

---

<a name="bab-11"></a>
## 11. Laporan & Ekspor Data

### 11.1 Ekspor Data Master

#### 11.1.1 Export Data Cabor
1. **Akses**: Menu **Master Data** → **Data Cabor**
2. **Export**: Klik tombol **Export** (hijau) di atas tabel
3. **Format**: Pilih Excel atau PDF
4. **Kolom Export**:
   - Nama Cabor
   - Ketua, Sekretaris, Bendahara
   - Alamat Sekretariat
   - Telepon, Email
   - Tanggal SK Mulai/Berakhir
   - Status
5. **Filter**: Export bisa difilter berdasarkan status aktif/tidak aktif

#### 11.1.2 Export Data Atlet
1. **Akses**: Menu **SDM** → **Data Atlet**
2. **Export**: Klik tombol **Export** (hijau)
3. **Format**: Excel/PDF
4. **Kolom Export**:
   - NIK, Nama Lengkap
   - Cabor, Klasifikasi, Jenis Disabilitas
   - Tempat/Tanggal Lahir
   - Alamat, Telepon, Email
   - Tinggi/Berat Badan, Golongan Darah
   - Riwayat Prestasi
5. **Filter**: Berdasarkan cabor, klasifikasi, status aktif

#### 11.1.3 Export Data Pelatih & Medis
1. **Akses**: Menu **SDM** → **Data Pelatih/Medis**
2. **Export**: Klik tombol **Export**
3. **Format**: Excel/PDF
4. **Kolom**: Sama dengan atlet + spesialisasi, pengalaman, sertifikasi
5. **Filter**: Berdasarkan cabor, spesialisasi, status

#### 11.1.4 Export Klasifikasi & Jenis Disabilitas
1. **Akses**: Menu **Master Data** → **Klasifikasi Disabilitas**
2. **Export**: Klik tombol **Export**
3. **Format**: Excel/PDF
4. **Kolom**: Kode, Nama, Deskripsi, Status

#### 11.1.5 Export Template Import
1. **Export Template**: Klik tombol **Export Template** (biru)
2. **Format**: Excel (.xlsx)
3. **Kolom Template**: Format yang benar untuk import massal
4. **Validasi**: Template sudah include contoh data

### 11.2 Laporan Monitoring

#### 11.2.1 Export Riwayat Kesehatan
1. **Akses**: Menu **Monitoring** → **Riwayat Kesehatan**
2. **Pilih Personil**: Atlet atau Pelatih
3. **Filter Periode**: Pilih rentang tanggal
4. **Export**: Klik **Export Riwayat** (biru)
5. **Format**: PDF/Excel
6. **Isi Laporan**:
   - Timeline kondisi kesehatan
   - Statistik kondisi (Sehat/Lelah/Cidera)
   - Tingkat cedera
   - Riwayat medis
   - Tren kesehatan

#### 11.2.2 Export Riwayat Latihan
1. **Akses**: Menu **Monitoring** → **Riwayat Latihan**
2. **Pilih Personil**: Atlet atau Pelatih
3. **Filter Advanced**:
   - Rentang tanggal
   - Sesi (Pagi/Sore)
   - Kehadiran
   - Beban latihan
4. **Export**: Klik **Export** (hijau)
5. **Format**: PDF/Excel
6. **Isi Laporan**:
   - Statistik kehadiran
   - Durasi latihan total
   - Tren beban latihan
   - Denyut nadi rata-rata
   - RPE average
   - Catatan pelatih

#### 11.2.3 Laporan Bulk Monitoring
1. **Akses**: Menu **Monitoring** → **Laporan Bulk**
2. **Pilih Cabor**: Filter berdasarkan cabor
3. **Pilih Periode**: Bulanan/Semester/Tahunan
4. **Tipe Laporan**:
   - **Kesehatan**: Kondisi kesehatan semua atlet
   - **Latihan**: Performa latihan semua atlet
   - **Combined**: Gabungan kesehatan + latihan
5. **Export**: Klik **Generate Report**

#### 11.2.4 Dashboard Monitoring Export
1. **Akses**: Menu **Monitoring** → **Dashboard**
2. **Export Summary**: Klik **Export Dashboard**
3. **Format**: PDF (visual) atau Excel (data)
4. **Isi**:
   - Kehadiran hari ini
   - Alert cedera
   - Statistik mingguan
   - Grafik tren kondisi

#### 11.2.5 Laporan Kondisi Fisik
- **Tren Kondisi**: Perkembangan kondisi atlet
- **Alert System**: Atlet yang perlu perhatian
- **Recovery Tracking**: Monitoring recovery dari cedera
- **Performance Correlation**: Hubungan kondisi vs performa

### 11.3 Laporan Prestasi

#### 11.3.1 Export Hasil Kompetisi
1. **Akses**: Menu **Modul Kompetisi** → **Hasil Kompetisi**
2. **Filter**:
   - Tingkatan kompetisi
   - Cabor
   - Periode tanggal
3. **Export**: Klik **Export** (hijau)
4. **Format**: PDF/Excel
5. **Isi Laporan**:
   - Daftar kompetisi
   - Breakdown medali per cabor
   - Statistik per atlet
   - Peringkat umum

#### 11.3.2 Export Tes Performa
1. **Akses**: Menu **Tes & Evaluasi** → **Hasil Tes Performa**
2. **Filter**:
   - Atlet/Cabor
   - Jenis tes
   - Periode
3. **Export**: Klik **Export** (hijau)
4. **Format**: PDF/Excel
5. **Isi Laporan**:
   - Hasil tes per atlet
   - Tren perkembangan
   - Perbandingan performa
   - Analisis statistik

#### 11.3.3 Export Program Pembinaan
1. **Akses**: Menu **Program Pembinaan** → **Riwayat Pembinaan**
2. **Filter**:
   - Atlet
   - Jenis latihan
   - Periode
3. **Export**: Klik **Export** (hijau)
4. **Format**: PDF/Excel
5. **Isi Laporan**:
   - Progress pembinaan
   - Evaluasi performa
   - Rekomendasi program

#### 11.3.4 Dashboard Summary Export
1. **Akses**: Menu **Dashboard** → **Summary Report**
2. **Pilih Periode**: Bulanan/Kuartalan/Tahunan
3. **Export**: Klik **Export Summary**
4. **Format**: PDF (comprehensive report)
5. **Isi**:
   - Executive summary
   - Key performance indicators
   - Trend analysis
   - Recommendations

#### 11.3.5 Laporan Tahunan
- **Annual Report**: Laporan prestasi tahunan
- **Progress Report**: Kemajuan program pembinaan
- **Achievement Report**: Daftar pencapaian
- **SWOT Analysis**: Analisis kekuatan kelemahan

### 11.4 Template Import

#### 11.4.1 Template Import Atlet
1. **Download Template**: Klik **Export Template** pada halaman Data Atlet
2. **Struktur Kolom**:
   - **NIK**: 16 digit angka (wajib)
   - **Nama**: Nama lengkap (wajib)
   - **Tempat_Lahir**: Kota kelahiran
   - **Tanggal_Lahir**: Format YYYY-MM-DD
   - **Jenis_Kelamin**: L/P
   - **Alamat**: Alamat lengkap
   - **Telepon**: Nomor HP
   - **Email**: Email (opsional)
   - **Cabor**: Nama cabor sesuai sistem
   - **Klasifikasi_Disabilitas**: Kode klasifikasi (T11, F20, dll)
   - **Jenis_Disabilitas**: Nama jenis
   - **Tinggi_Badan**: Dalam cm
   - **Berat_Badan**: Dalam kg
   - **Golongan_Darah**: A/B/AB/O
3. **Contoh Data**: Template sudah include baris contoh

#### 11.4.2 Template Import Pelatih/Medis
1. **Download Template**: Klik **Export Template** pada halaman Data Pelatih/Medis
2. **Kolom Tambahan**:
   - **Spesialisasi**: Bidang keahlian
   - **Pengalaman**: Tahun pengalaman
   - **Sertifikasi**: Daftar sertifikat
3. **Validasi**: Sistem akan validasi spesialisasi yang ada

#### 11.4.3 Template Import Monitoring
1. **Template Kesehatan**: Untuk import data kesehatan massal
2. **Template Latihan**: Untuk import data latihan massal
3. **Kolom**: Tanggal, Personil, Parameter kesehatan/latihan
4. **Bulk Upload**: Support upload ratusan data sekaligus

#### 11.4.4 Tips Menggunakan Template
- **Jangan Ubah Header**: Kolom header tidak boleh diubah
- **Format Tanggal**: Selalu YYYY-MM-DD
- **Data Kosong**: Kosongkan cell jika tidak ada data
- **Validasi Manual**: Cek data sebelum import
- **Backup**: Simpan data asli sebelum import

### 11.5 Cara Export Data Lengkap

#### 11.5.1 Export Data Atlet/Pelatih/Medis Lengkap
1. **Buka Halaman**: Data Atlet/Pelatih/Medis
2. **Klik Export**: Tombol **Export** (hijau) di pojok kanan atas
3. **Pilih Format**: Excel (.xlsx) atau PDF
4. **Filter Opsional**: Berdasarkan cabor, status, dll
5. **Download**: File terdownload otomatis ke folder Downloads

#### 11.5.2 Export Riwayat Kesehatan Lengkap
1. **Buka Riwayat Kesehatan**
2. **Pilih Personil**: Dropdown atlet/pelatih
3. **Filter Periode**: Pilih tanggal mulai dan akhir
4. **Klik Export Riwayat**: Tombol biru
5. **Format**: PDF untuk laporan lengkap, Excel untuk data mentah

#### 11.5.3 Export Data Monitoring Latihan
1. **Buka Riwayat Latihan**
2. **Pilih Personil**: Atlet atau pelatih
3. **Filter Advanced**:
   - Rentang tanggal
   - Sesi latihan (pagi/sore)
   - Status kehadiran
   - Beban latihan
4. **Export**: Klik tombol **Export**
5. **Format**: PDF/Excel sesuai kebutuhan

#### 11.5.4 Export Laporan Gabungan
1. **Menu Laporan**: Ada menu khusus untuk laporan gabungan
2. **Pilih Tipe**: Kesehatan + Latihan, Prestasi + Tes, dll
3. **Konfigurasi**: Pilih periode, filter, format
4. **Generate**: Sistem akan buat laporan comprehensive
5. **Download**: File PDF lengkap dengan grafik dan tabel

#### 11.5.5 Scheduling Export
- **Auto Export**: Sistem bisa diatur untuk export otomatis
- **Email Report**: Laporan dikirim ke email tertentu
- **Frequency**: Harian, Mingguan, Bulanan
- **Konfigurasi**: Setup di menu Settings → Report Scheduling

3. **Export Riwayat Latihan**:
   - Buka Riwayat Latihan
   - Pilih atlet/pelatih
   - Klik **Export Data** (hijau)
   - Filter berdasarkan tanggal

4. **Export Hasil Tes Performa**:
   - Buka Hasil Tes Performa
   - Klik **Export Semua** (biru)
   - Atau filter per atlet lalu export

5. **Export Kompetisi**:
   - Buka Modul Kompetisi
   - Klik **Export Kompetisi** (hijau)
   - Mendapatkan daftar semua kompetisi dengan hasil medali

---

<a name="bab-12"></a>
## 12. Tips Navigasi & Troubleshooting

### 12.1 Navigasi Dasar
- **Pencarian**: Gunakan kotak **Cari** (di atas tabel sebelah kanan) untuk filter instan.
- **Urutan Data**: Klik judul kolom (contoh: Nama Lengkap) untuk mengurutkan A-Z atau Z-A.
- **Pagination**: Navigasi halaman di bagian bawah tabel.
- **Refresh Data**: Klik tombol **Reset Filter** jika pencarian macet.

### 12.2 Masalah Umum & Solusi

#### Error Simpan Data
- **Gejala**: Tombol simpan ditekan tapi tidak terjadi apa-apa.
- **Solusi**: Periksa field yang berwarna **Merah**. Biasanya ada data wajib yang terlewat atau format NIK salah.

#### Import Excel Gagal
- **Gejala**: Error saat upload file Excel.
- **Solusi**: Pastikan format sesuai template. Periksa NIK (16 digit), hindari karakter spesial.

#### Dropdown Tidak Muncul
- **Gejala**: Dropdown atlet kosong setelah pilih cabor.
- **Solusi**: Pastikan cabor sudah memiliki atlet terdaftar. Refresh halaman jika perlu.

#### File Upload Gagal
- **Gejala**: Error saat upload dokumen.
- **Solusi**: Periksa ukuran file (max 5MB), format yang didukung (PDF/JPG/PNG).

#### Session Timeout
- **Gejala**: Tiba-tiba logout otomatis.
- **Solusi**: Login kembali. Sistem timeout setelah 120 menit tidak aktif.

### 12.3 Optimasi Performa
- Gunakan filter pencarian untuk mengurangi data yang ditampilkan.
- Tutup tab browser yang tidak digunakan.
- Clear cache browser jika halaman lambat dimuat.

---

*Buku panduan ini merupakan dokumen hidup. Segala perubahan pada antarmuka sistem akan diperbarui pada dokumen ini. Untuk pertanyaan lebih lanjut, hubungi administrator sistem.*
## 3. Akses & Pengaturan Akun
### 3.1 Proses Login Utama
1. Masukkan alamat URL sistem pada browser.
2. Ketik **Username** pada kolom isian pertama.
3. Ketik **Password** pada kolom isian kedua.
4. Klik tombol **Login** (berwarna biru).

### 3.2 Keluar dari Sistem (Logout)
1. Klik **Nama/Foto Profil** Anda di pojok kanan atas.
2. Klik opsi **Keluar** (ikon pintu keluar).

### 3.3 Reset Password
1. Di halaman login, klik **Lupa Password?**.
2. Masukkan alamat email yang terdaftar.
3. Sistem akan mengirim link reset via email.
4. Ikuti instruksi di email untuk membuat password baru.

### 3.4 Pengaturan Profil
1. Klik nama profil → **Pengaturan Profil**.
2. Update informasi pribadi, foto profil, dan password.
3. Klik **Simpan Perubahan**.

---

<a name="bab-4"></a>
## 4. Eksplorasi Dashboard
Dashboard memberikan ringkasan statistik organisasi secara *real-time*.

### 4.1 Widget Utama
- **Jumlah Cabor**: Total cabang olahraga aktif.
- **Jumlah Atlet**: Total atlet terdaftar.
- **Jumlah Pelatih**: Total pelatih aktif.
- **Jumlah Medis**: Total tenaga medis.
- *Klik pada widget untuk menuju halaman detail terkait.*

### 4.2 Filter Berdasarkan Cabor
- Gunakan dropdown di bagian atas dashboard.
- Pilih cabang olahraga tertentu untuk menyaring statistik.

### 4.3 Grafik & Tren Performa
- **Tren Nilai Rata-rata Atlet**: Menampilkan perkembangan performa.
- **Statistik Medali**: Breakdown perolehan medali.
- **Statistik Tes Performa**: Tren hasil tes fisik.

### 4.4 Log Aktivitas
- Menampilkan aktivitas terbaru pengguna sistem.
- Klik **Lihat Detail** untuk informasi lengkap.

---

<a name="bab-5"></a>
## 5. Manajemen Pengguna & Keamanan (Khusus Admin)
*Menu: Sistem & Pengguna*

### 5.1 Manajemen Role (Izin Akses)
1. Klik sub-menu **Data Role**.
2. Klik tombol biru **Tambah Role**.
3. Masukkan **Nama Role** (cth: Pengurus Cabor).
4. Centang kotak pada kolom **Permissions** untuk menentukan fitur apa saja yang bisa diakses.
5. Klik **Simpan Data**.

### 5.2 Manajemen User (Akun Login)
1. Klik sub-menu **Data User**.
2. Klik tombol biru **Tambah User**.
3. Isi: Nama, Username, Email, Password.
4. Pilih **Role** yang sudah dibuat sebelumnya.
5. Klik **Simpan Data**.

### 5.3 Konfigurasi Keamanan
- **Two-Factor Authentication**: Aktifkan di pengaturan profil.
- **Session Timeout**: Otomatis logout setelah 120 menit tidak aktif.
- **Password Policy**: Minimal 8 karakter, kombinasi huruf dan angka.

---

<a name="bab-6"></a>
## 6. Master Data Dasar (Referensial)
*Menu: Master Data*

### 6.1 Data Cabor (Cabang Olahraga)
1. Klik sub-menu **Data Cabor**.
2. Klik tombol biru **Tambah Cabor**.
3. Masukkan nama cabang olahraga dan deskripsi singkat.

### 6.2 Klasifikasi & Jenis Disabilitas
- **Klasifikasi**: Digunakan untuk kode kelas atlet (cth: T11, F20).
- **Jenis Disabilitas**: Detail kategori (cth: Tunagrahita, Tunarungu).
- *Catatan: Setiap Atlet wajib memiliki satu Klasifikasi dan satu Jenis Disabilitas.*

### 6.3 Jenis Latihan & Komponen
1. Klik menu **Master Data** → **Jenis Latihan**.
2. Klik **Tambah Jenis Latihan**.
3. Isi nama jenis latihan (contoh: "Latihan Dasar").
4. Tambahkan komponen latihan (contoh: "Daya Tahan", "Kelincahan").
5. Untuk setiap komponen, tentukan rentang skor:
   - 0-30 cm = Skor 1
   - 31-50 cm = Skor 2
   - 51+ cm = Skor 3

### 6.4 Jenis Tes Fisik
1. Klik menu **Master Data** → **Jenis Tes**.
2. Buat kategori tes (contoh: "Tes Kekuatan").
3. Tambahkan item tes (contoh: "Bench Press" dengan unit "kg").
4. Tentukan rentang skor untuk setiap item tes.

---

<a name="bab-7"></a>
## 7. Manajemen SDM Terpadu (Pelatih, Medis, Atlet)
Halaman ini memiliki fitur paling kompleks termasuk manajemen dokumen.

### 7.1 Alur Pengisian Form Manual
1. Klik tombol **+ Tambah [SDM]** di pojok kanan atas tabel.
2. **Tab Akun & Pribadi**:
    - **Username**: Jika dikosongkan, sistem akan otomatis menggunakan NIK.
    - **Identitas**: Pastikan NIK berjumlah 16 digit.
    - **Cabor**: Pilih dari daftar yang tersedia (wajib untuk atlet).
3. **Tab Upload Dokumen**:
    - Klik tab kedua di bagian atas jendela modal.
    - Unggah berkas (KTP, Foto, Sertifikat). Format yang didukung: PDF, JPG, PNG.
4. Klik tombol biru **Simpan Data**.

### 7.2 Fitur Export & Import Massal (Excel)
1. **Langkah 1**: Klik tombol **Export Template** (warna hijau).
2. **Langkah 2**: Isi data di Excel. Hindari penggunaan tanda baca aneh pada NIK atau Nama.
3. **Langkah 3**: Klik tombol **Import [SDM]** (warna biru muda).
4. **Langkah 4**: Klik **Simpan Data**.

> [!WARNING]
> Jika muncul daftar kesalahan setelah import, perbaiki data di Excel sesuai nomor baris yang dilaporkan sistem, lalu unggah kembali.

### 7.3 Validasi Data Penting
- **NIK**: Harus 16 digit angka, unik di seluruh sistem.
- **Foto**: Format JPG/PNG, maksimal 2MB.
- **Dokumen**: PDF/JPG/PNG, maksimal 5MB per file.
- **Nama**: Tidak boleh mengandung karakter spesial.

---

<a name="bab-8"></a>
## 8. Operasional Monitoring (Kesehatan & Latihan)

### 8.1 Pencatatan Kesehatan
1. Klik menu **Monitoring** → **Test Kesehatan**.
2. Klik **+ Input Test Kesehatan**.
3. **Langkah Kritis**: Pilih **Cabor** terlebih dahulu, baru daftar **Atlet** akan muncul.
4. Isi parameter medis:
   - Kondisi Harian: Sehat/Lelah/Cidera
   - Tingkat Cedera: Tidak Cidera/Ringan/Sedang/Berat
   - Riwayat Medis: Catatan kesehatan
   - Kesimpulan: Baik/Sedang/Berat
5. Klik **Simpan**.

### 8.2 Riwayat Kesehatan
1. Klik menu **Monitoring** → **Riwayat Kesehatan**.
2. Pilih tab **Atlet** atau **Pelatih**.
3. Pilih nama personil dari dropdown.
4. Lihat timeline kesehatan dan tren kondisi.

### 8.3 Monitoring Latihan
1. Klik menu **Monitoring** → **Monitoring Latihan** → **Input Monitoring**.
2. Klik tombol **+ Input Monitoring Baru**.
3. Pilih Sesi (Pagi/Sore) dan masukkan detail:
   - Kehadiran: Hadir/Tidak Hadir/Izin/Sakit
   - Durasi Latihan: Format JJ:MM (contoh: 02:30)
   - Beban Latihan: Ringan/Sedang/Berat
   - Denyut Nadi/RPE: Skor atau denyut nadi
   - Catatan Pelatih: Evaluasi latihan
   - Kesimpulan: Ya/Tidak (lanjutkan atau evaluasi)

### 8.4 Riwayat Latihan
1. Klik menu **Monitoring** → **Riwayat Latihan**.
2. Pilih personil untuk melihat histori sesi latihan.
3. Analisis pola kehadiran dan beban latihan.

---

<a name="bab-9"></a>
## 9. Program Pembinaan & Evaluasi

### 9.1 Konfigurasi Jenis Latihan (Master)
1. Klik menu **Program Pembinaan** → **Jenis Latihan**.
2. Tambahkan komponen latihan (contoh: Daya Tahan, Kelincahan).
3. Tentukan bobot nilai untuk setiap komponen.

### 9.2 Input Hasil Pembinaan
1. Klik menu **Program Pembinaan** → **Input Program**.
2. Klik **+ Tambah Program Pembinaan**.
3. Pilih Atlet yang akan dinilai.
4. Isi detail program:
   - Tanggal program
   - Periodesasi: Harian/Mingguan/Bulanan
   - Intensitas: Ringan/Sedang/Berat
   - Jenis Latihan: Pilih dari master data
   - Target Performa: Tujuan yang ingin dicapai
5. Masukkan nilai pada setiap komponen yang muncul.
6. Sistem akan menghitung total skor secara otomatis.

---

<a name="bab-10"></a>
## 10. Modul Tes Performa (Fisik)

### 10.1 Konfigurasi Item Tes
1. Klik menu **Tes & Evaluasi** → **Komponen Tes Fisik**.
2. Buat kategori (contoh: Tes Kekuatan) dan tambahkan item di dalamnya.
3. **Sangat Penting**: Isi **Rentang Skor** agar sistem bisa melakukan penilaian otomatis.

### 10.2 Mencatat Hasil Tes Atlet
1. Klik menu **Tes & Evaluasi** → **Input Tes Performa**.
2. Klik **+ Input Tes Baru**.
3. Pilih **Atlet**. Cabor dan klasifikasi akan terisi otomatis.
4. Pada bagian bawah, masukkan hasil tes fisik atlet.
5. Perhatikan **Skor Otomatis** yang muncul di samping kolom input.
6. Klik **Simpan Tes**.

### 10.3 Melihat Hasil Tes
1. Klik menu **Tes & Evaluasi** → **Hasil Tes Performa**.
2. Filter berdasarkan atlet atau periode.
3. Lihat detail skor dan perbandingan.

---

<a name="bab-11"></a>
## 11. Modul Kompetisi & Prestasi

### 11.1 Pencatatan Kompetisi
1. Klik menu **Modul Kompetisi**.
2. Klik **Tambah Kompetisi**.
3. Isi detail event:
   - Cabor yang berpartisipasi
   - Nama kompetisi
   - Tingkatan: Internasional/Nasional/Daerah
   - Tanggal pelaksanaan
   - Tempat pelaksanaan
   - Jumlah peserta
4. Klik tombol **Pilih Atlet** untuk memasukkan atlet yang berpartisipasi.
5. Masukkan perolehan medali per atlet.
6. Klik **Simpan**.

### 11.2 Pelaporan Prestasi
- Sistem otomatis menghitung statistik medali.
- Dashboard menampilkan breakdown per cabor.
- Klik detail untuk melihat riwayat kompetisi atlet.

---

<a name="bab-12"></a>
## 12. Tips Navigasi & Troubleshooting

### 12.1 Navigasi Dasar
- **Pencarian**: Gunakan kotak **Cari** (di atas tabel sebelah kanan) untuk filter instan.
- **Urutan Data**: Klik judul kolom (contoh: Nama Lengkap) untuk mengurutkan A-Z atau Z-A.
- **Pagination**: Navigasi halaman di bagian bawah tabel.
- **Refresh Data**: Klik tombol **Reset Filter** jika pencarian macet.

### 12.2 Masalah Umum & Solusi

#### Error Simpan Data
- **Gejala**: Tombol simpan ditekan tapi tidak terjadi apa-apa.
- **Solusi**: Periksa field yang berwarna **Merah**. Biasanya ada data wajib yang terlewat atau format NIK salah.

#### Import Excel Gagal
- **Gejala**: Error saat upload file Excel.
- **Solusi**: Pastikan format sesuai template. Periksa NIK (16 digit), hindari karakter spesial.

#### Dropdown Tidak Muncul
- **Gejala**: Dropdown atlet kosong setelah pilih cabor.
- **Solusi**: Pastikan cabor sudah memiliki atlet terdaftar. Refresh halaman jika perlu.

#### File Upload Gagal
- **Gejala**: Error saat upload dokumen.
- **Solusi**: Periksa ukuran file (max 5MB), format yang didukung (PDF/JPG/PNG).

#### Session Timeout
- **Gejala**: Tiba-tiba logout otomatis.
- **Solusi**: Login kembali. Sistem timeout setelah 120 menit tidak aktif.

### 12.3 Optimasi Performa
- Gunakan filter pencarian untuk mengurangi data yang ditampilkan.
- Tutup tab browser yang tidak digunakan.
- Clear cache browser jika halaman lambat dimuat.

---

<a name="bab-13"></a>
## 13. Laporan & Ekspor Data

### 13.1 Ekspor Data Master
- Pada setiap halaman master data, klik tombol **Export** (ikon Excel).
- Pilih format: Excel (.xlsx) atau PDF.
- Data akan terunduh otomatis.

### 13.2 Laporan Monitoring
- **Riwayat Kesehatan**: Ekspor histori kesehatan per personil.
- **Riwayat Latihan**: Ekspor data kehadiran dan performa latihan.
- Filter berdasarkan periode tanggal.

### 13.3 Laporan Prestasi
- **Hasil Kompetisi**: Breakdown medali per cabor dan atlet.
- **Tes Performa**: Tren perkembangan atlet.
- **Dashboard Summary**: Ringkasan statistik organisasi.

### 13.4 Template Import
- Klik **Export Template** untuk mendapatkan format Excel yang benar.
- Gunakan template ini untuk mempersiapkan data massal.

---

<a name="bab-14"></a>
## 14. Keamanan & Privasi Data

### 14.1 Tingkat Akses Pengguna
- **Super Admin**: Akses penuh ke semua fitur.
- **Admin**: Manajemen data master dan pengguna.
- **Pelatih**: Monitoring latihan dan atlet.
- **Medis**: Akses kesehatan dan riwayat medis.
- **Atlet**: Hanya data pribadi dan riwayat sendiri.

### 14.2 Enkripsi Data
- Password di-hash menggunakan bcrypt.
- File upload disimpan di direktori terproteksi.
- Session database-backed untuk keamanan.

### 14.3 Audit Trail
- Semua aktivitas tercatat di tabel `activity_log`.
- Track perubahan data untuk compliance.
- Log login dan logout pengguna.

### 14.4 Backup & Recovery
- Lakukan backup database secara berkala.
- Simpan file upload di lokasi aman.
- Test restore procedure secara rutin.

---

<a name="bab-12"></a>
## 12. Tips Navigasi & Troubleshooting

### 12.1 Tips Navigasi Cepat

#### 12.1.1 Shortcut Keyboard
- **Ctrl+F**: Pencarian di halaman
- **Enter**: Submit form
- **Esc**: Tutup modal/popup
- **Tab**: Navigasi antar field

#### 12.1.2 Filter & Pencarian Efektif
- **Gunakan Kata Kunci**: Nama, NIK, atau kode
- **Filter Majemuk**: Gabungkan multiple filter
- **Wildcard**: Gunakan * untuk pencarian partial
- **Date Range**: Selalu tentukan periode untuk performa optimal

#### 12.1.3 DataTables Tips
- **Sorting**: Klik header kolom untuk sort
- **Pagination**: Atur "Show entries" untuk tampilan optimal
- **Column Visibility**: Sembunyikan kolom yang tidak perlu
- **Export**: Gunakan built-in export DataTables

### 12.2 Troubleshooting Umum

#### 12.2.1 Error Login
- **Username/Password Salah**: Cek kapitalisasi
- **Akun Nonaktif**: Hubungi admin untuk aktivasi
- **Session Expired**: Login ulang
- **Browser Cache**: Clear cache browser

#### 12.2.2 Error Import Excel
- **Format Salah**: Pastikan menggunakan template resmi
- **Data Duplikat**: Cek NIK sudah ada di sistem
- **Required Field**: Pastikan field wajib terisi
- **File Corrupt**: Simpan ulang file Excel

#### 12.2.3 Error Upload File
- **Ukuran Terlalu Besar**: Max 5MB per file
- **Format Tidak Didukung**: Hanya PDF/JPG/PNG
- **Permission Error**: Folder storage tidak writable
- **Disk Space**: Cek kapasitas server

#### 12.2.4 Error Database
- **Connection Timeout**: Cek koneksi internet
- **Foreign Key Error**: Pastikan data master sudah ada
- **Duplicate Entry**: Data sudah ada di sistem
- **Lock Wait Timeout**: Tunggu proses lain selesai

#### 12.2.5 Performance Issue
- **Loading Lambat**: Gunakan filter untuk limit data
- **Browser Lag**: Update browser ke versi terbaru
- **Memory Full**: Restart browser
- **Network Slow**: Cek koneksi internet

### 12.3 Optimisasi Penggunaan

#### 12.3.1 Best Practices Input Data
- **Batch Input**: Gunakan import untuk data massal
- **Validasi Dulu**: Cek data sebelum simpan
- **Backup First**: Export data sebelum bulk edit
- **Consistent Format**: Ikuti format standar sistem

#### 12.3.2 Monitoring Sistem
- **Check Logs**: Monitor error logs secara berkala
- **Database Size**: Monitor ukuran database
- **User Activity**: Track aktivitas user mencurigakan
- **Performance Metrics**: Monitor response time

#### 12.3.3 Maintenance Rutin
- **Clear Cache**: Bersihkan cache aplikasi
- **Optimize Database**: Jalankan query optimization
- **Update Dependencies**: Update package secara berkala
- **Backup Schedule**: Backup otomatis mingguan

---

<a name="bab-13"></a>
## 13. Frequently Asked Questions (FAQ)

### 13.1 FAQ Umum Sistem

#### Q: Bagaimana cara reset password?
**A**: 
1. Klik "Lupa Password" di halaman login
2. Masukkan email terdaftar
3. Ikuti instruksi di email
4. Atau hubungi admin untuk reset manual

#### Q: Mengapa tidak bisa login?
**A**: 
- Cek username dan password
- Pastikan akun aktif (hubungi admin)
- Cek koneksi internet
- Clear browser cache

#### Q: Bagaimana cara export data?
**A**: 
1. Buka halaman data yang ingin di-export
2. Klik tombol "Export" (hijau)
3. Pilih format (Excel/PDF)
4. File akan terdownload otomatis

#### Q: Error "Data tidak ditemukan"?
**A**: 
- Pastikan filter sudah benar
- Cek koneksi database
- Refresh halaman
- Hubungi admin jika berlanjut

### 13.2 FAQ Data Management

#### Q: Bagaimana cara import data massal?
**A**: 
1. Download template Excel
2. Isi data sesuai format
3. Upload file melalui menu Import
4. Sistem akan validasi otomatis
5. Perbaiki error jika ada

#### Q: Mengapa import gagal?
**A**: 
- Format file salah (harus .xlsx)
- Data tidak sesuai template
- Field wajib kosong
- Data duplikat (NIK sudah ada)

#### Q: Bagaimana cara edit data?
**A**: 
1. Cari data di tabel
2. Klik ikon "Edit" (pensil)
3. Ubah data sesuai kebutuhan
4. Klik "Update" untuk simpan

#### Q: Data hilang setelah edit?
**A**: 
- Cek apakah ada error validasi
- Pastikan klik "Update" bukan "Cancel"
- Cek koneksi database
- Data mungkin dihapus user lain

### 13.3 FAQ Monitoring & Evaluasi

#### Q: Bagaimana input monitoring harian?
**A**: 
1. Menu Monitoring → Input Monitoring
2. Pilih cabor dan atlet
3. Isi form kehadiran dan performa
4. Klik "Simpan"

#### Q: Skor tes otomatis tidak muncul?
**A**: 
- Pastikan rentang skor sudah dikonfigurasi
- Cek nilai input dalam range valid
- Refresh halaman
- Hubungi admin untuk cek konfigurasi

#### Q: Tidak bisa pilih atlet di dropdown?
**A**: 
- Pilih cabor dulu sebelum pilih atlet
- Pastikan atlet aktif dan terdaftar di cabor tersebut
- Refresh halaman jika dropdown kosong

#### Q: Bagaimana lihat riwayat atlet?
**A**: 
1. Menu Riwayat Kesehatan/Latihan
2. Pilih atlet dari dropdown
3. Filter periode jika perlu
4. Klik "Tampilkan"

### 13.4 FAQ Kompetisi & Prestasi

#### Q: Bagaimana input hasil kompetisi?
**A**: 
1. Buat data kompetisi dulu
2. Tambah peserta atlet
3. Input hasil per atlet
4. Sistem hitung medali otomatis

#### Q: Medali tidak muncul di dashboard?
**A**: 
- Pastikan hasil kompetisi sudah diinput
- Cek status kompetisi (harus "Selesai")
- Refresh dashboard
- Cek filter periode

#### Q: Bagaimana laporan prestasi?
**A**: 
1. Menu Modul Kompetisi → Dashboard Prestasi
2. Filter berdasarkan cabor/periode
3. Export laporan jika perlu

#### Q: Tidak bisa hapus data kompetisi?
**A**: 
- Data kompetisi yang sudah punya hasil tidak bisa dihapus
- Nonaktifkan status kompetisi
- Hubungi admin untuk force delete

### 13.5 FAQ Teknis

#### Q: Sistem lambat loading?
**A**: 
- Gunakan filter untuk limit data
- Clear browser cache
- Restart browser
- Cek koneksi internet

#### Q: File upload gagal?
**A**: 
- Cek ukuran file (max 5MB)
- Format file harus PDF/JPG/PNG
- Pastikan folder storage writable
- Cek kapasitas disk server

#### Q: Error 500 Internal Server Error?
**A**: 
- Cek koneksi database
- Clear application cache
- Cek error logs
- Restart web server

#### Q: Session timeout terus?
**A**: 
- Aktivitas terlalu lama idle
- Konfigurasi timeout 120 menit
- Simpan pekerjaan secara berkala
- Login ulang jika perlu

### 13.6 FAQ Role & Permission

#### Q: Tidak bisa akses menu tertentu?
**A**: 
- Cek role dan permission user
- Hubungi admin untuk update permission
- Pastikan role aktif

#### Q: Bagaimana tambah user baru?
**A**: 
1. Login sebagai admin/super admin
2. Menu Sistem & Pengguna → Data User
3. Klik "Tambah User"
4. Isi form dan pilih role
5. Simpan

#### Q: Lupa password admin?
**A**: 
- Gunakan fitur reset password
- Atau akses database langsung
- Hubungi developer untuk recovery

#### Q: Bagaimana ubah role user?
**A**: 
1. Edit user di Data User
2. Ubah field "Role"
3. Update data
4. User perlu login ulang

---

*Buku panduan ini merupakan dokumen hidup. Segala perubahan pada antarmuka sistem akan diperbarui pada dokumen ini. Untuk pertanyaan lebih lanjut, hubungi administrator sistem.*
