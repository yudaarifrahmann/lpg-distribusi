@extends('layouts.admin')
@section('title', 'Laporan Stok & Retur - LPG Distribution')
@section('page_title', 'Laporan Rekap Inventaris')

@section('content')
{{-- Filters --}}
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 mb-8 print:hidden">
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
        <div class="w-full md:w-auto md:min-w-[200px]">
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Jenis Mutasi</label>
            <select name="jenis_mutasi" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs">
                <option value="">Semua Mutasi</option>
                <option value="penjualan" {{ request('jenis_mutasi') == 'penjualan' ? 'selected' : '' }}>Penjualan</option>
                <option value="penebusan_do" {{ request('jenis_mutasi') == 'penebusan_do' ? 'selected' : '' }}>Penebusan DO</option>
                <option value="retur_gudang" {{ request('jenis_mutasi') == 'retur_gudang' ? 'selected' : '' }}>Retur Gudang</option>
                <option value="penyesuaian_stok" {{ request('jenis_mutasi') == 'penyesuaian_stok' ? 'selected' : '' }}>Penyesuaian Stok</option>
            </select>
        </div>
        <button type="submit" class="bg-gray-900 text-white font-bold px-8 py-2 rounded-lg text-xs hover:bg-black transition">Filter</button>
    </form>
</div>

<div class="mb-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
    {{-- Retur Summary --}}
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <h4 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-6">Rekap Retur (Terverifikasi)</h4>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-400 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-400 uppercase">Truk</th>
                        <th class="px-4 py-3 text-center text-[10px] font-bold text-gray-400 uppercase">Kondisi</th>
                        <th class="px-4 py-3 text-right text-[10px] font-bold text-gray-400 uppercase">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($returs->take(10) as $rt)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-4 py-3 text-gray-500">{{ $rt->tanggal_retur->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 font-bold text-gray-800">{{ $rt->truck->nomor_polisi }}</td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $cond = [
                                    'baik' => 'text-emerald-600',
                                    'rusak' => 'text-red-600',
                                    'bocor' => 'text-amber-600',
                                ];
                            @endphp
                            <span class="font-bold uppercase {{ $cond[$rt->kondisi_tabung] }}">{{ $rt->kondisi_tabung }}</span>
                        </td>
                        <td class="px-4 py-3 text-right font-black">{{ $rt->jumlah_retur }} Pcs</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mutation Summary --}}
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <h4 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-6">Aktivitas Mutasi Terkini</h4>
        <div class="space-y-4">
            @foreach($mutations->take(6) as $mut)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-800 uppercase tracking-tighter">{{ str_replace('_', ' ', $mut->jenis_mutasi) }}</p>
                        <p class="text-[10px] text-gray-400">{{ $mut->lokasi_asal }} → {{ $mut->lokasi_tujuan }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs font-black {{ $mut->stok_masuk > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                        {{ $mut->stok_masuk > 0 ? '+' : '-' }}{{ $mut->stok_masuk > 0 ? $mut->stok_masuk : $mut->stok_keluar }}
                    </p>
                    <p class="text-[9px] text-gray-400">{{ $mut->tanggal->diffForHumans() }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-8 py-6 bg-gray-50/50 border-b border-gray-100 flex justify-between items-center">
        <h4 class="text-xs font-black text-gray-600 uppercase tracking-widest">Detail Seluruh Mutasi Stok</h4>
        <button onclick="window.print()" class="text-xs font-bold text-blue-600 hover:underline">Print Laporan</button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-xs divide-y divide-gray-100">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Waktu</th>
                    <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Jenis Mutasi</th>
                    <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Keterangan / Referensi</th>
                    <th class="px-8 py-4 text-center text-[10px] font-bold text-gray-400 uppercase">Masuk</th>
                    <th class="px-8 py-4 text-center text-[10px] font-bold text-gray-400 uppercase">Keluar</th>
                    <th class="px-8 py-4 text-right text-[10px] font-bold text-gray-400 uppercase">Saldo Akhir</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($mutations as $mut)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-8 py-4 text-gray-500">{{ $mut->tanggal->format('d/m/Y H:i') }}</td>
                    <td class="px-8 py-4 font-bold text-gray-700 uppercase">{{ str_replace('_', ' ', $mut->jenis_mutasi) }}</td>
                    <td class="px-8 py-4">
                        <p class="font-medium text-gray-800">{{ $mut->referensi }}</p>
                        <p class="text-[10px] text-gray-400">{{ $mut->lokasi_asal }} ke {{ $mut->lokasi_tujuan }}</p>
                    </td>
                    <td class="px-8 py-4 text-center font-bold text-emerald-600">{{ $mut->stok_masuk ?: '-' }}</td>
                    <td class="px-8 py-4 text-center font-bold text-red-600">{{ $mut->stok_keluar ?: '-' }}</td>
                    <td class="px-8 py-4 text-right font-black text-gray-900">{{ $mut->stok_akhir }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
