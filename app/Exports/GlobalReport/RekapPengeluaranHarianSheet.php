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

class RekapPengeluaranHarianSheet implements FromCollection, WithTitle, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
    protected $expenses;

    public function __construct($expenses) { $this->expenses = $expenses; }
    public function collection() { return $this->expenses; }
    public function title(): string { return 'REKAP PENGELUARAN HARIAN'; }

    public function headings(): array
    {
        return [
            ['PT. FARHAN ENERGI GASINDO'],
            ['REKAP PENGELUARAN HARIAN'],
            [''],
            ['Tanggal', 'Uraian', 'Biaya Harian', 'Keterangan']
        ];
    }

    public function map($expense): array
    {
        return [
            Carbon::parse($expense->tanggal_pengeluaran)->format('d/m/Y'),
            $expense->category->nama_kategori ?? $expense->nama_pengeluaran,
            (float)$expense->nominal,
            $expense->keterangan ?? '-',
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
        $sheet->setCellValue("A$totalRow", 'TOTAL PENGELUARAN'); $sheet->mergeCells("A$totalRow:B$totalRow");
        $sheet->setCellValue("C$totalRow", "=SUM(C5:C$lastRow)");
        $sheet->getStyle("A$totalRow:D$totalRow")->getFont()->setBold(true);
        $sheet->getStyle("A4:D$totalRow")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('C5:C' . $totalRow)->getNumberFormat()->setFormatCode('"Rp "#,##0');
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
