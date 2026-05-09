@extends('layouts.admin')
@section('title', 'Laporan Global - LPG Distribution')
@section('page_title', 'Laporan Global (Pemasukan, Pengeluaran & Retur)')

@section('content')
<div class="mb-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Pemasukan</p>
        <h3 class="text-2xl font-black text-emerald-600">Rp {{ number_format($summary['total_pemasukan']) }}</h3>
        <p class="text-[10px] text-gray-400 mt-2">{{ number_format($penjualans->count()) }} Transaksi</p>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Pengeluaran</p>
        <h3 class="text-2xl font-black text-red-600">Rp {{ number_format($summary['total_pengeluaran']) }}</h3>
        <p class="text-[10px] text-gray-400 mt-2">{{ number_format($expenses->count()) }} Transaksi</p>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Saldo</p>
        <h3 class="text-2xl font-black {{ $summary['saldo'] >= 0 ? 'text-blue-600' : 'text-red-600' }}">
            Rp {{ number_format($summary['saldo']) }}
        </h3>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Tabung (Penjualan)</p>
        <h3 class="text-2xl font-black text-cyan-600">{{ number_format($summary['total_tabung_penjualan']) }} <span class="text-xs font-normal text-gray-400">Pcs</span></h3>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Retur Tabung</p>
        <h3 class="text-2xl font-black text-orange-600">{{ number_format($summary['total_retur_tabung']) }} <span class="text-xs font-normal text-gray-400">Pcs</span></h3>
        <p class="text-[10px] text-gray-400 mt-2">{{ number_format($returs->count()) }} Transaksi</p>
    </div>
</div>

{{-- Filters --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Periode</label>
            <div class="flex items-center space-x-2">
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs">
                <span class="text-gray-300">-</span>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs">
            </div>
        </div>
        <div class="flex items-end space-x-2">
            <button type="submit" class="flex-1 bg-gray-900 text-white font-bold py-2 rounded-lg text-xs hover:bg-black transition">Filter</button>
            <button type="button" onclick="window.print()" class="p-2.5 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition" title="Print">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z"></path></svg>
            </button>
        </div>
    </form>
</div>

{{-- Main Report Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex justify-between items-center">
        <h4 class="text-xs font-black text-gray-600 uppercase tracking-widest">Laporan Detail Transaksi Global</h4>
        <span class="text-[10px] font-bold text-gray-400">{{ $allData->count() }} Transaksi</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-xs divide-y divide-gray-100">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase w-24">Tanggal</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Jenis</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Nama Truk</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Nama Supir</th>
                    <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase w-20">Tabung</th>
                    <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-400 uppercase w-36">Nominal</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($allData as $item)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-6 py-4 text-gray-500 font-medium whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($item['tanggal'])->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4">
                        @if($item['type'] === 'penjualan')
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-bold">Pemasukan</span>
                        @elseif($item['type'] === 'expense')
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-[10px] font-bold">Pengeluaran</span>
                        @elseif($item['type'] === 'retur')
                            <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-[10px] font-bold">Retur</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-700">{{ $item['nama_truk'] }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $item['nama_supir'] }}</td>
                    <td class="px-6 py-4 text-center font-bold text-gray-700">
                        @if($item['jumlah_tabung'] > 0)
                            {{ number_format($item['jumlah_tabung']) }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right font-bold">
                        @if($item['type'] === 'penjualan')
                            <span class="text-emerald-600">Rp {{ number_format($item['nominal']) }}</span>
                        @elseif($item['type'] === 'expense')
                            <span class="text-red-600">(Rp {{ number_format($item['nominal']) }})</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-[10px]">{{ $item['keterangan'] }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-400">Tidak ada data transaksi untuk periode ini</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-50/80 font-black">
                <tr>
                    <td colspan="4" class="px-6 py-4 uppercase text-right">TOTAL</td>
                    <td class="px-6 py-4 text-center">{{ number_format($summary['total_tabung_penjualan']) }} + {{ number_format($summary['total_retur_tabung']) }}</td>
                    <td colspan="2" class="px-6 py-4 text-right">Rp {{ number_format($summary['saldo']) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- Summary Cards by Type --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
    {{-- Pemasukan Detail --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-emerald-50/50 border-b border-emerald-100">
            <h4 class="text-xs font-black text-emerald-600 uppercase tracking-widest">Rincian Pemasukan</h4>
        </div>
        <div class="px-6 py-4">
            <p class="text-[10px] text-gray-400 uppercase mb-3">Jumlah Transaksi: <span class="font-bold text-gray-600">{{ $penjualans->count() }}</span></p>
            <p class="text-sm font-black text-emerald-600">Rp {{ number_format($summary['total_pemasukan']) }}</p>
            <p class="text-[10px] text-gray-400 mt-2">Total Tabung: <span class="font-bold text-gray-600">{{ number_format($summary['total_tabung_penjualan']) }} Pcs</span></p>
            <p class="text-[10px] text-gray-400">Rata-rata: <span class="font-bold text-gray-600">Rp {{ number_format($penjualans->count() > 0 ? $summary['total_pemasukan'] / $penjualans->count() : 0) }}</span></p>
        </div>
    </div>

    {{-- Pengeluaran Detail --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-red-50/50 border-b border-red-100">
            <h4 class="text-xs font-black text-red-600 uppercase tracking-widest">Rincian Pengeluaran</h4>
        </div>
        <div class="px-6 py-4">
            <p class="text-[10px] text-gray-400 uppercase mb-3">Jumlah Transaksi: <span class="font-bold text-gray-600">{{ $expenses->count() }}</span></p>
            <p class="text-sm font-black text-red-600">Rp {{ number_format($summary['total_pengeluaran']) }}</p>
            <p class="text-[10px] text-gray-400 mt-2">Persentase: <span class="font-bold text-gray-600">{{ $summary['total_pemasukan'] > 0 ? number_format(($summary['total_pengeluaran'] / $summary['total_pemasukan']) * 100, 2) : 0 }}%</span></p>
            <p class="text-[10px] text-gray-400">Rata-rata: <span class="font-bold text-gray-600">Rp {{ number_format($expenses->count() > 0 ? $summary['total_pengeluaran'] / $expenses->count() : 0) }}</span></p>
        </div>
    </div>

    {{-- Retur Detail --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-orange-50/50 border-b border-orange-100">
            <h4 class="text-xs font-black text-orange-600 uppercase tracking-widest">Rincian Retur Tabung</h4>
        </div>
        <div class="px-6 py-4">
            <p class="text-[10px] text-gray-400 uppercase mb-3">Jumlah Transaksi: <span class="font-bold text-gray-600">{{ $returs->count() }}</span></p>
            <p class="text-sm font-black text-orange-600">{{ number_format($summary['total_retur_tabung']) }} Pcs</p>
            <p class="text-[10px] text-gray-400 mt-2">Rata-rata per Transaksi: <span class="font-bold text-gray-600">{{ number_format($returs->count() > 0 ? $summary['total_retur_tabung'] / $returs->count() : 0) }} Pcs</span></p>
        </div>
    </div>
</div>

{{-- Print Styles --}}
<style>
    @media print {
        body {
            background: white;
        }
        .bg-gray-50\/50 {
            background-color: #f9fafb;
        }
        .border-gray-100 {
            border-color: #f3f4f6;
        }
        table {
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #e5e7eb;
        }
    }
</style>
@endsection
