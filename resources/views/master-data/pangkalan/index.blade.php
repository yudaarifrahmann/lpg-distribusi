@extends('layouts.admin')
@section('title', 'Master Pangkalan - LPG Distribution')
@section('page_title', 'Master Pangkalan')

@section('content')
<div x-data="{ showCreate: false, showEdit: false, editData: {} }">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Data Pangkalan</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data pangkalan distribusi LPG</p>
        </div>
        @can('create master data')
        <button @click="showCreate = true" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-200 hover:-translate-y-0.5">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
            Tambah Pangkalan
        </button>
        @endcan
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET" action="{{ route('pangkalan.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama pangkalan, pemilik, alamat..." class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>
            <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Semua Status</option>
                <option value="aktif" {{ $status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ $status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2.5 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">
                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/></svg>Cari
                </button>
                <a href="{{ route('pangkalan.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 transition">Reset</a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Pangkalan</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pemilik</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No HP</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($pangkalans as $i => $p)
                    <tr class="hover:bg-blue-50/40 transition">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $pangkalans->firstItem() + $i }}</td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-semibold text-gray-800">{{ $p->nama_pangkalan }}</p>
                            <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ $p->alamat }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $p->nama_pemilik }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $p->no_hp }}</td>
                        <td class="px-6 py-4">
                            @if($p->status == 'aktif')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700"><span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-1">
                                <a href="{{ route('pangkalan.show', $p) }}" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition" title="Detail">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                                </a>
                                @can('edit master data')
                                <button @click="editData = { id: {{ $p->id }}, nama_pangkalan: '{{ addslashes($p->nama_pangkalan) }}', nama_pemilik: '{{ addslashes($p->nama_pemilik) }}', alamat: `{{ addslashes($p->alamat) }}`, no_hp: '{{ $p->no_hp }}', status: '{{ $p->status }}' }; showEdit = true" class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Edit">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                                </button>
                                @endcan
                                @can('delete master data')
                                <form action="{{ route('pangkalan.destroy', $p) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pangkalan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg></button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                            <p class="mt-4 text-sm font-medium text-gray-500">Belum ada data pangkalan</p>
                            <p class="mt-1 text-xs text-gray-400">Klik tombol "Tambah Pangkalan" untuk menambahkan data baru</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pangkalans->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $pangkalans->withQueryString()->links() }}</div>
        @endif
    </div>

    {{-- Modal Create --}}
    <div x-show="showCreate" x-cloak class="fixed inset-0 z-50 overflow-y-auto" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showCreate = false"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 z-10" @click.away="showCreate = false">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-gray-800">Tambah Pangkalan</h3>
                    <button @click="showCreate = false" class="p-1 hover:bg-gray-100 rounded-lg"><svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></button>
                </div>
                <form action="{{ route('pangkalan.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pangkalan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_pangkalan" value="{{ old('nama_pangkalan') }}" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('nama_pangkalan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pemilik <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_pemilik" value="{{ old('nama_pemilik') }}" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('nama_pemilik')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat <span class="text-red-500">*</span></label>
                        <textarea name="alamat" rows="2" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('alamat') }}</textarea>
                        @error('alamat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No HP <span class="text-red-500">*</span></label>
                            <input type="text" name="no_hp" value="{{ old('no_hp') }}" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('no_hp')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                            <select name="status" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" @click="showCreate = false" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Batal</button>
                        <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-500 rounded-lg shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div x-show="showEdit" x-cloak class="fixed inset-0 z-50 overflow-y-auto" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showEdit = false"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 z-10">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-gray-800">Edit Pangkalan</h3>
                    <button @click="showEdit = false" class="p-1 hover:bg-gray-100 rounded-lg"><svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></button>
                </div>
                <form :action="'{{ url('master-data/pangkalan') }}/' + editData.id" method="POST" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pangkalan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_pangkalan" x-model="editData.nama_pangkalan" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pemilik <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_pemilik" x-model="editData.nama_pemilik" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat <span class="text-red-500">*</span></label>
                        <textarea name="alamat" rows="2" x-text="editData.alamat" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No HP <span class="text-red-500">*</span></label>
                            <input type="text" name="no_hp" x-model="editData.no_hp" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                            <select name="status" x-model="editData.status" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" @click="showEdit = false" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Batal</button>
                        <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-amber-500 to-amber-400 rounded-lg shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50 transition">Perbarui</button>
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
