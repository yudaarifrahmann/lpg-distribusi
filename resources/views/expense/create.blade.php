@extends('layouts.admin')
@section('title', 'Input Pengeluaran - LPG Distribution')
@section('page_title', 'Input Pengeluaran Baru')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('expense.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition font-bold">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
            Kembali
        </a>
    </div>

    <form action="{{ route('expense.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @csrf
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest mb-6 border-b border-gray-50 pb-4">Informasi Utama</h3>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tanggal <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_pengeluaran" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition">
                            @error('tanggal_pengeluaran')<p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Kategori <span class="text-red-500">*</span></label>
                            <select name="expense_category_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('expense_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                                @endforeach
                            </select>
                            @error('expense_category_id')<p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Pengeluaran / Deskripsi <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_pengeluaran" value="{{ old('nama_pengeluaran') }}" placeholder="Contoh: Solar Truk B 1234 ABC" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition">
                        @error('nama_pengeluaran')<p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nominal (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="nominal" value="{{ old('nominal') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-lg font-black text-emerald-600 focus:ring-2 focus:ring-emerald-500 transition">
                            @error('nominal')<p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Metode Bayar <span class="text-red-500">*</span></label>
                            <select name="metode_pembayaran" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition">
                                <option value="cash" {{ old('metode_pembayaran') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                            </select>
                            @error('metode_pembayaran')<p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Keterangan Tambahan</label>
                        <textarea name="keterangan" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="md:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest mb-6 border-b border-gray-50 pb-4">Bukti Nota (Multi)</h3>
                
                <div x-data="{ images: [] }" class="space-y-4">
                    <label class="block w-full cursor-pointer">
                        <div class="border-2 border-dashed border-gray-200 rounded-2xl p-8 flex flex-col items-center justify-center hover:bg-gray-50 transition group">
                            <svg class="w-10 h-10 text-gray-300 group-hover:text-emerald-500 transition mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Klik untuk Upload</p>
                        </div>
                        <input type="file" name="attachments[]" multiple accept="image/*" class="hidden" 
                               @change="images = Array.from($event.target.files).map(file => URL.createObjectURL(file))">
                    </label>

                    <template x-if="images.length > 0">
                        <div class="grid grid-cols-3 gap-2">
                            <template x-for="(img, index) in images" :key="index">
                                <div class="relative aspect-square rounded-xl overflow-hidden group">
                                    <img :src="img" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                        <button type="button" @click="images.splice(index, 1)" class="p-1 bg-red-500 text-white rounded-full">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                    @error('attachments.*')<p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="pt-8 mt-8 border-t border-gray-50">
                    <button type="submit" class="w-full py-4 bg-gray-900 text-white font-bold rounded-2xl shadow-xl hover:bg-black transition transform active:scale-95">
                        AJUKAN PENGELUARAN
                    </button>
                    <p class="text-[10px] text-gray-400 text-center mt-3 leading-relaxed italic">
                        Pengeluaran akan diverifikasi oleh Admin Keuangan/SuperAdmin.
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
