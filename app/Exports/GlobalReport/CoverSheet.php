<?php

namespace App\Exports\GlobalReport;

use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class CoverSheet implements WithTitle, WithEvents
{
    protected $period;

    public function __construct($period)
    {
        $this->period = $period;
    }

    public function title(): string
    {
        return 'COVER';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->setShowGridlines(false);
                $sheet->getStyle('A1:Z100')->getFont()->setName('Times New Roman');

                $sheet->mergeCells('B10:I13');
                $sheet->setCellValue('B10', "LAPORAN KEUANGAN\nPT. FARHAN ENERGI GASINDO\nAGEN LPG 3Kg BERSUBSIDI");
                $sheet->getStyle('B10')->getAlignment()->setWrapText(true);
                $sheet->getStyle('B10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B10')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('B10')->getFont()->setSize(20)->setBold(true);

                $sheet->mergeCells('B15:I15');
                $sheet->setCellValue('B15', "Jl. Siliwangi, Kawalimukti, Kawali, Ciamis");
                $sheet->getStyle('B15')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('B17:I17');
                $sheet->setCellValue('B17', "PERIODE: " . strtoupper($this->period));
                $sheet->getStyle('B17')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B17')->getFont()->setSize(14)->setBold(true);

                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
                $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
            },
        ];
    }
}
