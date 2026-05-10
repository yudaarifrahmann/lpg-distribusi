<?php

namespace App\Exports;

use App\Models\Penjualan;
use App\Models\Expense;
use App\Models\ReturTabung;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class GlobalReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $startDate;
    protected $endDate;
    protected $user;

    public function __construct($request)
    {
        $this->startDate = $request->start_date ?? Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = $request->end_date ?? Carbon::now()->endOfMonth()->toDateString();
        $this->user = Auth::user();
    }

    public function collection()
    {
        $isSuperAdmin = $this->user->hasRole('superadmin');
        $branchId = $this->user->branch_id;
        $search = request('search');
        $paymentMethod = request('payment_method');

        // Get Penjualan
        $penjualanQuery = Penjualan::with(['truck', 'supir', 'branch'])
            ->when(!$isSuperAdmin, fn($q) => $q->where('branch_id', $branchId))
            ->whereBetween('tanggal_penjualan', [$this->startDate, $this->endDate]);

        if ($search) {
            $penjualanQuery->where(function($q) use ($search) {
                $q->whereHas('truck', fn($t) => $t->where('nomor_polisi', 'like', "%$search%"))
                  ->orWhereHas('supir', fn($s) => $s->where('nama', 'like', "%$search%"))
                  ->orWhere('nomor_invoice', 'like', "%$search%");
            });
        }

        if ($paymentMethod) {
            if ($paymentMethod === 'cash') $penjualanQuery->where('nominal_cash', '>', 0);
            elseif ($paymentMethod === 'transfer') $penjualanQuery->where('nominal_transfer', '>', 0);
            elseif ($paymentMethod === 'utang') $penjualanQuery->whereRaw('(total_penjualan - nominal_cash - nominal_transfer) > 0');
        }

        $penjualans = $penjualanQuery->get()->map(function($item) {
            return [
                'tanggal' => $item->tanggal_penjualan->format('d/m/Y'),
                'cabang' => $item->branch->name ?? '-',
                'jenis' => 'Pemasukan',
                'truk' => $item->truck->nomor_polisi ?? '-',
                'supir' => $item->supir->nama ?? '-',
                'tabung' => $item->jumlah_tabung,
                'cash' => $item->nominal_cash,
                'transfer' => $item->nominal_transfer,
                'utang' => $item->total_penjualan - $item->nominal_cash - $item->nominal_transfer,
                'total' => $item->total_penjualan,
                'keterangan' => 'Penjualan - ' . $item->nomor_invoice,
            ];
        });

        // Get Expenses
        $expenseQuery = Expense::with(['category', 'user', 'branch'])
            ->when(!$isSuperAdmin, fn($q) => $q->where('branch_id', $branchId))
            ->where('status_verifikasi', 'disetujui')
            ->whereBetween('tanggal_pengeluaran', [$this->startDate, $this->endDate]);

        if ($search) {
            $expenseQuery->where(function($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%$search%"))
                  ->orWhere('nama_pengeluaran', 'like', "%$search%");
            });
        }

        if ($paymentMethod && $paymentMethod !== 'cash') {
            $expenseQuery->where('id', 0);
        }

        $expenses = $expenseQuery->get()->map(function($item) {
            return [
                'tanggal' => Carbon::parse($item->tanggal_pengeluaran)->format('d/m/Y'),
                'cabang' => $item->branch->name ?? '-',
                'jenis' => 'Pengeluaran',
                'truk' => '-',
                'supir' => $item->user->name ?? '-',
                'tabung' => 0,
                'cash' => $item->nominal,
                'transfer' => 0,
                'utang' => 0,
                'total' => -$item->nominal,
                'keterangan' => 'Pengeluaran - ' . ($item->category ? $item->category->nama_kategori : $item->nama_pengeluaran),
            ];
        });

        // Get Retur
        $returQuery = ReturTabung::with(['truck', 'supir', 'branch'])
            ->when(!$isSuperAdmin, fn($q) => $q->where('branch_id', $branchId))
            ->whereBetween('tanggal_retur', [$this->startDate, $this->endDate])
            ->whereIn('status_retur', ['pending', 'diterima']);

        if ($search) {
            $returQuery->where(function($q) use ($search) {
                $q->whereHas('truck', fn($t) => $t->where('nomor_polisi', 'like', "%$search%"))
                  ->orWhereHas('supir', fn($s) => $s->where('nama', 'like', "%$search%"));
            });
        }

        if ($paymentMethod) {
            $returQuery->where('id', 0);
        }

        $returs = $returQuery->get()->map(function($item) {
                return [
                    'tanggal' => Carbon::parse($item->tanggal_retur)->format('d/m/Y'),
                    'cabang' => $item->branch->name ?? '-',
                    'jenis' => 'Retur',
                    'truk' => $item->truck->nomor_polisi ?? '-',
                    'supir' => $item->supir->nama ?? '-',
                    'tabung' => $item->jumlah_retur,
                    'cash' => 0,
                    'transfer' => 0,
                    'utang' => 0,
                    'total' => 0,
                    'keterangan' => 'Retur Tabung - ' . $item->kondisi_tabung . ' (' . $item->status_retur . ')',
                ];
            });

        $allData = collect()->merge($penjualans)->merge($expenses)->merge($returs)->sortBy(function($item) {
            return Carbon::createFromFormat('d/m/Y', $item['tanggal']);
        });

        // Add Total Row
        $allData->push([
            'tanggal' => 'TOTAL',
            'cabang' => '',
            'jenis' => '',
            'truk' => '',
            'supir' => '',
            'tabung' => $allData->sum('tabung'),
            'cash' => $allData->sum('cash'),
            'transfer' => $allData->sum('transfer'),
            'utang' => $allData->sum('utang'),
            'total' => $allData->sum('total'),
            'keterangan' => '',
        ]);

        return $allData;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Cabang',
            'Jenis Transaksi',
            'No Polisi Truk',
            'Nama Personil',
            'Jumlah Tabung',
            'Pembayaran Cash',
            'Pembayaran Transfer',
            'Piutang (Utang)',
            'Total Nominal',
            'Keterangan',
        ];
    }

    public function map($item): array
    {
        return [
            $item['tanggal'],
            $item['cabang'],
            $item['jenis'],
            $item['truk'],
            $item['supir'],
            $item['tabung'],
            $item['cash'],
            $item['transfer'],
            $item['utang'],
            $item['total'],
            $item['keterangan'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        return [
            1 => ['font' => ['bold' => true]],
            $lastRow => ['font' => ['bold' => true]],
        ];
    }
}
