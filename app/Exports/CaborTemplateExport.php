<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class CaborTemplateExport implements FromCollection, WithHeadings, WithTitle, WithEvents
{
    public function collection()
    {
        return collect([]);
    }

    public function headings(): array
    {
        return [
            'Nama Cabang Olahraga',
            'SK Mulai (YYYY-MM-DD)',
            'SK Akhir (YYYY-MM-DD)',
            'Nama Ketua',
            'Nama Sekretaris',
            'Nama Bendahara',
            'Alamat Sekretariat',
            'Nomor Tlp/WA',
            'Email',
            'NPWP',
        ];
    }

    public function title(): string
    {
        return 'Template Import Cabor';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Keep phone and NPWP as text to preserve leading zeros.
                $sheet->getStyle('H2:H1000')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                $sheet->getStyle('J2:J1000')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

                foreach (range('A', 'J') as $columnID) {
                    $sheet->getColumnDimension($columnID)->setAutoSize(true);
                }
            },
        ];
    }
}
