@extends('layouts.admin')
@section('title', 'Input Pengeluaran - LPG Distribution')
@section('page_title', 'Input Pengeluaran Baru')

@section('content')
<div class="max-w-5xl" x-data="{ 
    expenses: [
        { id: Date.now(), nama: '', category_id: '', nominal: 0, formattedNominal: '', metode: 'cash', keterangan: '', preview: null }
    ],
    addExpense() {
        this.expenses.unshift({ id: Date.now() + Math.random(), nama: '', category_id: '', nominal: 0, formattedNominal: '', metode: 'cash', keterangan: '', preview: null });
    },
    removeExpense(index) {
        if (this.expenses.length > 1) {
            this.expenses.splice(index, 1);
        } else {
            this.expenses[0] = { id: Date.now(), nama: '', category_id: '', nominal: 0, formattedNominal: '', metode: 'cash', keterangan: '', preview: null };
        }
    },
    get total() {
        return this.expenses.reduce((sum, item) => sum + (parseFloat(item.nominal) || 0), 0);
    },
    formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
    }
}">
    <div class="mb-6">
        <a href="{{ route('expense.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition font-bold">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
            Kembali
        </a>
    </div>

    <form action="{{ route('expense.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-6 border-b border-gray-50 pb-4">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Informasi Pengeluaran</h3>
                    <button type="button" @click="addExpense()" class="inline-flex items-center px-3 py-1.5 bg-gray-900 text-white text-[10px] font-bold uppercase tracking-wider rounded-xl hover:bg-black transition shadow-sm">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Tambah Biaya Lainnya
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tanggal Global <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_pengeluaran" value="{{ date('Y-m-d') }}" required class="w-full md:w-40 px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition">
                        </div>

                        @if(auth()->user()->hasAnyRole(['superadmin', 'admin_keuangan']))
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Pengeluaran Atas Nama Siapa <span class="text-red-500">*</span></label>
                            <select name="user_id" required class="w-full md:w-72 px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition">
                                <option value="">-- Pilih Akun / Nama --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', auth()->id()) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                        @if($user->roles->count() > 0)
                                            ({{ $user->roles->pluck('name')->map(function($r) { return str_replace('_', ' ', ucwords($r, '_')); })->join(', ') }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>

                    <div class="space-y-4">
                        <template x-for="(expense, index) in expenses" :key="expense.id">
                            <div class="p-5 rounded-2xl border border-gray-100 bg-gray-50/50 relative group">
                                <button type="button" @click="removeExpense(index)" class="absolute -top-2 -right-2 p-1.5 bg-white text-gray-300 hover:text-red-500 rounded-full border border-gray-100 shadow-sm transition opacity-0 group-hover:opacity-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nama Pengeluaran / Deskripsi <span class="text-red-500">*</span></label>
                                        <input type="text" :name="`items[${index}][nama]`" x-model="expense.nama" placeholder="Contoh: Solar Truk B 1234 ABC" required class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Kategori</label>
                                        <select :name="`items[${index}][category_id]`" x-model="expense.category_id" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition">
                                            <option value="">-- Pilih Kategori --</option>
                                            @foreach(\App\Models\ExpenseCategory::all() as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nominal (Rp) <span class="text-red-500">*</span></label>
                                        <input type="hidden" :name="`items[${index}][nominal]`" :value="expense.nominal">
                                        <input type="text" x-model="expense.formattedNominal" 
                                               @input="expense.nominal = expense.formattedNominal.replace(/\D/g, ''); 
                                                       expense.formattedNominal = expense.nominal.replace(/\B(?=(\d{3})+(?!\d))/g, '.')" 
                                               required placeholder="0" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm font-black text-emerald-600 focus:ring-2 focus:ring-emerald-500 transition">
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Metode Bayar <span class="text-red-500">*</span></label>
                                        <select :name="`items[${index}][metode]`" x-model="expense.metode" required class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition">
                                            <option value="cash">Cash</option>
                                            <option value="transfer">Transfer</option>
                                        </select>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Bukti Nota / Resi</label>
                                        <div class="flex items-center space-x-4">
                                            <label class="cursor-pointer group relative">
                                                <div class="w-16 h-16 rounded-xl border-2 border-dashed border-gray-200 flex items-center justify-center group-hover:border-emerald-500 group-hover:bg-emerald-50 transition overflow-hidden">
                                                    <template x-if="!expense.preview">
                                                        <svg class="w-6 h-6 text-gray-300 group-hover:text-emerald-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                    </template>
                                                    <template x-if="expense.preview">
                                                        <img :src="expense.preview" class="w-full h-full object-cover">
                                                    </template>
                                                </div>
                                                <input type="file" :name="`items[${index}][attachment]`" class="hidden" accept="image/*"
                                                       @change="
                                                            const file = $event.target.files[0];
                                                            if (file) {
                                                                const reader = new FileReader();
                                                                reader.onload = (e) => expense.preview = e.target.result;
                                                                reader.readAsDataURL(file);
                                                            }
                                                       ">
                                            </label>
                                            <div class="flex-1">
                                                <p class="text-[10px] text-gray-400 leading-tight">Klik ikon untuk upload nota khusus untuk pengeluaran ini. Format JPG/PNG, Max 2MB.</p>
                                                <template x-if="expense.preview">
                                                    <button type="button" @click="expense.preview = null" class="mt-1 text-[9px] font-bold text-red-500 uppercase">Hapus Foto</button>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-6">
            <div class="bg-gray-900 p-6 rounded-3xl shadow-xl text-white">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6">Ringkasan</h3>
                
                <div class="space-y-4 mb-8">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Total Item</span>
                        <span class="text-lg font-bold" x-text="expenses.length"></span>
                    </div>
                    <div class="pt-4 border-t border-gray-800 flex justify-between items-center">
                        <span class="text-sm font-bold uppercase tracking-widest text-gray-300">Total Nominal</span>
                        <span class="text-xl font-black text-emerald-400" x-text="formatRupiah(total)"></span>
                    </div>
                </div>

                <button type="submit" class="w-full py-4 bg-emerald-500 text-white font-black uppercase tracking-widest rounded-2xl shadow-lg shadow-emerald-500/20 hover:bg-emerald-400 transition transform active:scale-95">
                    SIMPAN SEMUA
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

