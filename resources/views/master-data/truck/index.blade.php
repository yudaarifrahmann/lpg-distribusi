@extends('layouts.admin')
@section('title', 'Master Truk - LPG Distribution')
@section('page_title', 'Master Truk')

@section('content')
<div x-data="{ showCreate: false, showEdit: false, editData: {}, trucks: {{ json_encode(old('trucks', [['nama_truk'=>'', 'nomor_polisi'=>'', 'kapasitas_tabung'=>'', 'status_kendaraan'=>'aktif']])) }} }">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Data Truk</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data armada truk distribusi</p>
        </div>
        @can('create master data')
        <button @click="showCreate = true" class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-200 hover:-translate-y-0.5">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
            Tambah Truk
        </button>
        @endcan
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET" action="{{ route('truck.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama truk atau nomor polisi..." class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>
            <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Semua Status</option>
                <option value="aktif" {{ $status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="service" {{ $status == 'service' ? 'selected' : '' }}>Service</option>
                <option value="nonaktif" {{ $status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2.5 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">Cari</button>
                <a href="{{ route('truck.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 transition">Reset</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Nama Truk</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Nomor Polisi</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Kapasitas</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($trucks as $i => $t)
                    <tr class="hover:bg-blue-50/40 transition">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $trucks->firstItem() + $i }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-800">{{ $t->nama_truk }}</td>
                        <td class="px-6 py-4"><span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-gray-100 text-gray-700 border border-gray-200">{{ $t->nomor_polisi }}</span></td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ number_format($t->kapasitas_tabung) }} tabung</td>
                        <td class="px-6 py-4">
                            @if($t->status_kendaraan == 'aktif')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Aktif</span>
                            @elseif($t->status_kendaraan == 'service')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>Service</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700"><span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-1">
                                @can('edit master data')
                                <button @click="editData = { id: {{ $t->id }}, nama_truk: '{{ addslashes($t->nama_truk) }}', nomor_polisi: '{{ $t->nomor_polisi }}', kapasitas_tabung: {{ $t->kapasitas_tabung }}, status_kendaraan: '{{ $t->status_kendaraan }}' }; showEdit = true" class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Edit">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                                </button>
                                @endcan
                                @can('delete master data')
                                <form action="{{ route('truck.destroy', $t) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus truk ini?')">
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
                            <p class="mt-4 text-sm font-medium text-gray-500">Belum ada data truk</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($trucks->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $trucks->withQueryString()->links() }}</div>
        @endif
    </div>

    {{-- Modal Create --}}
    <div x-show="showCreate" x-cloak class="fixed inset-0 z-50 overflow-y-auto" x-transition>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showCreate = false"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 z-10">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-gray-800">Tambah Truk</h3>
                    <button @click="showCreate = false" class="p-1 hover:bg-gray-100 rounded-lg"><svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></button>
                </div>
                <form action="{{ route('truck.store') }}" method="POST" class="space-y-4">
                    @csrf
                    @if($errors->any())
                    <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm mb-4">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="max-h-[60vh] overflow-y-auto pr-2 space-y-4">
                        <template x-for="(truck, index) in trucks" :key="index">
                            <div class="relative p-4 border border-gray-200 rounded-xl bg-gray-50/50">
                                <button type="button" x-show="trucks.length > 1" @click="trucks.splice(index, 1)" class="absolute top-3 right-3 text-red-500 hover:bg-red-50 p-1.5 rounded-lg transition" title="Hapus baris ini">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                </button>
                                
                                <div class="grid grid-cols-1 gap-4 mt-2">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Truk <span class="text-red-500">*</span></label>
                                        <input type="text" x-model="truck.nama_truk" :name="`trucks[${index}][nama_truk]`" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Polisi <span class="text-red-500">*</span></label>
                                        <input type="text" x-model="truck.nomor_polisi" :name="`trucks[${index}][nomor_polisi]`" required placeholder="Contoh: B 1234 ABC" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono uppercase">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Kapasitas Tabung <span class="text-red-500">*</span></label>
                                            <input type="number" x-model="truck.kapasitas_tabung" :name="`trucks[${index}][kapasitas_tabung]`" required min="1" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                                            <select x-model="truck.status_kendaraan" :name="`trucks[${index}][status_kendaraan]`" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                <option value="aktif">Aktif</option>
                                                <option value="service">Service</option>
                                                <option value="nonaktif">Nonaktif</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    
                    <div class="flex justify-between items-center pt-4 border-t mt-4">
                        <button type="button" @click="trucks.push({nama_truk:'', nomor_polisi:'', kapasitas_tabung:'', status_kendaraan:'aktif'})" class="px-4 py-2 text-sm font-medium text-blue-600 hover:bg-blue-50 rounded-lg flex items-center transition">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            Tambah Truk Lainnya
                        </button>
                        <div class="flex space-x-3">
                            <button type="button" @click="showCreate = false" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Batal</button>
                            <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition">Simpan Semua</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div x-show="showEdit" x-cloak class="fixed inset-0 z-50 overflow-y-auto" x-transition>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showEdit = false"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 z-10">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-gray-800">Edit Truk</h3>
                    <button @click="showEdit = false" class="p-1 hover:bg-gray-100 rounded-lg"><svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></button>
                </div>
                <form :action="'{{ url('master-data/truck') }}/' + editData.id" method="POST" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Truk <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_truk" x-model="editData.nama_truk" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Polisi <span class="text-red-500">*</span></label>
                        <input type="text" name="nomor_polisi" x-model="editData.nomor_polisi" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono uppercase">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kapasitas Tabung <span class="text-red-500">*</span></label>
                            <input type="number" name="kapasitas_tabung" x-model="editData.kapasitas_tabung" required min="1" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                            <select name="status_kendaraan" x-model="editData.status_kendaraan" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="aktif">Aktif</option>
                                <option value="service">Service</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" @click="showEdit = false" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Batal</button>
                        <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-amber-500 rounded-lg shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50 transition">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if($errors->any() || session('showCreate'))
<script>document.addEventListener('DOMContentLoaded', () => { document.querySelector('[x-data]').__x.$data.showCreate = true; });</script>
@endif
@endsection
