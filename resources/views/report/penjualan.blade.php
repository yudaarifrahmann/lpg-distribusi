@extends('layouts.admin')
@section('title', 'Laporan Penjualan - LPG Distribution')
@section('page_title', 'Laporan Rekap Penjualan')

@section('content')
<div class="mb-8 grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Tabung</p>
        <h3 class="text-2xl font-black text-gray-800">{{ number_format($summary['total_tabung']) }} <span class="text-xs font-normal text-gray-400">Pcs</span></h3>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Omzet</p>
        <h3 class="text-2xl font-black text-emerald-600">Rp {{ number_format($summary['total_omzet']) }}</h3>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tunai/Transfer</p>
        <h3 class="text-lg font-bold text-blue-600">Rp {{ number_format($summary['cash'] + $summary['transfer']) }}</h3>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Piutang</p>
        <h3 class="text-lg font-bold text-amber-600">Rp {{ number_format($summary['utang']) }}</h3>
    </div>
</div>

{{-- Filters --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        @if($isSuperAdmin)
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Cabang</label>
            <select name="branch_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs">
                <option value="">Semua Cabang</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Periode</label>
            <div class="flex items-center space-x-2">
                <input type="date" name="start_date" value="{{ request('start_date', date('Y-m-01')) }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs">
                <span class="text-gray-300">-</span>
                <input type="date" name="end_date" value="{{ request('end_date', date('Y-m-t')) }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs">
            </div>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Pangkalan</label>
            <select name="pangkalan_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs">
                <option value="">Semua Pangkalan</option>
                @foreach($pangalans as $p)
                <option value="{{ $p->id }}" {{ request('pangkalan_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_pangkalan }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Supir</label>
            <select name="driver_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs">
                <option value="">Semua Supir</option>
                @foreach($drivers as $d)
                <option value="{{ $d->id }}" {{ request('driver_id') == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end space-x-2">
            <button type="submit" class="flex-1 bg-gray-900 text-white font-bold py-2 rounded-lg text-xs hover:bg-black transition">Filter</button>
            <a href="{{ route('report.penjualan.export', request()->all()) }}" class="p-2.5 bg-emerald-100 text-emerald-600 rounded-lg hover:bg-emerald-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </a>
            <button type="button" onclick="window.print()" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2h2"></path></svg>
            </button>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Price Breakdown --}}
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100">
                <h4 class="text-xs font-black text-gray-600 uppercase tracking-widest">Rincian Per Harga</h4>
            </div>
            <table class="w-full text-xs">
                <tbody class="divide-y divide-gray-50">
                    @foreach($priceBreakdown as $pb)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-6 py-4 font-bold text-gray-500 italic">Rp {{ number_format($pb['harga']) }}</td>
                        <td class="px-6 py-4 text-center font-black text-gray-800">{{ number_format($pb['jumlah']) }} Pcs</td>
                        <td class="px-6 py-4 text-right font-black text-emerald-600">Rp {{ number_format($pb['subtotal']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50/80 font-black">
                    <tr>
                        <td class="px-6 py-4 uppercase">Total</td>
                        <td class="px-6 py-4 text-center">{{ number_format($summary['total_tabung']) }} Pcs</td>
                        <td class="px-6 py-4 text-right">Rp {{ number_format($summary['total_omzet']) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Detailed List --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex justify-between items-center">
                <h4 class="text-xs font-black text-gray-600 uppercase tracking-widest">Daftar Transaksi</h4>
                <span class="text-[10px] font-bold text-gray-400">{{ $penjualans->count() }} Transaksi</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Tanggal</th>
                            <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Pangkalan</th>
                            <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase">Jumlah</th>
                            <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-400 uppercase">Total</th>
                            <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase">Metode</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($penjualans as $pj)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4 text-gray-500 font-medium">{{ $pj->tanggal_penjualan->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                <p class="font-bold text-gray-800">{{ $pj->pangkalan->nama_pangkalan }}</p>
                                <p class="text-[10px] text-gray-400">{{ $pj->supir->nama }}</p>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-gray-700">{{ $pj->jumlah_tabung }}</td>
                            <td class="px-6 py-4 text-right font-black text-gray-900">Rp {{ number_format($pj->total_penjualan) }}</td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $met = [
                                        'cash' => 'bg-emerald-100 text-emerald-700',
                                        'transfer' => 'bg-blue-100 text-blue-700',
                                        'utang' => 'bg-amber-100 text-amber-700',
                                        'split' => 'bg-purple-100 text-purple-700',
                                    ];
                                @endphp
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $met[$pj->metode_pembayaran] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $pj->metode_pembayaran }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
