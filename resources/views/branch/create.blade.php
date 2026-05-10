@extends('layouts.admin')

@section('title', 'Tambah Cabang')
@section('page_title', 'Tambah Cabang')

@section('content')
<div class="max-w-4xl mx-auto" x-data="{ branches: {{ json_encode(old('branches', [['name'=>'', 'phone'=>'', 'address'=>'']])) }} }">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-xl font-bold text-gray-800">Tambah Cabang</h3>
        <a href="{{ route('branch.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Kembali</a>
    </div>
    
    <form action="{{ route('branch.store') }}" method="POST">
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
        <div class="space-y-4">
            <template x-for="(branch, index) in branches" :key="index">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 relative">
                    <button type="button" x-show="branches.length > 1" @click="branches.splice(index, 1)" class="absolute top-4 right-4 text-red-500 hover:bg-red-50 p-1.5 rounded-lg transition" title="Hapus baris ini">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Cabang <span class="text-red-500">*</span></label>
                            <input type="text" x-model="branch.name" :name="`branches[${index}][name]`" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Telepon</label>
                            <input type="text" x-model="branch.phone" :name="`branches[${index}][phone]`" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                            <textarea x-model="branch.address" :name="`branches[${index}][address]`" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"></textarea>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div class="flex justify-between items-center mt-6">
            <button type="button" @click="branches.push({name:'', phone:'', address:''})" class="px-4 py-2.5 text-sm font-medium text-blue-600 bg-white border border-blue-200 hover:bg-blue-50 rounded-lg flex items-center shadow-sm transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Tambah Cabang Lainnya
            </button>
            <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md transition">
                Simpan Semua Cabang
            </button>
        </div>
    </form>
</div>
@endsection
