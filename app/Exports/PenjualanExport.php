<?php

namespace App\Exports;

use App\Models\Penjualan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PenjualanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Penjualan::with(['pangkalan', 'truck', 'supir']);

        if ($this->request->filled('start_date') && $this->request->filled('end_date')) {
            $query->whereBetween('tanggal_penjualan', [$this->request->start_date, $this->request->end_date]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Surat Jalan',
            'Truk',
            'Supir',
            'Pangkalan',
            'Harga',
            'Jumlah Tabung',
            'Total Penjualan',
            'Metode Pembayaran',
            'Status',
        ];
    }

    public function map($penjualan): array
    {
        return [
            $penjualan->tanggal_penjualan->format('d/m/Y'),
            $penjualan->surat_jalan,
            $penjualan->truck->nomor_polisi,
            $penjualan->supir->nama,
            $penjualan->pangkalan->nama_pangkalan,
            $penjualan->harga_lpg,
            $penjualan->jumlah_tabung,
            $penjualan->total_penjualan,
            $penjualan->metode_pembayaran,
            $penjualan->status_pembayaran,
        ];
    }
}
