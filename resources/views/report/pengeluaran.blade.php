@extends('layouts.admin')
@section('title', 'Laporan Pengeluaran - LPG Distribution')
@section('page_title', 'Laporan Rekap Biaya Operasional')

@section('content')
<div class="mb-8 grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 md:col-span-2">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Pengeluaran (Periode Ini)</p>
        <h3 class="text-3xl font-black text-red-600">Rp {{ number_format($totalPengeluaran) }}</h3>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Jumlah Transaksi</p>
        <h3 class="text-2xl font-black text-gray-800">{{ $expenses->count() }}</h3>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Rata-rata / Hari</p>
        <h3 class="text-lg font-bold text-gray-600">Rp {{ $expenses->count() > 0 ? number_format($totalPengeluaran / 30) : 0 }}</h3>
    </div>
</div>

{{-- Filters --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
    <form method="GET" class="flex flex-wrap items-end gap-4">
        @if($isSuperAdmin)
        <div class="w-full md:w-auto md:min-w-[200px]">
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Cabang</label>
            <select name="branch_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs">
                <option value="">Semua Cabang</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ $selectedBranchId == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="flex-1 max-w-sm">
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Periode</label>
            <div class="flex items-center space-x-2">
                <input type="date" name="start_date" value="{{ request('start_date', date('Y-m-01')) }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs">
                <span class="text-gray-300">-</span>
                <input type="date" name="end_date" value="{{ request('end_date', date('Y-m-t')) }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs">
            </div>
        </div>
        <button type="submit" class="bg-gray-900 text-white font-bold px-8 py-2 rounded-lg text-xs hover:bg-black transition">Filter</button>
        <button type="button" onclick="window.print()" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition">
            Print
        </button>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Category Breakdown --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100">
                <h4 class="text-xs font-black text-gray-600 uppercase tracking-widest">Rincian Per Kategori</h4>
            </div>
            <table class="w-full text-xs">
                <tbody class="divide-y divide-gray-50">
                    @foreach($categoryBreakdown as $cat)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-6 py-4 font-bold text-gray-500 uppercase">{{ $cat['nama'] }}</td>
                        <td class="px-6 py-4 text-center text-gray-400">{{ $cat['count'] }} Tx</td>
                        <td class="px-6 py-4 text-right font-black text-red-600">Rp {{ number_format($cat['total']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50/80 font-black">
                    <tr>
                        <td colspan="2" class="px-6 py-4 uppercase">Total Biaya</td>
                        <td class="px-6 py-4 text-right">Rp {{ number_format($totalPengeluaran) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Detailed List --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100">
                <h4 class="text-xs font-black text-gray-600 uppercase tracking-widest">Detail Transaksi</h4>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Tanggal</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Keterangan</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Input Oleh</th>
                            <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-400 uppercase">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($expenses as $exp)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4 text-gray-500 font-medium">{{ $exp->tanggal_pengeluaran->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                <p class="font-bold text-gray-800">{{ $exp->nama_pengeluaran }}</p>
                                <p class="text-[10px] text-gray-400 uppercase">{{ $exp->category->nama_kategori }} • {{ $exp->metode_pembayaran }}</p>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $exp->user->name }}</td>
                            <td class="px-6 py-4 text-right font-black text-gray-900">Rp {{ number_format($exp->nominal) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
