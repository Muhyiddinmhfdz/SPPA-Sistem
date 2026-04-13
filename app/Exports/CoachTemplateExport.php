<?php

namespace App\Exports;

use App\Models\Cabor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CoachTemplateExport implements FromCollection, WithHeadings, WithTitle, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Return empty collection for template
        return collect([]);
    }

    public function headings(): array
    {
        return [
            'Nama Lengkap',
            'NIK',
            'Username (Opsional)',
            'Password (Opsional)',
            'Tempat Lahir',
            'Tanggal Lahir (YYYY-MM-DD)',
            'Agama',
            'Jenis Kelamin (L/P)',
            'Golongan Darah',
            'Alamat',
            'Pendidikan Terakhir',
            'Cabang Olahraga',
        ];
    }

    public function title(): string
    {
        return 'Template Import Pelatih';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Get all active Cabors
                $cabors = Cabor::where('is_active', 1)->pluck('name')->toArray();
                $sheet = $event->sheet->getDelegate();
                $spreadsheet = $sheet->getParent();

                // Apply data validation to the 'Cabang Olahraga' column (Column L)
                $validation = $sheet->getCell('L2')->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setErrorTitle('Input Error');
                $validation->setError('Cabang Olahraga tidak ada dalam daftar.');
                $validation->setPromptTitle('Pilih Cabang Olahraga');
                $validation->setPrompt('Silakan pilih cabang olahraga dari daftar.');

                // Store cabor list in a hidden reference sheet so import rows stay clean.
                if (!empty($cabors)) {
                    $referenceName = 'REF_COACH';
                    $referenceSheet = $spreadsheet->getSheetByName($referenceName);
                    if (!$referenceSheet) {
                        $referenceSheet = new Worksheet($spreadsheet, $referenceName);
                        $spreadsheet->addSheet($referenceSheet);
                    }

                    $referenceSheet->setSheetState(Worksheet::SHEETSTATE_HIDDEN);
                    foreach ($cabors as $idx => $name) {
                        $row = $idx + 1;
                        $referenceSheet->setCellValue("A{$row}", $name);
                    }
                    $lastRow = count($cabors);
                    $validation->setFormula1("='REF_COACH'!\$A\$1:\$A\${$lastRow}");
                } else {
                    $validation->setFormula1('"-"');
                }

                for ($i = 2; $i <= 1000; $i++) {
                    $sheet->getCell("L$i")->setDataValidation(clone $validation);
                }

                // Add data validation for Gender (Column H)
                $genderValidation = $sheet->getCell('H2')->getDataValidation();
                $genderValidation->setType(DataValidation::TYPE_LIST);
                $genderValidation->setFormula1('"L,P"');
                $genderValidation->setShowDropDown(true);
                $genderValidation->setPrompt('Pilih L atau P');

                for ($i = 2; $i <= 1000; $i++) {
                    $sheet->getCell("H$i")->setDataValidation(clone $genderValidation);
                }

                // Add data validation for Religion (Column G)
                $religionValidation = $sheet->getCell('G2')->getDataValidation();
                $religionValidation->setType(DataValidation::TYPE_LIST);
                $religionValidation->setFormula1('"Islam,Kristen,Katolik,Hindu,Buddha,Konghucu"');
                $religionValidation->setShowDropDown(true);
                $religionValidation->setPrompt('Pilih Agama');

                for ($i = 2; $i <= 1000; $i++) {
                    $sheet->getCell("G$i")->setDataValidation(clone $religionValidation);
                }

                // Add data validation for Blood Type (Column I)
                $bloodValidation = $sheet->getCell('I2')->getDataValidation();
                $bloodValidation->setType(DataValidation::TYPE_LIST);
                $bloodValidation->setFormula1('"A,B,AB,O"');
                $bloodValidation->setShowDropDown(true);
                $bloodValidation->setPrompt('Pilih Golongan Darah');

                for ($i = 2; $i <= 1000; $i++) {
                    $sheet->getCell("I$i")->setDataValidation(clone $bloodValidation);
                }

                // Keep NIK as text to prevent scientific notation in Excel.
                $sheet->getStyle('B2:B1000')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

                // Auto-size columns
                foreach (range('A', 'L') as $columnID) {
                    $sheet->getColumnDimension($columnID)->setAutoSize(true);
                }
            },
        ];
    }
}
