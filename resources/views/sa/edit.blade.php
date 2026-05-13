@extends('layouts.admin')
@section('title', 'Edit SA - LPG Distribution')
@section('page_title', 'Edit Schedule Agreement')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('schedule-agreement.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
            Kembali ke Daftar SA
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800">Edit Schedule Agreement</h3>
        </div>
        <form action="{{ route('schedule-agreement.update', $scheduleAgreement->id) }}" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal SA <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_sa" value="{{ old('tanggal_sa', $scheduleAgreement->tanggal_sa->format('Y-m-d')) }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
                @error('tanggal_sa')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Supir <span class="text-red-500">*</span></label>
                    <select name="driver_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
                        <option value="">Pilih Supir</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}" {{ old('driver_id', $scheduleAgreement->driver_id) == $driver->id ? 'selected' : '' }}>{{ $driver->nama }}</option>
                        @endforeach
                    </select>
                    @error('driver_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Truk <span class="text-red-500">*</span></label>
                    <select name="truck_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
                        <option value="">Pilih Truk</option>
                        @foreach($trucks as $truck)
                            <option value="{{ $truck->id }}" {{ old('truck_id', $scheduleAgreement->truck_id) == $truck->id ? 'selected' : '' }}>{{ $truck->nomor_polisi }} - {{ $truck->nama_truk }}</option>
                        @endforeach
                    </select>
                    @error('truck_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah DO <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_do" value="{{ old('jumlah_do', $scheduleAgreement->jumlah_do) }}" min="1" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
                    @error('jumlah_do')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status SA <span class="text-red-500">*</span></label>
                    <select name="status_sa" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
                        <option value="pending" {{ old('status_sa', $scheduleAgreement->status_sa) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="selesai" {{ old('status_sa', $scheduleAgreement->status_sa) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('status_sa')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Tabung <span class="text-red-500">*</span></label>
                <input type="number" name="jumlah_tabung" value="{{ old('jumlah_tabung', $scheduleAgreement->jumlah_tabung) }}" min="1" required placeholder="Masukkan jumlah tabung"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
                @error('jumlah_tabung')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea name="keterangan" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">{{ old('keterangan', $scheduleAgreement->keterangan) }}</textarea>
            </div>

            <div class="pt-4 flex items-center justify-end">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition duration-200">
                    Update SA
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
