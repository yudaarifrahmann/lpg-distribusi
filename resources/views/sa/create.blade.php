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

            <div x-data="{ doCount: 1 }">
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah DO <span class="text-red-500">*</span></label>
                <input type="number" name="jumlah_do" x-model="doCount" value="{{ old('jumlah_do', 1) }}" min="1" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
                <p class="text-xs text-gray-500 mt-1.5 flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    Total tabung otomatis: <span class="font-bold text-gray-700 ml-1" x-text="doCount * 560"></span> tabung
                </p>
                @error('jumlah_do')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
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
