<?php

namespace App\Exports;

use App\Models\HistoriMutasiTitipan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\Auth;

class DepositMutationExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = HistoriMutasiTitipan::with('user')->where('branch_id', Auth::user()->branch_id);

        if ($this->request->filled('start_date') && $this->request->filled('end_date')) {
            $query->whereBetween('tanggal', [$this->request->start_date, $this->request->end_date]);
        }

        if ($this->request->filled('pemilik_tabung')) {
            $query->where('pemilik_tabung', 'like', '%' . $this->request->pemilik_tabung . '%');
        }

        if ($this->request->filled('jenis_mutasi')) {
            $query->where('jenis_mutasi', $this->request->jenis_mutasi);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Jenis Mutasi',
            'Pemilik Tabung',
            'Peminjam',
            'Jumlah',
            'Stok Sebelum',
            'Stok Sesudah',
            'Keterangan',
            'User Input',
        ];
    }

    public function map($m): array
    {
        return [
            $m->tanggal->format('d/m/Y'),
            ucfirst($m->jenis_mutasi),
            $m->pemilik_tabung,
            $m->peminjam ?? '-',
            $m->jumlah,
            $m->stok_sebelum,
            $m->stok_sesudah,
            $m->keterangan ?? '-',
            $m->user->name ?? '-',
        ];
    }
}
