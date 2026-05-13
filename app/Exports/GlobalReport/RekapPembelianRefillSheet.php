<?php

namespace App\Exports\GlobalReport;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class RekapPembelianRefillSheet implements FromCollection, WithTitle, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
    protected $penebusans;
    public function __construct($penebusans) { $this->penebusans = $penebusans; }
    public function collection() { return $this->penebusans; }
    public function title(): string { return 'REKAP PEMBELIAN REFILL'; }

    public function headings(): array
    {
        return [
            ['PT. FARHAN ENERGI GASINDO'],
            ['REKAP PEMBELIAN REFILL LPG 3KG'],
            [''],
            ['Tanggal', 'Sampai Tanggal', 'Qty', 'Biaya']
        ];
    }

    public function map($pb): array
    {
        return [
            $pb->tanggal_penebusan->format('d/m/Y'),
            $pb->tanggal_penebusan->format('d/m/Y'),
            (float)$pb->jumlah_tabung,
            (float)$pb->total_penebusan,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:Z100')->getFont()->setName('Times New Roman');
        $lastRow = $sheet->getHighestRow();
        $sheet->mergeCells('A1:D1'); $sheet->mergeCells('A2:D2');
        $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:D4')->getFont()->setBold(true);
        $totalRow = $lastRow + 1;
        $sheet->setCellValue("A$totalRow", 'TOTAL PEMBELIAN'); $sheet->mergeCells("A$totalRow:B$totalRow");
        $sheet->setCellValue("C$totalRow", "=SUM(C5:C$lastRow)"); $sheet->setCellValue("D$totalRow", "=SUM(D5:D$lastRow)");
        $sheet->getStyle("A$totalRow:D$totalRow")->getFont()->setBold(true);
        $sheet->getStyle("A4:D$totalRow")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('D5:D' . $totalRow)->getNumberFormat()->setFormatCode('"Rp "#,##0');
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
                $event->sheet->getDelegate()->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
            },
        ];
    }
}
