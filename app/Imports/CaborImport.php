<?php

namespace App\Imports;

use App\Models\Cabor;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class CaborImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    public function prepareForValidation($data, $index)
    {
        $data = $this->sanitizeRow($data);

        $data['sk_mulai_yyyy_mm_dd'] = $this->normalizeDateValue($data['sk_mulai_yyyy_mm_dd'] ?? null);
        $data['sk_akhir_yyyy_mm_dd'] = $this->normalizeDateValue($data['sk_akhir_yyyy_mm_dd'] ?? null);

        return $data;
    }

    public function model(array $row)
    {
        $row = $this->sanitizeRow($row);

        return new Cabor([
            'name' => $this->normalizeNullableString($row['nama_cabang_olahraga'] ?? null),
            'sk_start_date' => $this->normalizeDateValue($row['sk_mulai_yyyy_mm_dd'] ?? null),
            'sk_end_date' => $this->normalizeDateValue($row['sk_akhir_yyyy_mm_dd'] ?? null),
            'chairman_name' => $this->normalizeNullableString($row['nama_ketua'] ?? null),
            'secretary_name' => $this->normalizeNullableString($row['nama_sekretaris'] ?? null),
            'treasurer_name' => $this->normalizeNullableString($row['nama_bendahara'] ?? null),
            'secretariat_address' => $this->normalizeNullableString($row['alamat_sekretariat'] ?? null),
            'phone_number' => $this->normalizeNullableString($row['nomor_tlpwa'] ?? null),
            'email' => $this->normalizeNullableString($row['email'] ?? null),
            'npwp' => $this->normalizeNullableString($row['npwp'] ?? null),
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
            'nama_cabang_olahraga' => 'required|string|max:255',
            'sk_mulai_yyyy_mm_dd' => 'nullable|date',
            'sk_akhir_yyyy_mm_dd' => 'nullable|date|after_or_equal:sk_mulai_yyyy_mm_dd',
            'nama_ketua' => 'nullable|string|max:255',
            'nama_sekretaris' => 'nullable|string|max:255',
            'nama_bendahara' => 'nullable|string|max:255',
            'alamat_sekretariat' => 'nullable|string',
            'nomor_tlpwa' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'npwp' => 'nullable|string|max:255',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_cabang_olahraga.required' => 'Nama Cabang Olahraga wajib diisi.',
            'sk_mulai_yyyy_mm_dd.date' => 'Tanggal SK Mulai tidak valid.',
            'sk_akhir_yyyy_mm_dd.date' => 'Tanggal SK Akhir tidak valid.',
            'sk_akhir_yyyy_mm_dd.after_or_equal' => 'Tanggal SK Akhir harus sama atau setelah Tanggal SK Mulai.',
            'email.email' => 'Format email tidak valid.',
        ];
    }

    private function sanitizeRow(array $row): array
    {
        $allowedKeys = [
            'nama_cabang_olahraga',
            'sk_mulai_yyyy_mm_dd',
            'sk_akhir_yyyy_mm_dd',
            'nama_ketua',
            'nama_sekretaris',
            'nama_bendahara',
            'alamat_sekretariat',
            'nomor_tlpwa',
            'email',
            'npwp',
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
    private function normalizeNullableString(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $normalized = trim((string) $value);
        return $normalized === '' ? null : $normalized;
    }
}
