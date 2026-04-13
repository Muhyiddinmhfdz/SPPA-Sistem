<?php

namespace App\Imports;

use App\Models\Medis;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class MedisImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    public function prepareForValidation($data, $index)
    {
        $classification = $this->extractClassificationValue($data);
        $data = $this->sanitizeRow($data);

        if ($classification !== null) {
            $data['klasifikasi_dokterperawatmasseur'] = $this->normalizeClassificationValue($classification);
        } elseif (isset($data['klasifikasi_dokterperawatmasseur'])) {
            $data['klasifikasi_dokterperawatmasseur'] = $this->normalizeClassificationValue($data['klasifikasi_dokterperawatmasseur']);
        }

        if (isset($data['jenis_kelamin_lp'])) {
            $data['jenis_kelamin_lp'] = $this->normalizeGenderValue($data['jenis_kelamin_lp']);
        }

        if (isset($data['tanggal_lahir_yyyy_mm_dd'])) {
            $data['tanggal_lahir_yyyy_mm_dd'] = $this->normalizeDateValue($data['tanggal_lahir_yyyy_mm_dd']);
        }

        foreach (['nama_lengkap', 'username', 'email_opsional', 'password_opsional', 'nik'] as $field) {
            if (array_key_exists($field, $data) && $data[$field] !== null) {
                $data[$field] = trim((string) $data[$field]);
            }
        }

        return $data;
    }

    public function model(array $row)
    {
        $classification = $this->extractClassificationValue($row);
        $row = $this->sanitizeRow($row);

        if ($classification !== null) {
            $row['klasifikasi_dokterperawatmasseur'] = $this->normalizeClassificationValue($classification);
        }

        $username = trim((string) ($row['username'] ?? ''));
        $email = $this->normalizeNullableString($row['email_opsional'] ?? null) ?: $this->generateUniqueMedisEmail($username);

        $user = User::create([
            'name' => trim((string) ($row['nama_lengkap'] ?? '')),
            'username' => $username,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => Hash::make((string) (($row['password_opsional'] ?? null) ?: '123123123')),
        ]);
        $user->assignRole('Medis');

        return new Medis([
            'user_id' => $user->id,
            'name' => trim((string) ($row['nama_lengkap'] ?? '')),
            'klasifikasi' => $this->normalizeClassificationValue($row['klasifikasi_dokterperawatmasseur'] ?? ''),
            'nik' => $this->normalizeNullableString($row['nik'] ?? null),
            'birth_place' => $this->normalizeNullableString($row['tempat_lahir'] ?? null),
            'birth_date' => $this->normalizeDateValue($row['tanggal_lahir_yyyy_mm_dd'] ?? null),
            'religion' => $this->normalizeNullableString($row['agama'] ?? null),
            'gender' => $this->normalizeGenderValue($row['jenis_kelamin_lp'] ?? null),
            'address' => $this->normalizeNullableString($row['alamat'] ?? null),
            'blood_type' => $this->normalizeNullableString($row['golongan_darah'] ?? null),
            'last_education' => $this->normalizeNullableString($row['pendidikan_terakhir'] ?? null),
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
            'klasifikasi_dokterperawatmasseur' => 'required|in:dokter,perawat,masseur',
            'nik' => 'nullable|string|max:50',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir_yyyy_mm_dd' => 'nullable|date',
            'agama' => 'nullable|string|max:50',
            'jenis_kelamin_lp' => 'nullable|in:L,P',
            'golongan_darah' => 'nullable|string|max:10',
            'alamat' => 'nullable|string',
            'pendidikan_terakhir' => 'nullable|string|max:255',
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
            'klasifikasi_dokterperawatmasseur.required' => 'Klasifikasi wajib diisi.',
            'klasifikasi_dokterperawatmasseur.in' => 'Klasifikasi harus dokter, perawat, atau masseur.',
            'tanggal_lahir_yyyy_mm_dd.date' => 'Tanggal lahir tidak valid.',
            'jenis_kelamin_lp.in' => 'Jenis kelamin harus L atau P.',
        ];
    }

    private function sanitizeRow(array $row): array
    {
        $allowedKeys = [
            'nama_lengkap',
            'username',
            'email_opsional',
            'password_opsional',
            'klasifikasi_dokterperawatmasseur',
            'klasifikasi_dokter_perawat_masseur',
            'klasifikasi',
            'nik',
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

    private function extractClassificationValue(array $row): mixed
    {
        $aliases = [
            'klasifikasi_dokterperawatmasseur',
            'klasifikasi_dokter_perawat_masseur',
            'klasifikasi',
        ];

        foreach ($aliases as $key) {
            if (!array_key_exists($key, $row)) {
                continue;
            }

            $value = $row[$key];
            if ($value === null) {
                continue;
            }

            if (is_string($value) && trim($value) === '') {
                continue;
            }

            return $value;
        }

        return null;
    }

    private function normalizeClassificationValue(mixed $value): string
    {
        return strtolower(trim((string) $value));
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

    private function normalizeNullableString(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $normalized = trim((string) $value);
        return $normalized === '' ? null : $normalized;
    }

    private function generateUniqueMedisEmail(string $username): string
    {
        $localPart = Str::of($username)
            ->lower()
            ->replaceMatches('/[^a-z0-9._-]/', '')
            ->trim('.')
            ->value();

        if ($localPart === '') {
            $localPart = 'medis';
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
