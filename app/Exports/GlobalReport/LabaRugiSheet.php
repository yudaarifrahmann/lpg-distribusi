<?php

namespace App\Exports\GlobalReport;

use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class LabaRugiSheet implements WithTitle, WithEvents
{
    protected $totals;
    protected $period;
    public function __construct($totals, $period) { $this->totals = $totals; $this->period = $period; }
    public function title(): string { return 'LAPORAN LABA RUGI'; }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->getStyle('A1:Z100')->getFont()->setName('Times New Roman');
                $sheet->mergeCells('A1:F1'); $sheet->setCellValue('A1', 'PT. FARHAN ENERGI GASINDO');
                $sheet->mergeCells('A2:F2'); $sheet->setCellValue('A2', 'LAPORAN LABA RUGI');
                $sheet->mergeCells('A3:F3'); $sheet->setCellValue('A3', 'PERIODE: ' . strtoupper($this->period));
                $sheet->getStyle('A1:A3')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('B5', 'URAIAN'); $sheet->mergeCells('B5:E5');
                $sheet->setCellValue('F5', 'JUMLAH (RP)'); $sheet->getStyle('B5:F5')->getFont()->setBold(true);
                $sheet->getStyle('B5:F5')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $sheet->setCellValue('B7', 'I. PENDAPATAN'); $sheet->getStyle('B7')->getFont()->setBold(true);
                $sheet->setCellValue('B8', '   Modal Awal'); $sheet->setCellValue('F8', 0);
                $sheet->setCellValue('B9', '   Penjualan LPG 3Kg (Refill)'); $sheet->setCellValue('F9', (float)$this->totals['total_penjualan']);
                $sheet->setCellValue('B10', 'TOTAL PENDAPATAN'); $sheet->getStyle('B10')->getFont()->setBold(true);
                $sheet->setCellValue('F10', '=SUM(F8:F9)'); $sheet->getStyle('F10')->getFont()->setBold(true);
                $sheet->setCellValue('B12', 'II. PENGELUARAN'); $sheet->getStyle('B12')->getFont()->setBold(true);
                $sheet->setCellValue('B13', '   Pembelian Refill LPG 3Kg (HPP)'); $sheet->setCellValue('F13', (float)$this->totals['total_penebusan']);
                $sheet->setCellValue('B14', '   Uang Jalan & Operasional'); $sheet->setCellValue('F14', (float)$this->totals['total_expenses']);
                $sheet->setCellValue('B15', 'TOTAL PENGELUARAN'); $sheet->getStyle('B15')->getFont()->setBold(true);
                $sheet->setCellValue('F15', '=SUM(F13:F14)'); $sheet->getStyle('F15')->getFont()->setBold(true);
                $sheet->setCellValue('B17', 'III. LABA BERSIH'); $sheet->getStyle('B17')->getFont()->setBold(true)->setSize(12);
                $sheet->setCellValue('F17', '=F10-F15'); $sheet->getStyle('F17')->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('F8:F17')->getNumberFormat()->setFormatCode('"Rp "#,##0');
                $sheet->getStyle('B5:F17')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
                $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
            },
        ];
    }
}
