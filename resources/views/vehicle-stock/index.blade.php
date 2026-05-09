@extends('layouts.admin')
@section('title', 'Stok Kendaraan - LPG Distribution')
@section('page_title', 'Stok Tabung di Kendaraan')

@section('content')
<div x-data="{ showReturnModal: false, selectedTruckId: '', selectedTruckPolisi: '', selectedTruckStok: '' }">
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
                @if($vs->stok_saat_ini > 0)
                <button @click="selectedTruckId = '{{ $vs->truck_id }}'; selectedTruckPolisi = '{{ $vs->truck->nomor_polisi }}'; selectedTruckStok = '{{ number_format($vs->stok_saat_ini) }}'; showReturnModal = true" type="button" class="px-3 py-1.5 bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-blue-700 transition shadow-sm hover:shadow-blue-500/30 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                    Kembalikan
                </button>
                @else
                <div class="flex items-center text-xs font-bold text-gray-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400 mr-2"></span>KOSONG
                </div>
                @endif
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

    {{-- Global Custom Modal Konfirmasi --}}
    <div x-show="showReturnModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showReturnModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showReturnModal = false" class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showReturnModal" @click.stop x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative z-10 inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-black text-gray-900 uppercase tracking-widest">
                                Konfirmasi Pengembalian
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Anda yakin ingin mengembalikan <span class="font-black text-gray-900" x-text="selectedTruckStok"></span> tabung dari truk <span class="font-black text-gray-900" x-text="selectedTruckPolisi"></span> ke gudang?
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                    <form x-bind:action="`/vehicle-stock/${selectedTruckId}/return`" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-black text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-xs uppercase tracking-widest transition">
                            Ya, Kembalikan
                        </button>
                    </form>
                    <button @click="showReturnModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-black text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-xs uppercase tracking-widest transition">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
