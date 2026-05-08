@extends('layouts.admin')
@section('title', 'Stok Kendaraan - LPG Distribution')
@section('page_title', 'Stok Tabung di Kendaraan')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    @forelse($vehicleStocks as $vs)
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-xl hover:shadow-indigo-500/10 transition-all duration-300">
        <div class="p-6 bg-gradient-to-br from-gray-900 to-gray-800 text-white relative">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
            </div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $vs->truck->nama_truk }}</p>
                <h3 class="text-2xl font-black mt-1">{{ $vs->truck->nomor_polisi }}</h3>
                <div class="mt-6 flex items-baseline">
                    <span class="text-4xl font-black text-indigo-400">{{ number_format($vs->stok_saat_ini) }}</span>
                    <span class="ml-2 text-sm text-gray-400 font-medium">Tabung</span>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 bg-gray-50 flex items-center justify-between">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Update: {{ $vs->updated_at->diffForHumans() }}</span>
            <div class="flex items-center text-xs font-bold text-emerald-500">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2"></span>AKTIF
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-20 text-center bg-white rounded-3xl border border-gray-100 italic text-gray-400">
        Belum ada kendaraan yang memiliki stok aktif.
    </div>
    @endforelse
</div>

{{-- Mutasi Stok Kendaraan --}}
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-8 py-6 border-b border-gray-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-gray-800 uppercase tracking-widest">Histori Mutasi Stok Kendaraan</h3>
            <p class="text-xs text-gray-400 mt-1 uppercase tracking-tighter">Riwayat pergerakan tabung di setiap armada</p>
        </div>
        <form method="GET" class="flex gap-2">
            <select name="truck_id" class="px-4 py-2 border border-gray-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-500 transition">
                <option value="">Semua Truk</option>
                @foreach($trucks as $t)
                    <option value="{{ $t->id }}" {{ request('truck_id') == $t->id ? 'selected' : '' }}>{{ $t->nomor_polisi }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-xs font-bold rounded-xl hover:bg-gray-700 transition">Filter</button>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tanggal & Truk</th>
                    <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Transaksi</th>
                    <th class="px-8 py-4 text-right text-[10px] font-bold text-gray-400 uppercase tracking-widest">Masuk</th>
                    <th class="px-8 py-4 text-right text-[10px] font-bold text-gray-400 uppercase tracking-widest">Keluar</th>
                    <th class="px-8 py-4 text-right text-[10px] font-bold text-gray-400 uppercase tracking-widest">Stok Akhir</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($histories as $h)
                <tr class="hover:bg-indigo-50/20 transition">
                    <td class="px-8 py-4">
                        <p class="text-sm font-bold text-gray-800">{{ $h->tanggal->format('d M Y') }}</p>
                        <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest">{{ $h->truck->nomor_polisi }}</p>
                    </td>
                    <td class="px-8 py-4">
                        <p class="text-xs text-gray-700 font-medium">{{ $h->keterangan }}</p>
                        <p class="text-[10px] text-gray-400 uppercase tracking-tighter">Ref: #{{ $h->referensi }}</p>
                    </td>
                    <td class="px-8 py-4 text-right text-sm font-bold text-emerald-600">{{ $h->stok_masuk > 0 ? '+'.number_format($h->stok_masuk) : '-' }}</td>
                    <td class="px-8 py-4 text-right text-sm font-bold text-red-600">{{ $h->stok_keluar > 0 ? '-'.number_format($h->stok_keluar) : '-' }}</td>
                    <td class="px-8 py-4 text-right">
                        <span class="inline-flex px-3 py-1 bg-gray-100 text-gray-800 text-sm font-black rounded-lg">{{ number_format($h->stok_akhir) }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-16 text-center text-gray-400 italic text-sm">Belum ada mutasi stok kendaraan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($histories->hasPages())
    <div class="px-8 py-6 border-t border-gray-50">
        {{ $histories->links() }}
    </div>
    @endif
</div>
@endsection
