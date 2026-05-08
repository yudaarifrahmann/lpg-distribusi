@extends('layouts.admin')
@section('title', 'Buat SA Baru - LPG Distribution')
@section('page_title', 'Buat Schedule Agreement')

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
            <h3 class="text-lg font-bold text-gray-800">Form Schedule Agreement</h3>
        </div>
        <form action="{{ route('schedule-agreement.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal SA <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_sa" value="{{ old('tanggal_sa', date('Y-m-d')) }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
                @error('tanggal_sa')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah DO <span class="text-red-500">*</span></label>
                <input type="number" name="jumlah_do" value="{{ old('jumlah_do', 1) }}" min="1" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
                @error('jumlah_do')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Tabung <span class="text-red-500">*</span></label>
                <input type="number" name="jumlah_tabung" value="{{ old('jumlah_tabung') }}" min="1" required placeholder="Masukkan jumlah tabung"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
                @error('jumlah_tabung')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea name="keterangan" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">{{ old('keterangan') }}</textarea>
            </div>

            <div class="pt-4 flex items-center justify-end">
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition duration-200">
                    Simpan SA
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
