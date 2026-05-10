@extends('layouts.admin')
@section('title', 'Master Supir/Knek - LPG Distribution')
@section('page_title', 'Master Supir / Knek')

@section('content')
<div x-data="{ showCreate: false, showEdit: false, editData: {}, users: [], trucks: [], editUsers: [], editTrucks: [], loading: false, drivers: {{ json_encode(old('drivers', [['user_id'=>'', 'truck_id'=>'', 'nama'=>'', 'nomor_hp'=>'', 'alamat'=>'', 'role_pekerjaan'=>'supir', 'status'=>'aktif']])) }} }">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Data Supir / Knek</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data supir dan knek distribusi</p>
        </div>
        @can('create master data')
        <button @click="loading = true; fetch('{{ route('driver.create') }}').then(r=>r.json()).then(d=>{users=d.users; trucks=d.trucks; showCreate=true; loading=false})" 
                class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-200 hover:-translate-y-0.5"
                :class="{ 'opacity-75 cursor-wait': loading }" :disabled="loading">
            <template x-if="loading">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </template>
            <svg x-show="!loading" class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
            Tambah Supir/Knek
        </button>
        @endcan
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET" action="{{ route('driver.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau nomor HP..." class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <select name="role" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Semua Role</option>
                <option value="supir" {{ ($role ?? '') == 'supir' ? 'selected' : '' }}>Supir</option>
                <option value="knek" {{ ($role ?? '') == 'knek' ? 'selected' : '' }}>Knek</option>
            </select>
            <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Semua Status</option>
                <option value="aktif" {{ $status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ $status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2.5 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">Cari</button>
                <a href="{{ route('driver.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 transition">Reset</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">No HP</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Akun User</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($drivers as $i => $d)
                    <tr class="hover:bg-blue-50/40 transition">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $drivers->firstItem() + $i }}</td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-semibold text-gray-800">{{ $d->nama }}</p>
                            <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ $d->alamat }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $d->nomor_hp }}</td>
                        <td class="px-6 py-4">
                            @if($d->role_pekerjaan == 'supir')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">Supir</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">Knek</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $d->user ? $d->user->email : '-' }}</td>
                        <td class="px-6 py-4">
                            @if($d->status == 'aktif')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700"><span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-1">
                                <a href="{{ route('driver.show', $d) }}" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition" title="Detail"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg></a>
                                @can('edit master data')
                                <button @click="loading = true; fetch('{{ route('driver.edit', $d) }}').then(r=>r.json()).then(data=>{editData=data.driver; editUsers=data.users; editTrucks=data.trucks; showEdit=true; loading=false})" class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Edit" :disabled="loading">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" x-show="!loading"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                                    <svg class="animate-spin h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" x-show="loading" x-cloak><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </button>
                                @endcan
                                @can('delete master data')
                                <form action="{{ route('driver.destroy', $d) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg></button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <p class="mt-4 text-sm font-medium text-gray-500">Belum ada data supir/knek</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($drivers->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $drivers->withQueryString()->links() }}</div>
        @endif
    </div>

    {{-- Modal Create --}}
    <div x-show="showCreate" x-cloak class="fixed inset-0 z-50 overflow-y-auto" x-transition>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showCreate = false"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 z-10">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-gray-800">Tambah Supir/Knek</h3>
                    <button @click="showCreate = false" class="p-1 hover:bg-gray-100 rounded-lg"><svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></button>
                </div>
                <form action="{{ route('driver.store') }}" method="POST" class="space-y-4">
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
                        <template x-for="(driver, index) in drivers" :key="index">
                            <div class="relative p-4 border border-gray-200 rounded-xl bg-gray-50/50">
                                <button type="button" x-show="drivers.length > 1" @click="drivers.splice(index, 1)" class="absolute top-3 right-3 text-red-500 hover:bg-red-50 p-1.5 rounded-lg transition" title="Hapus baris ini">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                </button>
                                
                                <div class="grid grid-cols-1 gap-4 mt-2">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Akun User <span class="text-red-500">*</span></label>
                                            <select x-model="driver.user_id" :name="`drivers[${index}][user_id]`" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                <option value="">-- Pilih User --</option>
                                                <template x-for="u in users" :key="u.id">
                                                    <option :value="u.id" x-text="u.name + ' (' + u.email + ')'"></option>
                                                </template>
                                            </select>
                                            <p class="text-xs text-gray-400 mt-1">Hanya user supir/knek</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Truck Default (Opsional)</label>
                                            <select x-model="driver.truck_id" :name="`drivers[${index}][truck_id]`" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                <option value="">-- Pilih Truck --</option>
                                                <template x-for="t in trucks" :key="t.id">
                                                    <option :value="t.id" x-text="t.nomor_polisi + ' - ' + t.nama_truk"></option>
                                                </template>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                                            <input type="text" x-model="driver.nama" :name="`drivers[${index}][nama]`" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">No HP <span class="text-red-500">*</span></label>
                                            <input type="text" x-model="driver.nomor_hp" :name="`drivers[${index}][nomor_hp]`" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat <span class="text-red-500">*</span></label>
                                        <textarea x-model="driver.alamat" :name="`drivers[${index}][alamat]`" rows="2" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Role Pekerjaan <span class="text-red-500">*</span></label>
                                            <select x-model="driver.role_pekerjaan" :name="`drivers[${index}][role_pekerjaan]`" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                <option value="supir">Supir</option>
                                                <option value="knek">Knek</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                                            <select x-model="driver.status" :name="`drivers[${index}][status]`" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                <option value="aktif">Aktif</option>
                                                <option value="nonaktif">Nonaktif</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    
                    <div class="flex justify-between items-center pt-4 border-t mt-4">
                        <button type="button" @click="drivers.push({user_id:'', nama:'', nomor_hp:'', alamat:'', role_pekerjaan:'supir', status:'aktif'})" class="px-4 py-2 text-sm font-medium text-blue-600 hover:bg-blue-50 rounded-lg flex items-center transition">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            Tambah Supir/Knek Lainnya
                        </button>
                        <div class="flex space-x-3">
                            <button type="button" @click="showCreate = false" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Batal</button>
                            <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-500 rounded-lg shadow-lg shadow-blue-500/30 transition">Simpan Semua</button>
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
                    <h3 class="text-lg font-bold text-gray-800">Edit Supir/Knek</h3>
                    <button @click="showEdit = false" class="p-1 hover:bg-gray-100 rounded-lg"><svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></button>
                </div>
                <form :action="'{{ url('master-data/driver') }}/' + editData.id" method="POST" class="space-y-4">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Akun User <span class="text-red-500">*</span></label>
                            <select name="user_id" x-model="editData.user_id" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">-- Pilih User --</option>
                                <template x-for="u in editUsers" :key="u.id">
                                    <option :value="u.id" x-text="u.name + ' (' + u.email + ')'" :selected="u.id == editData.user_id"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Truck Default (Opsional)</label>
                            <select name="truck_id" x-model="editData.truck_id" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">-- Pilih Truck --</option>
                                <template x-for="t in editTrucks" :key="t.id">
                                    <option :value="t.id" x-text="t.nomor_polisi + ' - ' + t.nama_truk" :selected="t.id == editData.truck_id"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" x-model="editData.nama" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No HP <span class="text-red-500">*</span></label>
                            <input type="text" name="nomor_hp" x-model="editData.nomor_hp" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat <span class="text-red-500">*</span></label>
                        <textarea name="alamat" rows="2" x-text="editData.alamat" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role Pekerjaan <span class="text-red-500">*</span></label>
                            <select name="role_pekerjaan" x-model="editData.role_pekerjaan" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="supir">Supir</option>
                                <option value="knek">Knek</option>
                            </select>
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
                        <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-amber-500 to-amber-400 rounded-lg shadow-lg shadow-amber-500/30 transition">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
