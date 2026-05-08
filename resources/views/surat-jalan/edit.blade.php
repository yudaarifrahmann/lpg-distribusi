@extends('layouts.admin')
@section('title', 'Update Status SJ - LPG Distribution')
@section('page_title', 'Update Status Perjalanan')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('surat-jalan.show', $suratJalan) }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
            Kembali ke Detail
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800">SJ: {{ $suratJalan->nomor_surat_jalan }}</h3>
        </div>
        <form action="{{ route('surat-jalan.update', $suratJalan) }}" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status Perjalanan <span class="text-red-500">*</span></label>
                <select name="status_perjalanan" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
                    <option value="persiapan" {{ old('status_perjalanan', $suratJalan->status_perjalanan) == 'persiapan' ? 'selected' : '' }}>Persiapan</option>
                    <option value="berangkat" {{ old('status_perjalanan', $suratJalan->status_perjalanan) == 'berangkat' ? 'selected' : '' }}>Berangkat</option>
                    <option value="selesai" {{ old('status_perjalanan', $suratJalan->status_perjalanan) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="retur" {{ old('status_perjalanan', $suratJalan->status_perjalanan) == 'retur' ? 'selected' : '' }}>Retur</option>
                </select>
                @error('status_perjalanan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan</label>
                <textarea name="catatan" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">{{ old('catatan', $suratJalan->catatan) }}</textarea>
            </div>

            <div class="pt-4 flex items-center justify-end">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition duration-200">
                    Update Status
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
