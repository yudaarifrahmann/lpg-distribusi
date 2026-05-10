@extends('layouts.admin')
@section('title', 'Mutasi Stok - LPG Distribution')
@section('page_title', 'Histori Pergerakan Tabung')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" action="{{ route('stock-history.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
        <select name="jenis_mutasi" class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
            <option value="">Semua Jenis Mutasi</option>
            <option value="penebusan" {{ request('jenis_mutasi') == 'penebusan' ? 'selected' : '' }}>Penebusan</option>
            <option value="distribusi_ke_truk" {{ request('jenis_mutasi') == 'distribusi_ke_truk' ? 'selected' : '' }}>Distribusi ke Truk</option>
            <option value="penjualan" {{ request('jenis_mutasi') == 'penjualan' ? 'selected' : '' }}>Penjualan</option>
            <option value="retur_gudang" {{ request('jenis_mutasi') == 'retur_gudang' ? 'selected' : '' }}>Retur Gudang</option>
            <option value="penyesuaian_stok" {{ request('jenis_mutasi') == 'penyesuaian_stok' ? 'selected' : '' }}>Penyesuaian Stok</option>
        </select>
        <div class="flex items-center space-x-2 sm:col-span-2">
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="flex-1 px-4 py-2 border border-gray-200 rounded-xl text-sm">
            <span class="text-gray-400">s/d</span>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="flex-1 px-4 py-2 border border-gray-200 rounded-xl text-sm">
        </div>
        <button type="submit" class="bg-gray-800 text-white font-bold rounded-xl text-sm hover:bg-gray-700 transition">Filter Histori</button>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
    <div class="flow-root">
        <ul role="list" class="-mb-8">
            @forelse($mutations as $mut)
            <li>
                <div class="relative pb-8">
                    @if (!$loop->last)
                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                    @endif
                    <div class="relative flex space-x-3">
                        <div>
                            @php
                                $icons = [
                                    'penebusan' => ['bg-blue-500', 'M12 4v16m8-8H4'],
                                    'distribusi_ke_truk' => ['bg-amber-500', 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z'],
                                    'penjualan' => ['bg-emerald-500', 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2'],
                                    'retur_gudang' => ['bg-red-500', 'M11 15l-3-3m0 0l3-3m-3 3h8'],
                                    'penyesuaian_stok' => ['bg-gray-600', 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                                ];
                                $cfg = $icons[$mut->jenis_mutasi] ?? ['bg-gray-400', 'M12 4v16m8-8H4'];
                            @endphp
                            <span class="h-8 w-8 rounded-full {{ $cfg[0] }} flex items-center justify-center ring-8 ring-white">
                                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $cfg[1] }}"/></svg>
                            </span>
                        </div>
                        <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                            <div>
                                <p class="text-sm text-gray-500">
                                    <span class="font-bold text-gray-900 uppercase">{{ str_replace('_', ' ', $mut->jenis_mutasi) }}</span> 
                                    <span class="mx-1">•</span> 
                                    <span class="font-medium text-gray-700">{{ $mut->referensi }}</span>
                                </p>
                                <div class="mt-1 flex items-center text-xs text-gray-400">
                                    <span class="font-bold text-gray-500">{{ $mut->lokasi_asal }}</span>
                                    <svg class="w-3 h-3 mx-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    <span class="font-bold text-gray-500">{{ $mut->lokasi_tujuan }}</span>
                                </div>
                            </div>
                            <div class="text-right whitespace-nowrap">
                                <div class="flex flex-col items-end">
                                    @if($mut->stok_masuk > 0)
                                    <span class="text-sm font-black text-emerald-600">+{{ $mut->stok_masuk }}</span>
                                    @endif
                                    @if($mut->stok_keluar > 0)
                                    <span class="text-sm font-black text-red-600">-{{ $mut->stok_keluar }}</span>
                                    @endif
                                    <span class="text-[10px] font-bold text-gray-400">Stok Akhir: {{ $mut->stok_akhir }}</span>
                                </div>
                                <time class="block text-[10px] text-gray-400 mt-1">{{ \Carbon\Carbon::parse($mut->tanggal)->format('d/m/Y H:i') }}</time>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            @empty
            <li class="py-12 text-center text-gray-400 italic">Belum ada histori pergerakan stok.</li>
            @endforelse
        </ul>
    </div>
    <div class="mt-12 border-t border-gray-100 pt-6">
        {{ $mutations->links() }}
    </div>
</div>
@endsection
