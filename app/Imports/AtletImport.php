<?php

namespace App\Imports;

use App\Models\Atlet;
use App\Models\Cabor;
use App\Models\JenisDisabilitas;
use App\Models\KlasifikasiDisabilitas;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AtletImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    public function prepareForValidation($data, $index)
    {
        $data = $this->sanitizeRow($data);

        if (isset($data['tanggal_lahir_yyyy_mm_dd'])) {
            $data['tanggal_lahir_yyyy_mm_dd'] = $this->normalizeDateValue($data['tanggal_lahir_yyyy_mm_dd']);
        }

        if (isset($data['jenis_kelamin_lp'])) {
            $data['jenis_kelamin_lp'] = $this->normalizeGenderValue($data['jenis_kelamin_lp']);
        }

        if (isset($data['nik'])) {
            $data['nik'] = $this->normalizeNikValue($data['nik']);
        }

        foreach ([
            'nama_lengkap',
            'username',
            'email_opsional',
            'password_opsional',
            'cabang_olahraga',
            'kode_klasifikasi',
            'jenis_disabilitas',
            'tempat_lahir',
            'agama',
            'alamat',
            'pendidikan_terakhir',
        ] as $field) {
            if (array_key_exists($field, $data) && is_string($data[$field])) {
                $data[$field] = trim($data[$field]);
            }
        }

        return $data;
    }

    public function model(array $row)
    {
        $row = $this->sanitizeRow($row);

        $cabor = Cabor::where('name', $row['cabang_olahraga'])->first();
        $klasifikasi = KlasifikasiDisabilitas::where('kode_klasifikasi', $row['kode_klasifikasi'])->first();

        $jenisDisabilitas = null;
        if ($klasifikasi) {
            $jenisDisabilitas = JenisDisabilitas::where('nama_jenis', $row['jenis_disabilitas'])
                ->where('klasifikasi_disabilitas_id', $klasifikasi->id)
                ->first();
        }
        if (!$jenisDisabilitas) {
            $jenisDisabilitas = JenisDisabilitas::where('nama_jenis', $row['jenis_disabilitas'])->first();
        }

        if (!$cabor || !$klasifikasi || !$jenisDisabilitas) {
            return null;
        }

        $username = trim((string) $row['username']);
        $email = $this->normalizeNullableString($row['email_opsional'] ?? null) ?: $this->generateUniqueAtletEmail($username);
        $password = (string) (($row['password_opsional'] ?? null) ?: '123123123');

        $user = User::create([
            'name' => trim((string) $row['nama_lengkap']),
            'username' => $username,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => Hash::make($password),
        ]);
        $user->assignRole('Atlet');

        return new Atlet([
            'user_id' => $user->id,
            'cabor_id' => $cabor->id,
            'klasifikasi_disabilitas_id' => $klasifikasi->id,
            'jenis_disabilitas_id' => $jenisDisabilitas->id,
            'name' => trim((string) $row['nama_lengkap']),
            'jenis_disabilitas' => $jenisDisabilitas->nama_jenis,
            'nik' => $this->normalizeNikValue($row['nik']),
            'birth_place' => trim((string) $row['tempat_lahir']),
            'birth_date' => $this->normalizeDateValue($row['tanggal_lahir_yyyy_mm_dd']),
            'religion' => trim((string) $row['agama']),
            'gender' => $this->normalizeGenderValue($row['jenis_kelamin_lp']),
            'address' => trim((string) $row['alamat']),
            'blood_type' => $this->normalizeNullableString($row['golongan_darah'] ?? null),
            'last_education' => trim((string) $row['pendidikan_terakhir']),
            'is_active' => 1,
        ]);
    }

    public function isEmptyWhen(array $row): bool
    {
        $row = $this->sanitizeRow($row);

        foreach ($row as $value) {
            if ($value === null) {
                continue;
            }

            if (is_string($value) && trim($value) === '') {
                continue;
            }

            return false;
        }

        return true;
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email_opsional' => 'nullable|email|unique:users,email',
            'password_opsional' => 'nullable|string|min:6',
            'nik' => 'required|string|size:16|unique:atlets,nik',
            'cabang_olahraga' => 'required|exists:cabors,name',
            'kode_klasifikasi' => 'required|exists:klasifikasi_disabilitas,kode_klasifikasi',
            'jenis_disabilitas' => 'required|exists:jenis_disabilitas,nama_jenis',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir_yyyy_mm_dd' => 'required|date',
            'agama' => 'required|string|max:50',
            'jenis_kelamin_lp' => 'required|in:L,P',
            'golongan_darah' => 'nullable|in:A,B,AB,O',
            'alamat' => 'required|string',
            'pendidikan_terakhir' => 'required|string|max:255',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username :input sudah digunakan.',
            'email_opsional.email' => 'Format email tidak valid.',
            'email_opsional.unique' => 'Email :input sudah digunakan.',
            'password_opsional.min' => 'Password minimal 6 karakter.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.size' => 'NIK harus 16 digit.',
            'nik.unique' => 'NIK :input sudah terdaftar.',
            'cabang_olahraga.required' => 'Cabang olahraga wajib diisi.',
            'cabang_olahraga.exists' => 'Cabang olahraga :input tidak ditemukan.',
            'kode_klasifikasi.required' => 'Kode klasifikasi wajib diisi.',
            'kode_klasifikasi.exists' => 'Kode klasifikasi :input tidak ditemukan.',
            'jenis_disabilitas.required' => 'Jenis disabilitas wajib diisi.',
            'jenis_disabilitas.exists' => 'Jenis disabilitas :input tidak ditemukan.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tanggal_lahir_yyyy_mm_dd.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir_yyyy_mm_dd.date' => 'Tanggal lahir tidak valid.',
            'agama.required' => 'Agama wajib diisi.',
            'jenis_kelamin_lp.required' => 'Jenis kelamin wajib diisi.',
            'jenis_kelamin_lp.in' => 'Jenis kelamin harus L atau P.',
            'golongan_darah.in' => 'Golongan darah harus A, B, AB, atau O.',
            'alamat.required' => 'Alamat wajib diisi.',
            'pendidikan_terakhir.required' => 'Pendidikan terakhir wajib diisi.',
        ];
    }

    private function sanitizeRow(array $row): array
    {
        $allowedKeys = [
            'nama_lengkap',
            'username',
            'email_opsional',
            'password_opsional',
            'nik',
            'cabang_olahraga',
            'kode_klasifikasi',
            'jenis_disabilitas',
            'tempat_lahir',
            'tanggal_lahir_yyyy_mm_dd',
            'agama',
            'jenis_kelamin_lp',
            'golongan_darah',
            'alamat',
            'pendidikan_terakhir',
        ];

        $clean = [];
        foreach ($allowedKeys as $key) {
            if (!array_key_exists($key, $row)) {
                continue;
            }
            $clean[$key] = $row[$key];
        }

        return $clean;
    }

    private function normalizeDateValue(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value) && trim($value) === '') {
            return null;
        }

        if (is_numeric($value)) {
            try {
                return Date::excelToDateTimeObject($value)->format('Y-m-d');
            } catch (\Exception $e) {
                return (string) $value;
            }
        }

        try {
            return Carbon::parse((string) $value)->format('Y-m-d');
        } catch (\Exception $e) {
            return trim((string) $value);
        }
    }

    private function normalizeGenderValue(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $normalized = strtoupper(trim((string) $value));
        if ($normalized === '') {
            return null;
        }

        if (in_array($normalized, ['L', 'LAKI-LAKI', 'LAKI LAKI', 'LAKILAKI', 'PRIA', 'MALE', 'M'], true)) {
            return 'L';
        }

        if (in_array($normalized, ['P', 'PEREMPUAN', 'WANITA', 'FEMALE', 'F'], true)) {
            return 'P';
        }

        return $normalized;
    }

    private function normalizeNikValue(mixed $value): string
    {
        if ($value === null) {
            return '';
        }

        if (is_int($value) || is_string($value)) {
            return trim((string) $value);
        }

        if (is_float($value)) {
            return number_format($value, 0, '', '');
        }

        return trim((string) $value);
    }

    private function normalizeNullableString(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $normalized = trim((string) $value);
        return $normalized === '' ? null : $normalized;
    }

    private function generateUniqueAtletEmail(string $username): string
    {
        $localPart = Str::of($username)
            ->lower()
            ->replaceMatches('/[^a-z0-9._-]/', '')
            ->trim('.')
            ->value();

        if ($localPart === '') {
            $localPart = 'atlet';
        }

        $domain = 'npci.local';
        $email = "{$localPart}@{$domain}";
        $counter = 1;

        while (User::where('email', $email)->exists()) {
            $email = "{$localPart}{$counter}@{$domain}";
            $counter++;
        }

        return $email;
    }
}
