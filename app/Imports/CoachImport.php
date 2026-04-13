<?php

namespace App\Imports;

use App\Models\Coach;
use App\Models\Cabor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class CoachImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    public function prepareForValidation($data, $index)
    {
        $gender = $this->extractGenderValue($data);
        if ($gender !== null) {
            $data['jenis_kelamin_lp'] = $this->normalizeGenderValue($gender);
        }

        return $data;
    }

    public function model(array $row)
    {
        $row = $this->sanitizeRow($row);

        // Find Cabor by name
        $cabor = Cabor::where('name', $row['cabang_olahraga'])->first();
        
        if (!$cabor) {
            return null;
        }

        // Robust Date Parsing
        $birthDate = $row['tanggal_lahir_yyyy_mm_dd'];
        if (is_numeric($birthDate)) {
            // Handle Excel serial date
            try {
                $birthDate = Date::excelToDateTimeObject($birthDate)->format('Y-m-d');
            } catch (\Exception $e) {
                $birthDate = null;
            }
        } else if ($birthDate) {
            try {
                // Support multiple formats: DD/MM/YY, DD/MM/YYYY, YYYY-MM-DD
                $birthDate = Carbon::parse(str_replace('/', '-', $birthDate))->format('Y-m-d');
            } catch (\Exception $e) {
                // If parsing fails, validation in rules() (if any) or null
                $birthDate = null;
            }
        }

        // Ensure NIK is a string (remove scientific notation if any, though strings usually don't have it)
        $nik = (string)$row['nik'];

        $user_id = null;
        $username = $row['username_opsional'] ?? null;

        if ($username) {
            $password = (string)($row['password_opsional'] ?? '123123123');
            $email = $this->generateUniqueCoachEmail($username);

            $user = User::create([
                'name' => $row['nama_lengkap'],
                'username' => $username,
                'email' => $email,
                'email_verified_at' => now(),
                'password' => Hash::make($password)
            ]);
            $user->assignRole('Pelatih');
            $user_id = $user->id;
        }

        $gender = $row['jenis_kelamin_lp'] ?? $this->normalizeGenderValue($this->extractGenderValue($row));

        return new Coach([
            'user_id'        => $user_id,
            'cabor_id'       => $cabor->id,
            'name'           => $row['nama_lengkap'],
            'nik'            => $nik,
            'birth_place'    => $row['tempat_lahir'],
            'birth_date'     => $birthDate,
            'religion'       => $row['agama'],
            'gender'         => $gender,
            'address'        => $row['alamat'],
            'blood_type'     => $row['golongan_darah'],
            'last_education' => $row['pendidikan_terakhir'],
            'is_active'      => 1,
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
            'nik' => 'required|unique:coaches,nik',
            'cabang_olahraga' => 'required|exists:cabors,name',
            'jenis_kelamin_lp' => 'required|in:L,P',
            'tanggal_lahir_yyyy_mm_dd' => 'required',
            'username_opsional' => 'nullable|unique:users,username',
            'agama' => 'required|string',
            'tempat_lahir' => 'required|string',
            'alamat' => 'required|string',
            'pendidikan_terakhir' => 'required|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_lengkap.required' => 'Nama Lengkap wajib diisi.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.unique' => 'NIK :input sudah terdaftar.',
            'cabang_olahraga.required' => 'Cabang Olahraga wajib diisi.',
            'cabang_olahraga.exists' => 'Cabang Olahraga :input tidak ditemukan dalam sistem.',
            'jenis_kelamin_lp.required' => 'Jenis Kelamin wajib diisi.',
            'jenis_kelamin_lp.in' => 'Jenis Kelamin harus L atau P.',
            'tanggal_lahir_yyyy_mm_dd.required' => 'Tanggal Lahir wajib diisi.',
            'username_opsional.unique' => 'Username :input sudah digunakan oleh pengguna lain.',
            'agama.required' => 'Agama wajib diisi.',
            'tempat_lahir.required' => 'Tempat Lahir wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'pendidikan_terakhir.required' => 'Pendidikan Terakhir wajib diisi.',
        ];
    }

    private function extractGenderValue(array $row): mixed
    {
        $aliases = [
            'jenis_kelamin_lp',
            'jenis_kelamin_l_p',
            'jeniskelamin_lp',
            'jeniskelamin_l_p',
        ];

        foreach ($aliases as $key) {
            if (array_key_exists($key, $row) && $row[$key] !== null && $row[$key] !== '') {
                return $row[$key];
            }
        }

        foreach ($row as $key => $value) {
            if (!is_string($key)) {
                continue;
            }

            if (str_starts_with($key, 'jenis_kelamin') && str_contains($key, 'l') && str_contains($key, 'p')) {
                if ($value !== null && $value !== '') {
                    return $value;
                }
            }
        }

        return null;
    }

    private function sanitizeRow(array $row): array
    {
        $allowedKeys = [
            'nama_lengkap',
            'nik',
            'username_opsional',
            'password_opsional',
            'tempat_lahir',
            'tanggal_lahir_yyyy_mm_dd',
            'agama',
            'jenis_kelamin_lp',
            'jenis_kelamin_l_p',
            'golongan_darah',
            'alamat',
            'pendidikan_terakhir',
            'cabang_olahraga',
        ];

        $clean = [];
        foreach ($allowedKeys as $key) {
            if (!array_key_exists($key, $row)) {
                continue;
            }

            $value = $row[$key];
            $clean[$key] = is_string($value) ? trim($value) : $value;
        }

        return $clean;
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

    private function generateUniqueCoachEmail(string $username): string
    {
        $localPart = Str::of($username)
            ->lower()
            ->replaceMatches('/[^a-z0-9._-]/', '')
            ->trim('.')
            ->value();

        if ($localPart === '') {
            $localPart = 'coach';
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
