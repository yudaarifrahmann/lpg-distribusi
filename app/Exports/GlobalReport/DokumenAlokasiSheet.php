<?php

namespace App\Exports\GlobalReport;

use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class DokumenAlokasiSheet implements WithTitle, WithEvents
{
    protected $period;
    public function __construct($period) { $this->period = $period; }
    public function title(): string { return 'DOKUMEN ALOKASI LPG'; }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->getStyle('A1:Z100')->getFont()->setName('Times New Roman');
                $sheet->setCellValue('A2', 'SOLD TO'); $sheet->setCellValue('C2', ': PT. FARHAN ENERGI GASINDO');
                $sheet->setCellValue('A3', 'SHIP TO'); $sheet->setCellValue('C3', ': AGEN LPG 3KG CIAMIS');
                $sheet->setCellValue('A4', 'Schd Agrmt #'); $sheet->setCellValue('C4', ': 1000028741');
                $sheet->setCellValue('A5', 'Valid From'); $sheet->setCellValue('C5', ': 01.02.2026');
                $sheet->setCellValue('A6', 'Valid To'); $sheet->setCellValue('C6', ': 28.02.2026');
                $sheet->setCellValue('A7', 'Material Code'); $sheet->setCellValue('C7', ': 3000001');
                $sheet->setCellValue('A8', 'Description'); $sheet->setCellValue('C8', ': LPG 3KG BERSUBSIDI');
                $sheet->getStyle('A2:A8')->getFont()->setBold(true);
                $sheet->setCellValue('A10', 'DOKUMEN ALOKASI HARIAN'); $sheet->mergeCells('A10:G10');
                $sheet->getStyle('A10')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $headers = ['Tgl', 'Day', 'Alokasi (Tabung)', 'Penebusan', 'Sisa', 'Status', 'Keterangan'];
                $sheet->fromArray($headers, null, 'A12');
                $sheet->getStyle('A12:G12')->getFont()->setBold(true);
                $sheet->getStyle('A12:G12')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                for ($i = 1; $i <= 5; $i++) {
                    $row = 12 + $i;
                    $sheet->setCellValue("A$row", $i . ".02.2026");
                    $sheet->setCellValue("C$row", 560); $sheet->setCellValue("D$row", 560);
                    $sheet->setCellValue("E$row", 0); $sheet->setCellValue("F$row", 'Closed');
                }
                $lastRow = 17;
                $sheet->getStyle("A12:G$lastRow")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
                $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
            },
        ];
    }
}
