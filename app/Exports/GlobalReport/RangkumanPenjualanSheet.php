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

class RangkumanPenjualanSheet implements FromCollection, WithTitle, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
    protected $data;
    protected $avgCost;
    protected $currentRow = 0;
    protected $period;

    public function __construct($penjualans, $avgCost, $period)
    {
        // Fix: Group by 'harga_satuan' as defined in Penjualan model
        $this->data = $penjualans->groupBy('harga_satuan')->map(function($group) {
            return [
                'harga_jual' => (float)$group->first()->harga_satuan,
                'alokasi' => (float)$group->sum('jumlah_tabung'),
            ];
        })->values();
        
        $this->avgCost = (float)$avgCost;
        $this->period = $period;
    }

    public function collection()
    {
        return $this->data;
    }

    public function title(): string
    {
        return 'RANGKUMAN PENJUALAN';
    }

    public function headings(): array
    {
        return [
            ['RANGKUMAN PENJUALAN PERIODE ' . strtoupper($this->period)],
            ['PT. FARHAN ENERGI GASINDO'],
            ['Penjualan LPG 3 Kg'],
            [''],
            ['No', 'Alokasi', 'Harga Jual Satuan', 'Jumlah', 'Harga Satuan', 'Jumlah', 'Laba Penjualan']
        ];
    }

    public function map($item): array
    {
        $this->currentRow++;
        $rowNum = $this->currentRow + 5; // Headers occupy 5 rows (1-3 titles, 4 blank, 5 headings)

        $alokasi = $item['alokasi'];
        $hargaJual = $item['harga_jual'];
        $hargaModal = $this->avgCost;

        return [
            $this->currentRow,
            $alokasi,
            $hargaJual,
            "=B$rowNum*C$rowNum", // Jumlah 1: Alokasi * Harga Jual
            $hargaModal,
            "=B$rowNum*E$rowNum", // Jumlah 2: Alokasi * Harga Modal
            "=D$rowNum-F$rowNum", // Laba Penjualan
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:Z100')->getFont()->setName('Times New Roman');
        $lastRow = $sheet->getHighestRow();
        
        // Titles
        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');
        $sheet->mergeCells('A3:G3');
        $sheet->getStyle('A1:A3')->getFont()->setBold(true)->setSize(12);

        // Headings
        $sheet->getStyle('A5:G5')->getFont()->setBold(true);
        $sheet->getStyle('A5:G5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A5:G5')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Total Row logic
        if ($lastRow >= 6) {
            $totalRow = $lastRow + 1;
            $sheet->setCellValue("A$totalRow", 'TOTAL');
            $sheet->mergeCells("A$totalRow:A$totalRow");
            $sheet->setCellValue("B$totalRow", "=SUM(B6:B$lastRow)");
            $sheet->setCellValue("D$totalRow", "=SUM(D6:D$lastRow)");
            $sheet->setCellValue("F$totalRow", "=SUM(F6:F$lastRow)");
            $sheet->setCellValue("G$totalRow", "=SUM(G6:G$lastRow)");

            $sheet->getStyle("A$totalRow:G$totalRow")->getFont()->setBold(true);
            $sheet->getStyle("A5:G$totalRow")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            
            // Number Formatting
            $sheet->getStyle('C6:G' . $totalRow)->getNumberFormat()->setFormatCode('"Rp "#,##0');
            $sheet->getStyle('B6:B' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

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
