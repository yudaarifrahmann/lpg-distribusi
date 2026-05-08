@extends('layouts.admin')
@section('title', 'Input Retur - LPG Distribution')
@section('page_title', 'Input Retur Tabung ke Gudang')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('retur.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition font-bold">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('retur.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tanggal Retur <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_retur" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kondisi Tabung <span class="text-red-500">*</span></label>
                    <select name="kondisi_tabung" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                        <option value="baik">Baik / Layak Jual</option>
                        <option value="rusak">Rusak / Bocor</option>
                        <option value="bocor">Bocor Halus</option>
                    </select>
                </div>
            </div>

            <div x-data="{ selectedSj: '' }">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Pilih Surat Jalan <span class="text-red-500">*</span></label>
                <select name="surat_jalan_id" x-model="selectedSj" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                    <option value="">-- Pilih SJ Aktif --</option>
                    @foreach($suratJalans as $sj)
                    <option value="{{ $sj->id }}">{{ $sj->nomor_surat_jalan }} ({{ $sj->truck->nomor_polisi }} - {{ $sj->supir->nama }})</option>
                    @endforeach
                </select>
                <p class="mt-2 text-[10px] text-gray-400 italic">Hanya menampilkan Surat Jalan yang belum berstatus 'Selesai'.</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jumlah Retur (Pcs) <span class="text-red-500">*</span></label>
                <input type="number" name="jumlah_retur" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-lg font-black text-emerald-600 focus:ring-2 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Keterangan / Alasan Retur</label>
                <textarea name="keterangan" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500" placeholder="Contoh: Tabung sisa penjualan rute hari ini."></textarea>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-4 bg-gray-900 text-white font-bold rounded-2xl shadow-xl hover:bg-black transition">
                    AJUKAN RETUR GUDANG
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
