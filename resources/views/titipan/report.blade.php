@extends('layouts.admin')
@section('title', 'Laporan Mutasi Titipan - LPG Distribution')
@section('page_title', 'Laporan Histori Titipan & Pinjaman')

@section('content')
<div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-8 mb-8">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
        <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Pemilik Tabung</label>
            <input type="text" name="pemilik_tabung" value="{{ request('pemilik_tabung') }}" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition" placeholder="Cari pemilik...">
        </div>
        <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Jenis Mutasi</label>
            <select name="jenis_mutasi" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition">
                <option value="">Semua Mutasi</option>
                <option value="titip" {{ request('jenis_mutasi') == 'titip' ? 'selected' : '' }}>Titip</option>
                <option value="pinjam" {{ request('jenis_mutasi') == 'pinjam' ? 'selected' : '' }}>Pinjam</option>
                <option value="pengembalian" {{ request('jenis_mutasi') == 'pengembalian' ? 'selected' : '' }}>Pengembalian</option>
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Periode</label>
            <div class="flex items-center space-x-2">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition">
                <span class="text-gray-300 font-bold">-</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition">
            </div>
        </div>
        <div class="flex space-x-2">
            <button type="submit" class="flex-1 bg-gray-900 text-white font-black py-2 rounded-xl text-xs uppercase tracking-widest hover:bg-black transition shadow-lg shadow-gray-200">
                Filter Data
            </button>
            <a href="{{ route('titipan.report') }}" class="px-4 py-2 bg-gray-100 text-gray-500 rounded-xl hover:bg-gray-200 transition" title="Reset">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </a>
        </div>
    </form>
</div>

<div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
    <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
        <h4 class="text-sm font-black text-gray-800 uppercase tracking-widest">Histori Mutasi Tabung Titipan</h4>
        <div class="flex space-x-2">
            <a href="{{ route('titipan.export.excel', request()->all()) }}" class="p-2 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-100 transition" title="Export Excel">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </a>
            <button class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition" title="Export PDF">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Tanggal</th>
                    <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Jenis</th>
                    <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Pemilik</th>
                    <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Peminjam</th>
                    <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Jumlah</th>
                    <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Stok Akhir</th>
                    <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($mutations as $m)
                <tr class="hover:bg-gray-50/50 transition duration-300">
                    <td class="px-8 py-5 text-xs font-bold text-gray-600">
                        {{ $m->tanggal->format('d/m/Y') }}
                    </td>
                    <td class="px-8 py-5">
                        @php
                            $colors = [
                                'titip' => 'bg-emerald-100 text-emerald-700',
                                'pinjam' => 'bg-amber-100 text-amber-700',
                                'pengembalian' => 'bg-blue-100 text-blue-700',
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $colors[$m->jenis_mutasi] ?? 'bg-gray-100' }}">
                            {{ $m->jenis_mutasi }}
                        </span>
                    </td>
                    <td class="px-8 py-5 text-xs font-black text-gray-800">{{ $m->pemilik_tabung }}</td>
                    <td class="px-8 py-5 text-xs font-bold text-gray-500">{{ $m->peminjam ?? '-' }}</td>
                    <td class="px-8 py-5 text-center font-black text-gray-800">{{ number_format($m->jumlah) }}</td>
                    <td class="px-8 py-5 text-right font-black text-gray-900">{{ number_format($m->stok_sesudah) }}</td>
                    <td class="px-8 py-5 text-[10px] text-gray-400 font-medium">{{ $m->keterangan ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-8 py-20 text-center text-gray-400 italic">Data mutasi tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($mutations->hasPages())
    <div class="px-8 py-6 border-t border-gray-50">
        {{ $mutations->links() }}
    </div>
    @endif
</div>
@endsection
