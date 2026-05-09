@extends('layouts.admin')
@section('title', 'Input Penjualan - LPG Distribution')
@section('page_title', 'Form Penjualan Baru')

@section('content')
<div class="max-w-6xl mx-auto" x-data="{ 
    jumlah: {{ old('jumlah_tabung', 0) }}, 
    harga: 0,
    currentStok: 0,
    nominalCash: {{ old('nominal_cash', 0) }},
    nominalTransfer: {{ old('nominal_transfer', 0) }},
    multi: false,
    metode: ['cash'],
    rekeningPopup: false,
    rekeningDetail: {
        bank: 'BCA',
        norek: '1234567890',
        nama: 'PT LPG DISTRIBUSI'
    },
    updateHarga() {
        const select = document.getElementById('lpg_price_id');
        if (!select) return;
        const selectedOption = select.options[select.selectedIndex];
        this.harga = selectedOption.dataset.price || 0;
    },
    get total() {
        return this.jumlah * this.harga;
    },
    get sisaUtang() {
        return Math.max(0, this.total - this.nominalCash - this.nominalTransfer);
    },
    handleSJChange(el) {
        if (!el) return;
        let selected = el.options[el.selectedIndex];
        this.currentStok = (selected.value && selected.dataset.stok) ? selected.dataset.stok : 0;
    },
    toggleMetode(val) {
        if (!this.multi) {
            this.metode = [val];
        } else {
            if (this.metode.includes(val)) {
                this.metode = this.metode.filter(m => m !== val);
                if (val === 'cash') this.nominalCash = 0;
                if (val === 'transfer') this.nominalTransfer = 0;
            } else {
                this.metode.push(val);
            }
        }
        this.autoFillSinglePayment();
    },
    toggleMulti() {
        if (!this.multi) {
            if (this.metode.length > 1) {
                this.metode = [this.metode[0]];
            } else if (this.metode.length === 0) {
                this.metode = ['cash'];
            }
        }
        this.autoFillSinglePayment();
    },
    autoFillSinglePayment() {
        if (!this.multi && this.metode.length === 1) {
            if (this.metode[0] === 'cash') {
                this.nominalCash = this.total;
                this.nominalTransfer = 0;
            } else if (this.metode[0] === 'transfer') {
                this.nominalTransfer = this.total;
                this.nominalCash = 0;
            } else if (this.metode[0] === 'utang') {
                this.nominalCash = 0;
                this.nominalTransfer = 0;
            }
        }
    },
    formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
    }
}" x-init="
    updateHarga();
    $nextTick(() => {
        let sjSelect = document.getElementById('surat_jalan_id');
        if (sjSelect && sjSelect.value) {
            handleSJChange(sjSelect);
        }
    });
">
    
    <div class="mb-6">
        <a href="{{ route('penjualan.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
            Kembali
        </a>
    </div>

    <form action="{{ route('penjualan.store') }}" method="POST" class="space-y-6" @submit="nominalCash = nominalCash || 0; nominalTransfer = nominalTransfer || 0">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Column: Form Fields --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Informasi Transaksi</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_penjualan" value="{{ old('tanggal_penjualan', date('Y-m-d')) }}" required class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Surat Jalan (SJ Aktif) <span class="text-red-500">*</span></label>
                                <select name="surat_jalan_id" id="surat_jalan_id" required @change="handleSJChange($event.target)" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition">
                                    <option value="">-- Pilih SJ --</option>
                                    @foreach($suratJalans as $sj)
                                        <option value="{{ $sj->id }}" 
                                                data-stok="{{ $sj->truck->vehicleStock->stok_saat_ini ?? 0 }}"
                                                {{ (old('surat_jalan_id') == $sj->id || count($suratJalans) === 1) ? 'selected' : '' }}>
                                            {{ $sj->nomor_surat_jalan }} ({{ $sj->truck->nomor_polisi }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pangkalan / Pembeli <span class="text-red-500">*</span></label>
                            <input type="text" list="pangkalan_list" name="pangkalan_nama" id="pangkalan_input" value="{{ old('pangkalan_nama') }}" placeholder="Ketik nama pangkalan..." required class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition" 
                                   oninput="updatePangkalanId(this.value)">
                            <datalist id="pangkalan_list">
                                @foreach($pangkalans as $p)
                                    <option value="{{ $p->nama_pangkalan }}" data-id="{{ $p->id }}"></option>
                                @endforeach
                            </datalist>
                            <input type="hidden" name="pangkalan_id" id="pangkalan_id" value="{{ old('pangkalan_id') }}">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Harga LPG <span class="text-red-500">*</span></label>
                                <select name="lpg_price_id" id="lpg_price_id" @change="updateHarga()" required class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition">
                                    <option value="">-- Pilih Harga --</option>
                                    @foreach($prices as $price)
                                        <option value="{{ $price->id }}" data-price="{{ $price->harga }}" {{ old('lpg_price_id') == $price->id ? 'selected' : '' }}>
                                            {{ $price->nama_harga }} (Rp {{ number_format($price->harga) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Tabung (Pcs) <span class="text-red-500">*</span></label>
                                <input type="number" name="jumlah_tabung" x-model="jumlah" required min="1" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm font-bold text-indigo-600 focus:ring-2 focus:ring-emerald-500 transition">
                                <template x-if="currentStok > 0">
                                    <p class="text-[10px] text-gray-400 mt-1">Sisa di Mobil: <span class="font-bold text-emerald-600" x-text="currentStok"></span> Pcs</p>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Retur Tabung Section --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-amber-50 border-b border-amber-100 flex justify-between items-center">
                        <div>
                            <h3 class="text-sm font-bold text-amber-800 uppercase tracking-widest">Retur Tabung (Opsional)</h3>
                            <p class="text-[10px] text-amber-600 font-medium">Isi jika ada tabung bocor/rusak saat pengantaran</p>
                        </div>
                        <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Retur</label>
                                <input type="number" name="jumlah_retur" value="{{ old('jumlah_retur', 0) }}" min="0" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 transition" placeholder="0">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi Tabung</label>
                                <select name="kondisi_tabung" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 transition">
                                    <option value="">-- Pilih Kondisi --</option>
                                    <option value="bocor" {{ old('kondisi_tabung') == 'bocor' ? 'selected' : '' }}>Bocor</option>
                                    <option value="rusak" {{ old('kondisi_tabung') == 'rusak' ? 'selected' : '' }}>Rusak / Fisik Cacat</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Rincian Pembayaran</h3>
                        <div class="flex items-center gap-3 mt-2">
                            <input type="checkbox" id="multi_pembayaran" x-model="multi" @change="toggleMulti()" class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                            <label for="multi_pembayaran" class="text-xs font-bold text-gray-500 uppercase cursor-pointer select-none">Multi Pembayaran</label>
                        </div>
                        <p class="text-xs text-gray-400 mt-0.5">Pilih satu atau lebih metode pembayaran.</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex flex-col md:flex-row gap-4">
                            <button type="button" 
                                @click="toggleMetode('cash')" 
                                :class="metode.includes('cash') ? 'bg-emerald-50 border-emerald-500 text-emerald-700 shadow-sm' : 'bg-white border-gray-200 text-gray-500 hover:bg-gray-50 hover:border-gray-300'"
                                class="flex-1 py-3 px-4 rounded-xl border-2 font-bold text-sm transition-all text-center flex flex-col items-center justify-center gap-1">
                                <svg class="w-6 h-6 mb-1" :class="metode.includes('cash') ? 'text-emerald-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Cash (Tunai)
                            </button>
                            <button type="button" 
                                @click="toggleMetode('transfer')" 
                                :class="metode.includes('transfer') ? 'bg-blue-50 border-blue-500 text-blue-700 shadow-sm' : 'bg-white border-gray-200 text-gray-500 hover:bg-gray-50 hover:border-gray-300'"
                                class="flex-1 py-3 px-4 rounded-xl border-2 font-bold text-sm transition-all text-center flex flex-col items-center justify-center gap-1">
                                <svg class="w-6 h-6 mb-1" :class="metode.includes('transfer') ? 'text-blue-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                Transfer (Bank)
                            </button>
                            <button type="button" 
                                @click="toggleMetode('utang')" 
                                :class="metode.includes('utang') ? 'bg-red-50 border-red-500 text-red-700 shadow-sm' : 'bg-white border-gray-200 text-gray-500 hover:bg-gray-50 hover:border-gray-300'"
                                class="flex-1 py-3 px-4 rounded-xl border-2 font-bold text-sm transition-all text-center flex flex-col items-center justify-center gap-1">
                                <svg class="w-6 h-6 mb-1" :class="metode.includes('utang') ? 'text-red-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                Utang (Piutang)
                            </button>
                        </div>

                        <!-- Single Payment Info -->
                        <div x-show="!multi" class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-center mt-4">
                            <p class="text-sm text-blue-700">
                                Seluruh tagihan sebesar <span class="font-bold" x-text="formatRupiah(total)"></span> akan dicatat sebagai 
                                <span class="font-bold uppercase" x-text="metode[0]"></span>.
                            </p>
                        </div>

                        <!-- Cash Input -->
                        <div x-show="metode.includes('cash')" :class="!multi ? 'hidden' : 'mt-4'">
                            <label class="flex items-center mb-1">
                                <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center mr-2 flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </span>
                                <span class="text-sm font-semibold text-gray-700">Nominal Cash (Tunai)</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm font-bold text-gray-400">Rp</span>
                                <input type="number" name="nominal_cash" x-model.number="nominalCash" 
                                    @input="if (multi && metode.includes('transfer')) nominalTransfer = Math.max(0, total - nominalCash)"
                                    min="0" step="any" class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm font-bold text-emerald-600 focus:ring-2 focus:ring-emerald-500 transition" placeholder="0" value="{{ old('nominal_cash', 0) }}">
                            </div>
                        </div>

                        <!-- Transfer Input -->
                        <div x-show="metode.includes('transfer')" :class="!multi ? 'hidden' : 'mt-4'">
                            <label class="flex items-center mb-1">
                                <span class="w-7 h-7 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center mr-2 flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                </span>
                                <span class="text-sm font-semibold text-gray-700">Nominal Transfer (Bank)</span>
                                <span class="ml-2 text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full uppercase tracking-wide">Pending Verifikasi</span>
                                <button type="button" @click="rekeningPopup = true" class="ml-2 text-xs text-blue-600 underline">Lihat Rekening</button>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm font-bold text-gray-400">Rp</span>
                                <input type="number" name="nominal_transfer" x-model.number="nominalTransfer" min="0" step="any" class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm font-bold text-blue-600 focus:ring-2 focus:ring-blue-500 transition" placeholder="0" value="{{ old('nominal_transfer', 0) }}">
                            </div>
                        </div>

                        <!-- Utang Input -->
                        <div x-show="metode.includes('utang')" class="mt-4">
                            <div class="p-4 rounded-xl bg-red-50 border border-red-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-red-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        <span class="text-sm font-bold text-red-700">Utang (Sisa Tagihan)</span>
                                    </div>
                                    <span class="text-sm font-black text-red-700" x-text="formatRupiah(sisaUtang)"></span>
                                </div>
                                <div class="mt-3">
                                    <label class="block text-xs font-semibold text-red-700 mb-1">Tanggal Jatuh Tempo <span class="text-red-500">*</span></label>
                                    <input type="date" name="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo', date('Y-m-d', strtotime('+14 days'))) }}" class="w-full px-4 py-2 border border-red-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500 transition">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan</label>
                            <textarea name="catatan" rows="2" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Popup Rekening Transfer -->
                <div x-show="rekeningPopup" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" x-cloak>
                    <div class="bg-white rounded-2xl shadow-lg p-8 max-w-xs w-full relative">
                        <button @click="rekeningPopup = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <h4 class="text-lg font-bold mb-4 text-gray-800">Detail Rekening Transfer</h4>
                        <div class="space-y-2">
                            <div><span class="font-semibold">Bank:</span> <span x-text="rekeningDetail.bank"></span></div>
                            <div><span class="font-semibold">No. Rekening:</span> <span x-text="rekeningDetail.norek"></span></div>
                            <div><span class="font-semibold">Atas Nama:</span> <span x-text="rekeningDetail.nama"></span></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Summary Card --}}
            <div class="lg:col-span-1">
                <div class="bg-gray-900 rounded-3xl shadow-xl p-6 lg:sticky lg:top-6 text-white border border-gray-800">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6">Ringkasan Penjualan</h3>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-400">Jumlah Tabung</span>
                            <span class="text-lg font-bold" x-text="jumlah + ' Pcs'"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-400">Harga Satuan</span>
                            <span class="text-sm font-semibold" x-text="formatRupiah(harga)"></span>
                        </div>
                        <div class="pt-3 border-t border-gray-800 flex justify-between items-center">
                            <span class="text-sm text-gray-300 font-bold uppercase tracking-widest">Total Tagihan</span>
                            <span class="text-2xl font-black text-emerald-400" x-text="formatRupiah(total)"></span>
                        </div>
                    </div>

                    {{-- Payment Breakdown --}}
                    <div class="space-y-2 pt-4 border-t border-gray-800 mb-6">
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">Rincian Bayar</p>
                        <div class="flex justify-between items-center" x-show="nominalCash > 0">
                            <span class="flex items-center text-xs text-emerald-400"><span class="w-2 h-2 rounded-full bg-emerald-400 mr-2"></span>Cash</span>
                            <span class="text-xs font-bold text-emerald-400" x-text="formatRupiah(nominalCash)"></span>
                        </div>
                        <div class="flex justify-between items-center" x-show="nominalTransfer > 0">
                            <span class="flex items-center text-xs text-blue-400"><span class="w-2 h-2 rounded-full bg-blue-400 mr-2"></span>Transfer <span class="ml-1 text-[9px] text-amber-400">(Pending)</span></span>
                            <span class="text-xs font-bold text-blue-400" x-text="formatRupiah(nominalTransfer)"></span>
                        </div>
                        <div class="flex justify-between items-center" x-show="sisaUtang > 0">
                            <span class="flex items-center text-xs text-red-400"><span class="w-2 h-2 rounded-full bg-red-400 mr-2"></span>Piutang</span>
                            <span class="text-xs font-bold text-red-400" x-text="formatRupiah(sisaUtang)"></span>
                        </div>
                        <div class="flex justify-between items-center" x-show="nominalCash === 0 && nominalTransfer === 0">
                            <span class="text-xs text-gray-500 italic">Belum ada nominal diisi</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-emerald-500 hover:bg-emerald-400 text-white rounded-2xl font-black uppercase tracking-widest shadow-lg shadow-emerald-500/30 transition-all duration-200 hover:-translate-y-1 active:translate-y-0">
                        SIMPAN TRANSAKSI
                    </button>
                    
                    <div class="mt-6 flex items-center justify-center space-x-2 opacity-50">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="text-[10px] font-bold">TRANSAKSI AMAN & TERENCANA</span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('extra_js')
<script>
    function updatePangkalanId(val) {
        const options = document.getElementById('pangkalan_list').options;
        const hiddenInput = document.getElementById('pangkalan_id');
        hiddenInput.value = ""; // Reset
        
        for (let i = 0; i < options.length; i++) {
            if (options[i].value === val) {
                hiddenInput.value = options[i].getAttribute('data-id');
                break;
            }
        }
    }

    // Auto-fill on load if old value exists
    window.onload = function() {
        const oldId = "{{ old('pangkalan_id') }}";
        const oldName = @json(old('pangkalan_nama'));

        if (oldName) {
            document.getElementById('pangkalan_input').value = oldName;
            updatePangkalanId(oldName);
            return;
        }

        if (oldId) {
            const options = document.getElementById('pangkalan_list').options;
            for (let i = 0; i < options.length; i++) {
                if (options[i].getAttribute('data-id') == oldId) {
                    document.getElementById('pangkalan_input').value = options[i].value;
                    updatePangkalanId(options[i].value);
                    break;
                }
            }
        }
    };
</script>
@endsection
