@extends('layouts.admin')
@section('title', 'Buat SJ Baru - LPG Distribution')
@section('page_title', 'Buat Surat Jalan')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('surat-jalan.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800">Form Surat Jalan</h3>
            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full uppercase tracking-wider">Distribusi Stok</span>
        </div>
        <form action="{{ route('surat-jalan.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Surat Jalan <span class="text-red-500">*</span></label>
                    <input type="text" name="nomor_surat_jalan" value="{{ old('nomor_surat_jalan', 'SJ-'.date('YmdHis')) }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
                    @error('nomor_surat_jalan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Berangkat <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_berangkat" value="{{ old('tanggal_berangkat', date('Y-m-d')) }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
                    @error('tanggal_berangkat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Referensi Penebusan (DO) <span class="text-red-500">*</span></label>
                    <select name="penebusan_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
                        <option value="">-- Pilih DO --</option>
                        @foreach($penebusans as $p)
                            <option value="{{ $p->id }}" {{ old('penebusan_id') == $p->id ? 'selected' : '' }}>
                                #{{ $p->nomor_do }} ({{ $p->tanggal_penebusan->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>
                    @error('penebusan_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Tabung <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_tabung" value="{{ old('jumlah_tabung', 560) }}" required min="1" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
                    <p class="text-[10px] text-gray-400 mt-1 italic">* Pastikan stok gudang mencukupi</p>
                    @error('jumlah_tabung')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100">
                <h4 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wider">Personil & Armada</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Truck Armada <span class="text-red-500">*</span></label>
                        <select name="truck_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">-- Pilih Truck --</option>
                            @foreach($trucks as $truck)
                                <option value="{{ $truck->id }}" {{ old('truck_id') == $truck->id ? 'selected' : '' }}>
                                    {{ $truck->nomor_polisi }} ({{ $truck->nama_truk }})
                                </option>
                            @endforeach
                        </select>
                        @error('truck_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Supir Utama <span class="text-red-500">*</span></label>
                        <select name="driver_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">-- Pilih Supir --</option>
                            @foreach($supirs as $supir)
                                <option value="{{ $supir->id }}" {{ old('driver_id') == $supir->id ? 'selected' : '' }}>{{ $supir->nama }}</option>
                            @endforeach
                        </select>
                        @error('driver_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Knek (Opsional)</label>
                        <select name="knek_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">-- Pilih Knek --</option>
                            @foreach($kneks as $knek)
                                <option value="{{ $knek->id }}" {{ old('knek_id') == $knek->id ? 'selected' : '' }}>{{ $knek->nama }}</option>
                            @endforeach
                        </select>
                        @error('knek_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Perjalanan</label>
                <textarea name="catatan" rows="3" placeholder="Contoh: Rute pengiriman wilayah Utara..." class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">{{ old('catatan') }}</textarea>
            </div>

            <div class="pt-4 flex items-center justify-end">
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-indigo-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition duration-200 hover:-translate-y-0.5">
                    Konfirmasi & Berangkatkan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
