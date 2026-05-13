@extends('layouts.admin')
@section('title', 'Titipan & Pinjam Tabung - LPG Distribution')
@section('page_title', 'Manajemen Titipan & Pinjam Tabung')

@section('content')
{{-- Summary Cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 flex flex-col justify-between group hover:bg-emerald-600 transition-all duration-500 cursor-default">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-emerald-50 rounded-2xl group-hover:bg-emerald-500 transition-colors">
                <svg class="w-6 h-6 text-emerald-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <span class="text-[10px] font-black text-emerald-600 group-hover:text-emerald-100 uppercase tracking-widest bg-emerald-50 group-hover:bg-emerald-500/50 px-2.5 py-1 rounded-full">Tersedia</span>
        </div>
        <div>
            <h3 class="text-3xl font-black text-gray-900 group-hover:text-white mb-1">{{ number_format($summary['total_tersedia']) }}</h3>
            <p class="text-xs font-bold text-gray-400 group-hover:text-emerald-100 uppercase tracking-tight">Tabung Siap Dipinjam</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 flex flex-col justify-between group hover:bg-amber-500 transition-all duration-500 cursor-default">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-amber-50 rounded-2xl group-hover:bg-amber-400 transition-colors">
                <svg class="w-6 h-6 text-amber-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            </div>
            <span class="text-[10px] font-black text-amber-600 group-hover:text-amber-100 uppercase tracking-widest bg-amber-50 group-hover:bg-amber-400/50 px-2.5 py-1 rounded-full">Dipinjam</span>
        </div>
        <div>
            <h3 class="text-3xl font-black text-gray-900 group-hover:text-white mb-1">{{ number_format($summary['total_dipinjam']) }}</h3>
            <p class="text-xs font-bold text-gray-400 group-hover:text-amber-100 uppercase tracking-tight">Tabung Di Luar</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 flex flex-col justify-between group hover:bg-blue-600 transition-all duration-500 cursor-default">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-blue-50 rounded-2xl group-hover:bg-blue-500 transition-colors">
                <svg class="w-6 h-6 text-blue-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <span class="text-[10px] font-black text-blue-600 group-hover:text-blue-100 uppercase tracking-widest bg-blue-50 group-hover:bg-blue-500/50 px-2.5 py-1 rounded-full">Total</span>
        </div>
        <div>
            <h3 class="text-3xl font-black text-gray-900 group-hover:text-white mb-1">{{ number_format($summary['total_tabung']) }}</h3>
            <p class="text-xs font-bold text-gray-400 group-hover:text-blue-100 uppercase tracking-tight">Total Tabung Titipan</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 flex flex-col justify-between group hover:bg-indigo-600 transition-all duration-500 cursor-default">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-indigo-50 rounded-2xl group-hover:bg-indigo-500 transition-colors">
                <svg class="w-6 h-6 text-indigo-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <span class="text-[10px] font-black text-indigo-600 group-hover:text-indigo-100 uppercase tracking-widest bg-indigo-50 group-hover:bg-indigo-500/50 px-2.5 py-1 rounded-full">Pemilik</span>
        </div>
        <div>
            <h3 class="text-3xl font-black text-gray-900 group-hover:text-white mb-1">{{ number_format($summary['total_pemilik']) }}</h3>
            <p class="text-xs font-bold text-gray-400 group-hover:text-indigo-100 uppercase tracking-tight">Jumlah Pihak Menitip</p>
        </div>
    </div>
</div>

{{-- Action Buttons --}}
<div class="flex flex-wrap gap-4 mb-8">
    <button @click="$dispatch('open-modal', 'modal-titip')" class="px-6 py-3 bg-emerald-600 text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-emerald-700 transition shadow-lg shadow-emerald-200">
        + Titip Tabung
    </button>
    <button @click="$dispatch('open-modal', 'modal-pinjam')" class="px-6 py-3 bg-amber-500 text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-amber-600 transition shadow-lg shadow-amber-200">
        + Pinjam Tabung
    </button>
    <button @click="$dispatch('open-modal', 'modal-kembali')" class="px-6 py-3 bg-blue-600 text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-blue-700 transition shadow-lg shadow-blue-200">
        + Pengembalian
    </button>
    <a href="{{ route('titipan.report') }}" class="px-6 py-3 bg-gray-900 text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-black transition ml-auto">
        Lihat Laporan
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Stocks Table --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center">
                <h4 class="text-sm font-black text-gray-800 uppercase tracking-widest">Stok Tabung Per Pemilik</h4>
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></span>
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Live Update</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Pemilik Tabung</th>
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Tersedia</th>
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Dipinjam</th>
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center text-blue-600">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($stocks as $stock)
                        <tr class="hover:bg-gray-50/50 transition duration-300">
                            <td class="px-8 py-5">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center font-black text-gray-400 text-sm">
                                        {{ substr($stock->pemilik_tabung, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-gray-800">{{ $stock->pemilik_tabung }}</p>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">ID: DEPOSIT-{{ $stock->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="text-sm font-black text-emerald-600">{{ number_format($stock->jumlah_tersedia) }}</span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="text-sm font-black text-amber-500">{{ number_format($stock->jumlah_dipinjam) }}</span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm font-black">{{ number_format($stock->total_tabung) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center text-gray-400 italic">Belum ada data titipan tabung.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Recent Mutations --}}
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-gray-900 rounded-3xl shadow-2xl p-8 text-white relative overflow-hidden">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl"></div>
            
            <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-8 flex items-center">
                <svg class="w-4 h-4 mr-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Mutasi Terakhir
            </h4>

            <div class="space-y-6 relative z-10">
                @foreach($recentMutations as $m)
                <div class="flex items-start space-x-4 group">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-transform group-hover:scale-110
                            @if($m->jenis_mutasi == 'titip') bg-emerald-500/20 text-emerald-400 
                            @elseif($m->jenis_mutasi == 'pinjam') bg-amber-500/20 text-amber-400 
                            @else bg-blue-500/20 text-blue-400 @endif">
                            @if($m->jenis_mutasi == 'titip') <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                            @elseif($m->jenis_mutasi == 'pinjam') <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 12H4"/></svg>
                            @else <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> @endif
                        </div>
                        @if(!$loop->last)
                        <div class="absolute top-10 left-1/2 -translate-x-1/2 w-0.5 h-6 bg-gray-800"></div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <h5 class="text-sm font-black text-gray-200 capitalize tracking-tight">{{ $m->jenis_mutasi }}</h5>
                            <span class="text-[9px] font-bold text-gray-500 uppercase">{{ $m->tanggal->format('d M') }}</span>
                        </div>
                        <p class="text-[11px] text-gray-400 leading-tight mt-1">
                            <span class="text-gray-200 font-bold">{{ $m->jumlah }} Pcs</span> dari <span class="text-emerald-400">{{ $m->pemilik_tabung }}</span>
                        </p>
                    </div>
                </div>
                @endforeach
            </div>

            <a href="{{ route('titipan.report') }}" class="block w-full text-center mt-10 text-[10px] font-black text-emerald-400 uppercase tracking-widest hover:text-emerald-300 transition">
                Lihat Semua Histori →
            </a>
        </div>
    </div>
</div>

{{-- Modals --}}
{{-- Modal Titip --}}
<x-modal name="modal-titip" focusable>
    <div class="p-8">
        <h2 class="text-xl font-black text-gray-900 uppercase tracking-widest mb-6">Titip Tabung</h2>
        <form action="{{ route('titipan.deposit') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Pemilik Tabung</label>
                <input type="text" name="pemilik_tabung" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 transition" placeholder="Contoh: PT. Maju Jaya">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Jumlah</label>
                    <input type="number" name="jumlah" required min="1" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 transition" placeholder="0">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 transition">
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Keterangan (Opsional)</label>
                <textarea name="keterangan" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 transition"></textarea>
            </div>
            <div class="pt-4 flex justify-end space-x-3">
                <button type="button" @click="$dispatch('close')" class="px-6 py-3 text-xs font-black text-gray-400 uppercase tracking-widest">Batal</button>
                <button type="submit" class="px-8 py-3 bg-emerald-600 text-white rounded-xl font-black uppercase tracking-widest text-xs hover:bg-emerald-700 transition">Simpan Titipan</button>
            </div>
        </form>
    </div>
</x-modal>

{{-- Modal Pinjam --}}
<x-modal name="modal-pinjam" focusable>
    <div class="p-8">
        <h2 class="text-xl font-black text-gray-900 uppercase tracking-widest mb-6 text-amber-500">Pinjam Tabung</h2>
        <form action="{{ route('titipan.loan') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Pemilik Tabung (Sumber)</label>
                <select name="pemilik_tabung" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 transition">
                    <option value="">Pilih Pemilik</option>
                    @foreach($stocks->where('jumlah_tersedia', '>', 0) as $s)
                        <option value="{{ $s->pemilik_tabung }}">{{ $s->pemilik_tabung }} (Tersedia: {{ $s->jumlah_tersedia }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Peminjam</label>
                <input type="text" name="peminjam" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 transition" placeholder="Nama Pangkalan atau Supir">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Jumlah Pinjam</label>
                    <input type="number" name="jumlah" required min="1" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 transition" placeholder="0">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 transition">
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Keterangan (Opsional)</label>
                <textarea name="keterangan" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 transition"></textarea>
            </div>
            <div class="pt-4 flex justify-end space-x-3">
                <button type="button" @click="$dispatch('close')" class="px-6 py-3 text-xs font-black text-gray-400 uppercase tracking-widest">Batal</button>
                <button type="submit" class="px-8 py-3 bg-amber-500 text-white rounded-xl font-black uppercase tracking-widest text-xs hover:bg-amber-600 transition">Catat Pinjaman</button>
            </div>
        </form>
    </div>
</x-modal>

{{-- Modal Kembali --}}
<x-modal name="modal-kembali" focusable>
    <div class="p-8">
        <h2 class="text-xl font-black text-gray-900 uppercase tracking-widest mb-6 text-blue-600">Pengembalian Tabung</h2>
        <form action="{{ route('titipan.return') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Pemilik Tabung (Tujuan)</label>
                <select name="pemilik_tabung" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition">
                    <option value="">Pilih Pemilik</option>
                    @foreach($stocks->where('jumlah_dipinjam', '>', 0) as $s)
                        <option value="{{ $s->pemilik_tabung }}">{{ $s->pemilik_tabung }} (Sedang Dipinjam: {{ $s->jumlah_dipinjam }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Peminjam (Yang Mengembalikan)</label>
                <input type="text" name="peminjam" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition" placeholder="Opsional">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Jumlah Kembali</label>
                    <input type="number" name="jumlah" required min="1" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition" placeholder="0">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition">
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Keterangan (Opsional)</label>
                <textarea name="keterangan" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition"></textarea>
            </div>
            <div class="pt-4 flex justify-end space-x-3">
                <button type="button" @click="$dispatch('close')" class="px-6 py-3 text-xs font-black text-gray-400 uppercase tracking-widest">Batal</button>
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-xl font-black uppercase tracking-widest text-xs hover:bg-blue-700 transition">Simpan Pengembalian</button>
            </div>
        </form>
    </div>
</x-modal>
@endsection
