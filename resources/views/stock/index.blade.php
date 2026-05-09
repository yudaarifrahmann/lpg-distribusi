@extends('layouts.admin')
@section('title', 'Stok - LPG Distribution')
@section('page_title', 'Ringkasan Stok')

@section('content')
<div x-data="{ showReturnModal: false, selectedTruckId: '', selectedTruckPolisi: '', selectedTruckStok: '', tab: new URLSearchParams(location.search).get('tab') || 'gudang' }">
    
    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Card Gudang --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-4 bg-blue-50 rounded-2xl text-blue-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Stok Gudang Utama</p>
                <h3 class="text-2xl font-black text-gray-800">{{ number_format($totalStokGudang) }} <span class="text-sm font-medium text-gray-400">Tabung</span></h3>
            </div>
        </div>

        {{-- Card Kendaraan --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-4 bg-indigo-50 rounded-2xl text-indigo-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10v-8a1 1 0 011-1h5.086a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V15a1 1 0 01-1 1h-1.05a2.5 2.5 0 01-4.9 0H11v1h1a1 1 0 110 2h-1a1 1 0 110-2h-3z"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total di Kendaraan</p>
                <h3 class="text-2xl font-black text-gray-800">{{ number_format($totalStokKendaraan) }} <span class="text-sm font-medium text-gray-400">Tabung</span></h3>
            </div>
        </div>

        {{-- Card Total --}}
        <div class="bg-gradient-to-br from-gray-900 to-gray-800 p-6 rounded-3xl shadow-sm border border-gray-800 flex items-center relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <svg class="w-20 h-20 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div class="p-4 bg-white/10 rounded-2xl text-white mr-4 relative z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Keseluruhan</p>
                <h3 class="text-2xl font-black text-white">{{ number_format($totalKeseluruhan) }} <span class="text-sm font-medium text-gray-400">Tabung</span></h3>
            </div>
        </div>
    </div>

    {{-- Stok per Kendaraan Grid --}}
    @if(count($vehicleStocks) > 0)
    <div class="mb-4">
        <h3 class="text-lg font-bold text-gray-800 uppercase tracking-widest">Stok per Armada Aktif</h3>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @foreach($vehicleStocks as $vs)
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-xl hover:shadow-indigo-500/10 transition-all duration-300">
            <div class="p-6 bg-gradient-to-br from-gray-900 to-gray-800 text-white relative">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                </div>
                <div class="relative z-10">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $vs->truck->nama_truk }}</p>
                    <h3 class="text-xl font-black mt-1">{{ $vs->truck->nomor_polisi }}</h3>
                    <div class="mt-4 flex items-baseline">
                        <span class="text-3xl font-black text-indigo-400">{{ number_format($vs->stok_saat_ini) }}</span>
                        <span class="ml-2 text-[10px] text-gray-400 font-bold uppercase tracking-widest">Tabung</span>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 flex items-center justify-between">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $vs->updated_at->diffForHumans() }}</span>
                @if($vs->stok_saat_ini > 0)
                <button @click="selectedTruckId = '{{ $vs->truck_id }}'; selectedTruckPolisi = '{{ $vs->truck->nomor_polisi }}'; selectedTruckStok = '{{ number_format($vs->stok_saat_ini) }}'; showReturnModal = true" type="button" class="px-3 py-1 bg-indigo-500 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-600 transition shadow-sm hover:shadow-indigo-500/30 flex items-center">
                    Kembalikan
                </button>
                @else
                <div class="flex items-center text-[10px] font-bold text-gray-400 uppercase">
                    Kosong
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Tabs Mutasi --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="px-8 border-b border-gray-50 flex items-center gap-8 bg-gray-50/50">
            <button @click="tab = 'gudang'" :class="tab === 'gudang' ? 'border-b-2 border-blue-500 text-blue-600' : 'border-b-2 border-transparent text-gray-400 hover:text-gray-600'" class="py-5 text-xs font-bold uppercase tracking-widest transition">Riwayat Gudang Utama</button>
            <button @click="tab = 'kendaraan'" :class="tab === 'kendaraan' ? 'border-b-2 border-indigo-500 text-indigo-600' : 'border-b-2 border-transparent text-gray-400 hover:text-gray-600'" class="py-5 text-xs font-bold uppercase tracking-widest transition">Riwayat Kendaraan</button>
        </div>
        
        {{-- TAB 1: Gudang --}}
        <div x-show="tab === 'gudang'" x-transition.opacity.duration.300ms>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tanggal</th>
                            <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Transaksi / Referensi</th>
                            <th class="px-8 py-4 text-right text-[10px] font-bold text-gray-400 uppercase tracking-widest">Masuk</th>
                            <th class="px-8 py-4 text-right text-[10px] font-bold text-gray-400 uppercase tracking-widest">Keluar</th>
                            <th class="px-8 py-4 text-right text-[10px] font-bold text-gray-400 uppercase tracking-widest">Sisa Gudang</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($gudangHistories as $h)
                        <tr class="hover:bg-blue-50/20 transition">
                            <td class="px-8 py-4">
                                <p class="text-sm font-bold text-gray-800">{{ $h->tanggal->format('d M Y') }}</p>
                                <p class="text-[10px] font-bold text-gray-400 mt-0.5">{{ $h->tanggal->format('H:i') }}</p>
                            </td>
                            <td class="px-8 py-4">
                                <p class="text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $h->jenis_transaksi ?? 'TIDAK DIKETAHUI' }}</p>
                                <p class="text-[10px] text-gray-500 mt-0.5">{{ $h->keterangan ?? '-' }} | Ref: #{{ $h->referensi }}</p>
                            </td>
                            <td class="px-8 py-4 text-right text-sm font-bold text-emerald-600">{{ $h->stok_masuk > 0 ? '+'.number_format($h->stok_masuk) : '-' }}</td>
                            <td class="px-8 py-4 text-right text-sm font-bold text-red-600">{{ $h->stok_keluar > 0 ? '-'.number_format($h->stok_keluar) : '-' }}</td>
                            <td class="px-8 py-4 text-right">
                                <span class="inline-flex px-3 py-1 bg-gray-100 text-gray-800 text-sm font-black rounded-lg">{{ number_format($h->stok_akhir) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-16 text-center text-gray-400 italic text-sm">Belum ada riwayat mutasi gudang.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($gudangHistories->hasPages())
            <div class="px-8 py-6 border-t border-gray-50">
                {{ $gudangHistories->appends(['tab' => 'gudang', 'vehicle_page' => request('vehicle_page')])->links() }}
            </div>
            @endif
        </div>

        {{-- TAB 2: Kendaraan --}}
        <div x-show="tab === 'kendaraan'" x-transition.opacity.duration.300ms style="display: none;">
            <div class="px-8 py-4 border-b border-gray-50 flex items-center justify-between">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Filter Riwayat</p>
                <form method="GET" class="flex flex-wrap gap-2">
                    <input type="hidden" name="tab" value="kendaraan">
                    
                    {{-- Filter Truk --}}
                    <select name="truck_id" class="px-4 py-2 border border-gray-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-500 transition">
                        <option value="">Semua Truk</option>
                        @foreach($trucks as $t)
                            <option value="{{ $t->id }}" {{ request('truck_id') == $t->id ? 'selected' : '' }}>{{ $t->nomor_polisi }}</option>
                        @endforeach
                    </select>

                    {{-- Filter Supir --}}
                    <select name="driver_id" class="px-4 py-2 border border-gray-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-500 transition">
                        <option value="">Semua Supir</option>
                        @foreach($drivers as $d)
                            <option value="{{ $d->id }}" {{ request('driver_id') == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-xs font-bold rounded-xl hover:bg-gray-700 transition flex items-center gap-2">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                    
                    @if(request('truck_id') || request('driver_id'))
                    <a href="{{ route('stock.index', ['tab' => 'kendaraan']) }}" class="px-4 py-2 bg-gray-100 text-gray-500 text-xs font-bold rounded-xl hover:bg-gray-200 transition">Reset</a>
                    @endif
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tanggal & Truk</th>
                            <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Transaksi / Keterangan</th>
                            <th class="px-8 py-4 text-right text-[10px] font-bold text-gray-400 uppercase tracking-widest">Masuk</th>
                            <th class="px-8 py-4 text-right text-[10px] font-bold text-gray-400 uppercase tracking-widest">Keluar</th>
                            <th class="px-8 py-4 text-right text-[10px] font-bold text-gray-400 uppercase tracking-widest">Sisa Di Truk</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($vehicleHistories as $h)
                        <tr class="hover:bg-indigo-50/20 transition">
                            <td class="px-8 py-4">
                                <p class="text-sm font-bold text-gray-800">{{ $h->tanggal->format('d M Y') }}</p>
                                <p class="text-[10px] font-bold text-indigo-500 mt-0.5 uppercase tracking-widest">{{ $h->truck->nomor_polisi }}</p>
                            </td>
                            <td class="px-8 py-4">
                                <p class="text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $h->jenis_mutasi }}</p>
                                <p class="text-[10px] text-gray-500 mt-0.5">{{ $h->keterangan }} | Ref: #{{ $h->referensi }}</p>
                            </td>
                            <td class="px-8 py-4 text-right text-sm font-bold text-emerald-600">{{ $h->stok_masuk > 0 ? '+'.number_format($h->stok_masuk) : '-' }}</td>
                            <td class="px-8 py-4 text-right text-sm font-bold text-red-600">{{ $h->stok_keluar > 0 ? '-'.number_format($h->stok_keluar) : '-' }}</td>
                            <td class="px-8 py-4 text-right">
                                <span class="inline-flex px-3 py-1 bg-gray-100 text-gray-800 text-sm font-black rounded-lg">{{ number_format($h->stok_akhir) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-16 text-center text-gray-400 italic text-sm">Belum ada riwayat mutasi kendaraan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($vehicleHistories->hasPages())
            <div class="px-8 py-6 border-t border-gray-50">
                {{ $vehicleHistories->appends(['tab' => 'kendaraan', 'gudang_page' => request('gudang_page'), 'truck_id' => request('truck_id'), 'driver_id' => request('driver_id')])->links() }}
            </div>
            @endif
        </div>
    </div>
    
    {{-- Global Custom Modal Konfirmasi Return --}}
    <div x-show="showReturnModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showReturnModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showReturnModal = false" class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showReturnModal" @click.stop x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative z-10 inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-black text-gray-900 uppercase tracking-widest">
                                Konfirmasi Pengembalian
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Anda yakin ingin mengembalikan <span class="font-black text-gray-900" x-text="selectedTruckStok"></span> tabung dari armada <span class="font-black text-gray-900" x-text="selectedTruckPolisi"></span> ke gudang utama?
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                    <form x-bind:action="`/vehicle-stock/${selectedTruckId}/return`" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-black text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-xs uppercase tracking-widest transition">
                            Kembalikan ke Gudang
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
