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

class RekapPenjualanHarianSheet implements FromCollection, WithTitle, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
    protected $penjualans;
    protected $currentRow = 4;
    protected $subtotalRows = [];

    public function __construct($penjualans)
    {
        $grouped = $penjualans->sortBy('tanggal_penjualan')->groupBy(function($item) {
            $date = $item->tanggal_penjualan;
            if (!($date instanceof Carbon)) { $date = Carbon::parse($date); }
            return $date->format('Y-m-d');
        });
        $flatData = collect();
        foreach ($grouped as $date => $items) {
            foreach ($items as $item) { $flatData->push($item); }
            $flatData->push((object)[
                'is_subtotal' => true,
                'tanggal_formatted' => Carbon::parse($date)->format('d/m/Y'),
                'qty' => (float)$items->sum('jumlah_tabung'),
                'jumlah' => (float)$items->sum('total_penjualan')
            ]);
        }
        $this->penjualans = $flatData;
    }

    public function collection() { return $this->penjualans; }
    public function title(): string { return 'REKAP PENJUALAN HARIAN'; }

    public function headings(): array
    {
        return [
            ['PT. FARHAN ENERGI GASINDO'],
            ['REKAP PENJUALAN HARIAN'],
            [''],
            ['Tanggal', 'Nama Pangkalan', 'Qty', 'Harga', 'Jumlah']
        ];
    }

    public function map($item): array
    {
        $this->currentRow++;
        if (isset($item->is_subtotal)) {
            $this->subtotalRows[] = $this->currentRow;
            return ['SUBTOTAL ' . $item->tanggal_formatted, '', $item->qty, '', $item->jumlah];
        }
        return [
            $item->tanggal_penjualan->format('d/m/Y'),
            $item->pangkalan->nama_pangkalan ?? '-',
            (float)$item->jumlah_tabung,
            (float)$item->harga_satuan,
            (float)$item->total_penjualan,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:Z100')->getFont()->setName('Times New Roman');
        $lastRow = $sheet->getHighestRow();
        $sheet->mergeCells('A1:E1'); $sheet->mergeCells('A2:E2');
        $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:E4')->getFont()->setBold(true);
        foreach ($this->subtotalRows as $row) {
            $sheet->getStyle("A$row:E$row")->getFont()->setBold(true);
            $sheet->getStyle("A$row:B$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->mergeCells("A$row:B$row");
        }
        $sheet->getStyle("A4:E$lastRow")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('D5:E' . $lastRow)->getNumberFormat()->setFormatCode('"Rp "#,##0');
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
