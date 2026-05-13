@extends('layouts.admin')
@section('title', 'Laporan Global - LPG Distribution')
@section('page_title', 'Laporan Global (Pemasukan, Pengeluaran & Retur)')

@section('content')
<div class="mb-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
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
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Penebusan DO</p>
        <h3 class="text-2xl font-black text-blue-600">Rp {{ number_format($summary['total_penebusan']) }}</h3>
        <p class="text-[10px] text-gray-400 mt-2">{{ number_format($summary['total_tabung_penebusan']) }} Pcs</p>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Laba Bersih (Est)</p>
        <h3 class="text-2xl font-black {{ $summary['saldo'] >= 0 ? 'text-indigo-600' : 'text-red-600' }}">
            Rp {{ number_format($summary['saldo']) }}
        </h3>
        <p class="text-[10px] text-gray-400 mt-2">Pemasukan - (Beban + Penebusan)</p>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Tabung (Jual)</p>
        <h3 class="text-2xl font-black text-cyan-600">{{ number_format($summary['total_tabung_penjualan']) }} <span class="text-xs font-normal text-gray-400">Pcs</span></h3>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Retur</p>
        <h3 class="text-2xl font-black text-orange-600">{{ number_format($summary['total_retur_tabung']) }} <span class="text-xs font-normal text-gray-400">Pcs</span></h3>
        <p class="text-[10px] text-gray-400 mt-2">{{ number_format($returs->count()) }} Transaksi</p>
    </div>
</div>

{{-- Filters --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Cari Transaksi</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Truk / Supir / Invoice / DO..." class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs">
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Metode Pembayaran</label>
            <select name="payment_method" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs">
                <option value="">Semua Metode</option>
                <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                <option value="utang" {{ request('payment_method') == 'utang' ? 'selected' : '' }}>Utang (Piutang)</option>
            </select>
        </div>
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
            <a href="{{ route('report.global.export', request()->all()) }}" class="p-2.5 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-100 transition" title="Export Excel">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </a>
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
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Cabang</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Jenis</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Truk/Supir</th>
                    <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase w-16">Tabung</th>
                    <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-400 uppercase">Cash</th>
                    <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-400 uppercase">Transfer</th>
                    <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-400 uppercase">Utang</th>
                    <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-400 uppercase w-32">Total</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($allData as $item)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-6 py-4 text-gray-500 font-medium whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($item['tanggal'])->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-600">{{ $item['cabang'] }}</td>
                    <td class="px-6 py-4">
                        @if($item['type'] === 'penjualan')
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-bold">Penjualan</span>
                        @elseif($item['type'] === 'expense')
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-[10px] font-bold">Pengeluaran</span>
                        @elseif($item['type'] === 'penebusan')
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-[10px] font-bold">Penebusan</span>
                        @elseif($item['type'] === 'retur')
                            <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-[10px] font-bold">Retur</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-700">{{ $item['nama_truk'] }}</p>
                        <p class="text-[10px] text-gray-400">{{ $item['nama_supir'] }}</p>
                    </td>
                    <td class="px-6 py-4 text-center font-bold text-gray-700">
                        {{ $item['jumlah_tabung'] > 0 ? number_format($item['jumlah_tabung']) : '-' }}
                    </td>
                    <td class="px-6 py-4 text-right font-medium text-gray-600">
                        {{ $item['cash'] > 0 ? number_format($item['cash']) : '-' }}
                    </td>
                    <td class="px-6 py-4 text-right font-medium text-blue-600">
                        {{ $item['transfer'] > 0 ? number_format($item['transfer']) : '-' }}
                    </td>
                    <td class="px-6 py-4 text-right font-medium text-amber-600">
                        {{ $item['utang'] > 0 ? number_format($item['utang']) : '-' }}
                    </td>
                    <td class="px-6 py-4 text-right font-black">
                        @if($item['type'] === 'penjualan')
                            <span class="text-emerald-600">Rp {{ number_format($item['nominal']) }}</span>
                        @elseif($item['type'] === 'expense')
                            <span class="text-red-600">(Rp {{ number_format($item['nominal']) }})</span>
                        @elseif($item['type'] === 'penebusan')
                            <span class="text-blue-600">(Rp {{ number_format($item['nominal']) }})</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-[10px]">{{ $item['keterangan'] }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-6 py-8 text-center text-gray-400">Tidak ada data transaksi untuk periode ini</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-50/80 font-black text-[10px]">
                <tr>
                    <td colspan="4" class="px-6 py-4 uppercase text-right">TOTAL SALDO AKHIR</td>
                    <td class="px-6 py-4 text-center text-gray-900">{{ number_format($summary['total_tabung_penjualan']) }}</td>
                    <td class="px-6 py-4 text-right text-gray-900">{{ number_format($summary['total_cash']) }}</td>
                    <td class="px-6 py-4 text-right text-blue-700">{{ number_format($summary['total_transfer']) }}</td>
                    <td class="px-6 py-4 text-right text-amber-700">{{ number_format($summary['total_utang']) }}</td>
                    <td class="px-6 py-4 text-right text-indigo-700">Rp {{ number_format($summary['saldo']) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- Summary Cards by Type --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
    {{-- Pemasukan Detail --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-emerald-50/50 border-b border-emerald-100">
            <h4 class="text-xs font-black text-emerald-600 uppercase tracking-widest">Rincian Penjualan</h4>
        </div>
        <div class="px-6 py-4">
            <p class="text-[10px] text-gray-400 uppercase mb-3">Jumlah Transaksi: <span class="font-bold text-gray-600">{{ $penjualans->count() }}</span></p>
            <p class="text-sm font-black text-emerald-600">Rp {{ number_format($summary['total_pemasukan']) }}</p>
            <p class="text-[10px] text-gray-400 mt-2">Total Tabung: <span class="font-bold text-gray-600">{{ number_format($summary['total_tabung_penjualan']) }} Pcs</span></p>
        </div>
    </div>

    {{-- Penebusan Detail --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-blue-50/50 border-b border-blue-100">
            <h4 class="text-xs font-black text-blue-600 uppercase tracking-widest">Rincian Penebusan DO</h4>
        </div>
        <div class="px-6 py-4">
            <p class="text-[10px] text-gray-400 uppercase mb-3">Jumlah DO: <span class="font-bold text-gray-600">{{ $summary['total_penebusan'] > 0 ? 'Tersedia' : '0' }}</span></p>
            <p class="text-sm font-black text-blue-600">Rp {{ number_format($summary['total_penebusan']) }}</p>
            <p class="text-[10px] text-gray-400 mt-2">HPP (Modal Tabung)</p>
        </div>
    </div>

    {{-- Pengeluaran Detail --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-red-50/50 border-b border-red-100">
            <h4 class="text-xs font-black text-red-600 uppercase tracking-widest">Rincian Pengeluaran</h4>
        </div>
        <div class="px-6 py-4">
            <p class="text-[10px] text-gray-400 uppercase mb-3">Jumlah Transaksi: <span class="font-bold text-gray-600">{{ $expenses->count() }}</span></p>
            <p class="text-sm font-black text-red-600">Rp {{ number_format($expenses->sum('nominal')) }}</p>
            <p class="text-[10px] text-gray-400 mt-2">Beban Operasional</p>
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
        </div>
    </div>
</div>
</div>

{{-- Print Styles --}}
<style>
    @media print {
        /* Hide UI elements */
        aside, nav, .mb-8.grid, form, button, a, tfoot td:last-child {
            display: none !important;
        }
        
        /* Reset layout for print */
        body, main, .flex-1, .p-6 {
            height: auto !important;
            overflow: visible !important;
            padding: 0 !important;
            margin: 0 !important;
            background: white !important;
        }

        .bg-white {
            border: none !important;
            box-shadow: none !important;
        }

        /* Table styling for print */
        table {
            width: 100% !important;
            border-collapse: collapse !important;
            font-size: 9px !important; /* Smaller text to fit more columns */
        }
        
        th, td {
            border: 1px solid #ddd !important;
            padding: 6px !important;
        }

        .px-6 { padding-left: 0.5rem !important; padding-right: 0.5rem !important; }
        
        /* Ensure headers repeat on each page */
        thead { display: table-header-group; }
        
        /* Zebra striping for readability */
        tr:nth-child(even) { background-color: #f9f9f9 !important; }
    }
</style>
@endsection
