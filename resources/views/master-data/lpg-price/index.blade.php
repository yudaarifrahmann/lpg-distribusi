@extends('layouts.admin')
@section('title', 'Master Harga LPG - LPG Distribution')
@section('page_title', 'Master Harga LPG')

@section('content')
<div x-data="{ showCreate: false, showEdit: false, editData: {} }">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Data Harga LPG</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola daftar harga LPG untuk dropdown transaksi</p>
        </div>
        @can('create master data')
        <button @click="showCreate = true" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-200 hover:-translate-y-0.5">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
            Tambah Harga
        </button>
        @endcan
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET" action="{{ route('lpg-price.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama harga..." class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Semua Status</option>
                <option value="aktif" {{ $status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ $status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2.5 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">Cari</button>
                <a href="{{ route('lpg-price.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 transition">Reset</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Nama Harga</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Harga</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($lpgPrices as $i => $price)
                    <tr class="hover:bg-blue-50/40 transition">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $lpgPrices->firstItem() + $i }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ $price->nama_harga }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-gradient-to-r from-emerald-50 to-emerald-100 text-emerald-700 border border-emerald-200">
                                Rp {{ number_format($price->harga, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($price->status == 'aktif')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700"><span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-1">
                                @can('edit master data')
                                <button @click="editData = { id: {{ $price->id }}, nama_harga: '{{ addslashes($price->nama_harga) }}', harga: {{ $price->harga }}, status: '{{ $price->status }}' }; showEdit = true" class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Edit">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                                </button>
                                @endcan
                                @can('delete master data')
                                <form action="{{ route('lpg-price.destroy', $price) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus harga ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg></button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="mt-4 text-sm font-medium text-gray-500">Belum ada data harga LPG</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($lpgPrices->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $lpgPrices->withQueryString()->links() }}</div>
        @endif
    </div>

    {{-- Modal Create --}}
    <div x-show="showCreate" x-cloak class="fixed inset-0 z-50 overflow-y-auto" x-transition>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showCreate = false"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 z-10">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-gray-800">Tambah Harga LPG</h3>
                    <button @click="showCreate = false" class="p-1 hover:bg-gray-100 rounded-lg"><svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></button>
                </div>
                <form action="{{ route('lpg-price.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Harga <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_harga" required placeholder="Contoh: Harga Rp15.000" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('nama_harga')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="harga" required min="0" step="100" placeholder="Contoh: 15000" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('harga')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                        <select name="status" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" @click="showCreate = false" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Batal</button>
                        <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-500 rounded-lg shadow-lg shadow-blue-500/30 transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div x-show="showEdit" x-cloak class="fixed inset-0 z-50 overflow-y-auto" x-transition>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showEdit = false"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 z-10">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-gray-800">Edit Harga LPG</h3>
                    <button @click="showEdit = false" class="p-1 hover:bg-gray-100 rounded-lg"><svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></button>
                </div>
                <form :action="'{{ url('master-data/lpg-price') }}/' + editData.id" method="POST" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Harga <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_harga" x-model="editData.nama_harga" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="harga" x-model="editData.harga" required min="0" step="100" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                        <select name="status" x-model="editData.status" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" @click="showEdit = false" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Batal</button>
                        <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-amber-500 to-amber-400 rounded-lg shadow-lg shadow-amber-500/30 transition">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if($errors->any())
<script>document.addEventListener('DOMContentLoaded', () => { document.querySelector('[x-data]').__x.$data.showCreate = true; });</script>
@endif
@endsection
