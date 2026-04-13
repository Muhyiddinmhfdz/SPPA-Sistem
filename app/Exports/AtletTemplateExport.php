<?php

namespace App\Exports;

use App\Models\Cabor;
use App\Models\JenisDisabilitas;
use App\Models\KlasifikasiDisabilitas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AtletTemplateExport implements FromCollection, WithHeadings, WithTitle, WithEvents
{
    public function collection()
    {
        return collect([]);
    }

    public function headings(): array
    {
        return [
            'Nama Lengkap',
            'Username',
            'Email (Opsional)',
            'Password (Opsional)',
            'NIK',
            'Cabang Olahraga',
            'Kode Klasifikasi',
            'Jenis Disabilitas',
            'Tempat Lahir',
            'Tanggal Lahir (YYYY-MM-DD)',
            'Agama',
            'Jenis Kelamin (L/P)',
            'Golongan Darah',
            'Alamat',
            'Pendidikan Terakhir',
        ];
    }

    public function title(): string
    {
        return 'Template Import Atlet';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $spreadsheet = $sheet->getParent();

                $cabors = Cabor::where('is_active', 1)->orderBy('name')->pluck('name')->toArray();
                $klasifikasis = KlasifikasiDisabilitas::where('is_active', 1)->orderBy('kode_klasifikasi')->pluck('kode_klasifikasi')->toArray();
                $jenisDisabilitas = JenisDisabilitas::where('is_active', 1)->orderBy('nama_jenis')->pluck('nama_jenis')->toArray();

                $referenceName = 'REF_ATLET';
                $referenceSheet = $spreadsheet->getSheetByName($referenceName);
                if (!$referenceSheet) {
                    $referenceSheet = new Worksheet($spreadsheet, $referenceName);
                    $spreadsheet->addSheet($referenceSheet);
                }
                $referenceSheet->setSheetState(Worksheet::SHEETSTATE_HIDDEN);

                foreach ($cabors as $idx => $value) {
                    $referenceSheet->setCellValue('A' . ($idx + 1), $value);
                }
                foreach ($klasifikasis as $idx => $value) {
                    $referenceSheet->setCellValue('B' . ($idx + 1), $value);
                }
                foreach ($jenisDisabilitas as $idx => $value) {
                    $referenceSheet->setCellValue('C' . ($idx + 1), $value);
                }

                $applyListValidation = function (string $column, string $formula, string $prompt) use ($sheet) {
                    $validation = $sheet->getCell("{$column}2")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setFormula1($formula);
                    $validation->setPrompt($prompt);

                    for ($i = 2; $i <= 1000; $i++) {
                        $sheet->getCell("{$column}{$i}")->setDataValidation(clone $validation);
                    }
                };

                $caborFormula = !empty($cabors) ? "='REF_ATLET'!\$A\$1:\$A\$" . count($cabors) : '"-"';
                $klasifikasiFormula = !empty($klasifikasis) ? "='REF_ATLET'!\$B\$1:\$B\$" . count($klasifikasis) : '"-"';
                $jenisFormula = !empty($jenisDisabilitas) ? "='REF_ATLET'!\$C\$1:\$C\$" . count($jenisDisabilitas) : '"-"';

                $applyListValidation('F', $caborFormula, 'Pilih cabang olahraga');
                $applyListValidation('G', $klasifikasiFormula, 'Pilih kode klasifikasi');
                $applyListValidation('H', $jenisFormula, 'Pilih jenis disabilitas');
                $applyListValidation('K', '"Islam,Kristen,Katolik,Hindu,Buddha,Konghucu"', 'Pilih agama');
                $applyListValidation('L', '"L,P"', 'Pilih L atau P');
                $applyListValidation('M', '"A,B,AB,O"', 'Pilih golongan darah');

                // Keep NIK as text to avoid scientific notation.
                $sheet->getStyle('E2:E1000')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

                foreach (range('A', 'O') as $columnID) {
                    $sheet->getColumnDimension($columnID)->setAutoSize(true);
                }
            },
        ];
    }
}
