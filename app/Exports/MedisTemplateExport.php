<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class MedisTemplateExport implements FromCollection, WithHeadings, WithTitle, WithEvents
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
            'Klasifikasi (dokter/perawat/masseur)',
            'NIK',
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
        return 'Template Import Medis';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Keep NIK as text to avoid scientific notation.
                $sheet->getStyle('F2:F1000')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

                $classificationValidation = $sheet->getCell('E2')->getDataValidation();
                $classificationValidation->setType(DataValidation::TYPE_LIST);
                $classificationValidation->setFormula1('"dokter,perawat,masseur"');
                $classificationValidation->setShowDropDown(true);
                $classificationValidation->setPrompt('Pilih klasifikasi: dokter/perawat/masseur');
                for ($i = 2; $i <= 1000; $i++) {
                    $sheet->getCell("E{$i}")->setDataValidation(clone $classificationValidation);
                }

                $genderValidation = $sheet->getCell('J2')->getDataValidation();
                $genderValidation->setType(DataValidation::TYPE_LIST);
                $genderValidation->setFormula1('"L,P"');
                $genderValidation->setShowDropDown(true);
                $genderValidation->setPrompt('Pilih L atau P');
                for ($i = 2; $i <= 1000; $i++) {
                    $sheet->getCell("J{$i}")->setDataValidation(clone $genderValidation);
                }

                $religionValidation = $sheet->getCell('I2')->getDataValidation();
                $religionValidation->setType(DataValidation::TYPE_LIST);
                $religionValidation->setFormula1('"Islam,Kristen,Katolik,Hindu,Buddha,Konghucu"');
                $religionValidation->setShowDropDown(true);
                $religionValidation->setPrompt('Pilih Agama');
                for ($i = 2; $i <= 1000; $i++) {
                    $sheet->getCell("I{$i}")->setDataValidation(clone $religionValidation);
                }

                $bloodValidation = $sheet->getCell('K2')->getDataValidation();
                $bloodValidation->setType(DataValidation::TYPE_LIST);
                $bloodValidation->setFormula1('"A,B,AB,O"');
                $bloodValidation->setShowDropDown(true);
                $bloodValidation->setPrompt('Pilih Golongan Darah');
                for ($i = 2; $i <= 1000; $i++) {
                    $sheet->getCell("K{$i}")->setDataValidation(clone $bloodValidation);
                }

                foreach (range('A', 'M') as $columnID) {
                    $sheet->getColumnDimension($columnID)->setAutoSize(true);
                }
            },
        ];
    }
}
