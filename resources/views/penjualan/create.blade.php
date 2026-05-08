@extends('layouts.admin')
@section('title', 'Input Penjualan - LPG Distribution')
@section('page_title', 'Form Penjualan Baru')

@section('content')
<div class="max-w-4xl" x-data="{ 
    jumlah: {{ old('jumlah_tabung', 0) }}, 
    harga: 0,
    metode: '{{ old('metode_pembayaran', 'cash') }}',
    updateHarga() {
        const select = document.getElementById('lpg_price_id');
        const selectedOption = select.options[select.selectedIndex];
        this.harga = selectedOption.dataset.price || 0;
    },
    get total() {
        return this.jumlah * this.harga;
    },
    formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
    }
}" x-init="updateHarga()">
    
    <div class="mb-6">
        <a href="{{ route('penjualan.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
            Kembali
        </a>
    </div>

    <form action="{{ route('penjualan.store') }}" method="POST" class="space-y-6">
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
                                <select name="surat_jalan_id" required class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition">
                                    <option value="">-- Pilih SJ --</option>
                                    @foreach($suratJalans as $sj)
                                        <option value="{{ $sj->id }}" {{ old('surat_jalan_id') == $sj->id ? 'selected' : '' }}>
                                            {{ $sj->nomor_surat_jalan }} ({{ $sj->truck->nomor_polisi }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pangkalan / Pembeli <span class="text-red-500">*</span></label>
                            <input list="pangkalan_list" id="pangkalan_input" placeholder="Ketik nama pangkalan..." class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition" 
                                   onchange="updatePangkalanId(this.value)">
                            <datalist id="pangkalan_list">
                                @foreach($pangkalans as $p)
                                    <option value="{{ $p->nama_pangkalan }}" data-id="{{ $p->id }}"></option>
                                @endforeach
                            </datalist>
                            <input type="hidden" name="pangkalan_id" id="pangkalan_id" value="{{ old('pangkalan_id') }}" required>
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
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Metode & Status Pembayaran</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                            <div class="grid grid-cols-3 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="metode_pembayaran" value="cash" x-model="metode" class="hidden peer">
                                    <div class="text-center p-3 border border-gray-200 rounded-xl peer-checked:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:text-emerald-700 transition">
                                        <p class="text-xs font-bold uppercase">CASH</p>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="metode_pembayaran" value="transfer" x-model="metode" class="hidden peer">
                                    <div class="text-center p-3 border border-gray-200 rounded-xl peer-checked:bg-blue-50 peer-checked:border-blue-500 peer-checked:text-blue-700 transition">
                                        <p class="text-xs font-bold uppercase">TRANSFER</p>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="metode_pembayaran" value="utang" x-model="metode" class="hidden peer">
                                    <div class="text-center p-3 border border-gray-200 rounded-xl peer-checked:bg-red-50 peer-checked:border-red-500 peer-checked:text-red-700 transition">
                                        <p class="text-xs font-bold uppercase">UTANG</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div x-show="metode === 'utang'" x-transition x-cloak>
                            <label class="block text-sm font-medium text-red-700 mb-1">Tanggal Jatuh Tempo <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo', date('Y-m-d', strtotime('+14 days'))) }}" class="w-full px-4 py-2 border border-red-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500 transition">
                            <p class="text-[10px] text-gray-500 mt-1 italic">Piutang akan otomatis tercatat ke sistem keuangan.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan</label>
                            <textarea name="catatan" rows="2" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Summary Card --}}
            <div class="lg:col-span-1">
                <div class="bg-gray-900 rounded-3xl shadow-xl p-6 sticky top-6 text-white border border-gray-800">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6">Ringkasan Penjualan</h3>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-400">Jumlah Tabung</span>
                            <span class="text-lg font-bold" x-text="jumlah + ' Pcs'"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-400">Harga Satuan</span>
                            <span class="text-sm font-semibold" x-text="formatRupiah(harga)"></span>
                        </div>
                        <div class="pt-4 border-t border-gray-800 flex justify-between items-center">
                            <span class="text-sm text-gray-300 font-bold uppercase tracking-widest">Total Bayar</span>
                        </div>
                        <div class="text-3xl font-black text-emerald-400" x-text="formatRupiah(total)"></div>
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
        if (oldId) {
            const options = document.getElementById('pangkalan_list').options;
            for (let i = 0; i < options.length; i++) {
                if (options[i].getAttribute('data-id') == oldId) {
                    document.getElementById('pangkalan_input').value = options[i].value;
                    break;
                }
            }
        }
    };
</script>
@endsection
