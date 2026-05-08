@extends('layouts.admin')
@section('title', 'Buat Penyesuaian Stok')
@section('page_title', 'Buat Penyesuaian Stok')

@section('content')
<div class="bg-white rounded-xl shadow p-8 max-w-xl mx-auto">
    <h2 class="text-lg font-bold mb-4">Form Penyesuaian Stok</h2>
    <form action="{{ route('stock-adjustment.store') }}" method="POST" class="space-y-6">
        @csrf
        <div>
            <label for="tanggal_adjustment" class="block text-sm font-medium text-gray-700">Tanggal Penyesuaian</label>
            <input type="date" name="tanggal_adjustment" id="tanggal_adjustment" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500" value="{{ old('tanggal_adjustment', date('Y-m-d')) }}" required>
            @error('tanggal_adjustment')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
        </div>
        <div>
            <label for="lokasi_stok" class="block text-sm font-medium text-gray-700">Lokasi Stok</label>
            <select name="lokasi_stok" id="lokasi_stok" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500" required onchange="document.getElementById('truck-field').style.display = this.value === 'kendaraan' ? 'block' : 'none'">
                <option value="gudang" {{ old('lokasi_stok') == 'gudang' ? 'selected' : '' }}>Gudang</option>
                <option value="kendaraan" {{ old('lokasi_stok') == 'kendaraan' ? 'selected' : '' }}>Kendaraan</option>
            </select>
            @error('lokasi_stok')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
        </div>
        <div id="truck-field" style="display:{{ old('lokasi_stok') == 'kendaraan' ? 'block' : 'none' }};">
            <label for="truck_id" class="block text-sm font-medium text-gray-700">Pilih Kendaraan</label>
            <select name="truck_id" id="truck_id" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">
                <option value="">-- Pilih Kendaraan --</option>
                @foreach($trucks as $truck)
                    <option value="{{ $truck->id }}" {{ old('truck_id') == $truck->id ? 'selected' : '' }}>{{ $truck->nomor_polisi }}</option>
                @endforeach
            </select>
            @error('truck_id')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
        </div>
        <div>
            <label for="stok_sebelum" class="block text-sm font-medium text-gray-700">Stok Sebelum</label>
            <input type="number" name="stok_sebelum" id="stok_sebelum" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500" value="{{ old('stok_sebelum', $gudangStock ?? 0) }}" required readonly>
        </div>
        <div>
            <label for="stok_setelah" class="block text-sm font-medium text-gray-700">Stok Setelah</label>
            <input type="number" name="stok_setelah" id="stok_setelah" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500" value="{{ old('stok_setelah') }}" required>
            @error('stok_setelah')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
        </div>
        <div>
            <label for="alasan_penyesuaian" class="block text-sm font-medium text-gray-700">Alasan Penyesuaian</label>
            <textarea name="alasan_penyesuaian" id="alasan_penyesuaian" rows="2" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500" required>{{ old('alasan_penyesuaian') }}</textarea>
            @error('alasan_penyesuaian')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
        </div>
        <div class="flex justify-end">
            <a href="{{ route('stock-adjustment.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded mr-2">Batal</a>
            <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded font-bold hover:bg-red-700">Simpan</button>
        </div>
    </form>
</div>
@endsection
