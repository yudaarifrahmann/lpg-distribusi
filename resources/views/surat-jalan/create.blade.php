@extends('layouts.admin')
@section('title', 'Buat SJ Baru - LPG Distribution')
@section('page_title', 'Buat Surat Jalan')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('surat-jalan.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800">Form Surat Jalan</h3>
            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full uppercase tracking-wider">Distribusi Stok</span>
        </div>
        <form action="{{ route('surat-jalan.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6" x-ref="sjForm">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Surat Jalan <span class="text-red-500">*</span></label>
                    <input type="text" name="nomor_surat_jalan" value="{{ old('nomor_surat_jalan', 'SJ-'.date('YmdHis')) }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
                    @error('nomor_surat_jalan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Berangkat <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_berangkat" value="{{ old('tanggal_berangkat', date('Y-m-d')) }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
                    @error('tanggal_berangkat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div x-data="{ 
                muatGudang: false, 
                stokGudang: {{ $stokGudang }}, 
                jumlahTabung: {{ old('jumlah_tabung', 560) }},
                handleMuatGudang() {
                    if (this.muatGudang) {
                        this.jumlahTabung = Math.min(this.stokGudang, 560);
                    }
                },
                handlePenebusanChange(el) {
                    let selected = el.options[el.selectedIndex];
                    if (selected.value && selected.dataset.jumlah) {
                        this.jumlahTabung = selected.dataset.jumlah;
                    }
                }
            }" x-init="$watch('muatGudang', value => handleMuatGudang())">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Nomor DO (Penebusan)</label>
                        <select name="penebusan_id" :required="!muatGudang" :disabled="muatGudang" @change="handlePenebusanChange($event.target)" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition disabled:bg-gray-100 disabled:text-gray-400">
                            <option value="">-- Pilih DO --</option>
                            @foreach($penebusans as $p)
                                <option value="{{ $p->id }}" data-jumlah="{{ $p->jumlah_tabung }}" {{ old('penebusan_id') == $p->id ? 'selected' : '' }}>
                                    DO #{{ $p->nomor_do }} ({{ $p->tanggal_penebusan->format('d/m/Y') }}) - {{ $p->truck->nomor_polisi }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-gray-400 mt-1" x-show="!muatGudang">Stok DO sudah otomatis ada di kendaraan saat pembayaran berhasil.</p>
                    </div>
                    <div class="flex items-end pb-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="muat_dari_gudang" value="1" x-model="muatGudang" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            <span class="ml-3 text-sm font-bold text-gray-700">Muat Barang dari Gudang</span>
                        </label>
                    </div>
                </div>

                <div x-show="muatGudang" class="p-4 bg-blue-50 rounded-xl border border-blue-100 mb-4 animate-pulse-subtle">
                    <p class="text-xs text-blue-700 font-medium">
                        <strong>Info:</strong> Fitur ini akan mengambil stok dari <strong>Gudang Utama</strong> dan menambahkannya ke stok kendaraan. 
                        Pastikan jumlah tabung yang diinput tersedia di gudang.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Tabung <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah_tabung" x-model="jumlahTabung" required min="1" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
                        @error('jumlah_tabung')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100" x-data="{ isSupirTembak: false, isKnekTembak: false }">
                <h4 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wider">Personil & Armada</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                    {{-- Driver Section --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Supir Utama <span class="text-red-500">*</span></label>
                        <select name="driver_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">-- Pilih Supir --</option>
                            @foreach($supirs as $supir)
                                <option value="{{ $supir->id }}" {{ old('driver_id') == $supir->id ? 'selected' : '' }}>
                                    {{ $supir->nama }} (Truck: {{ $supir->truck->nomor_polisi ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Truck armada otomatis menggunakan Truck Default supir.</p>
                        @error('driver_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Knek Section --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Knek Pendamping</label>
                        <select name="knek_id" @change="isKnekTembak = ($event.target.value === 'tembak')" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition" :class="isKnekTembak ? 'border-amber-400 bg-amber-50' : ''">
                            <option value="">-- Tanpa Knek --</option>
                            @foreach($kneks as $knek)
                                <option value="{{ $knek->id }}" {{ old('knek_id') == $knek->id ? 'selected' : '' }}>{{ $knek->nama }}</option>
                            @endforeach
                            <option value="tembak" {{ old('knek_id') == 'tembak' ? 'selected' : '' }}>-- KNEK TEMBAK (LUAR) --</option>
                        </select>
                        <input type="hidden" name="is_knek_tembak" :value="isKnekTembak ? 1 : 0">

                        {{-- Knek Tembak Inputs --}}
                        <div x-show="isKnekTembak" x-transition class="mt-3 p-4 bg-amber-50 rounded-xl border border-amber-100 space-y-3">
                            <p class="text-[10px] font-bold text-amber-800 uppercase tracking-widest">Detail Knek Luar</p>
                            <input type="text" name="nama_knek_tembak" placeholder="Nama Lengkap" :required="isKnekTembak" class="w-full px-3 py-2 border border-amber-200 rounded-lg text-xs focus:ring-1 focus:ring-amber-500">
                            <input type="text" name="no_hp_knek_tembak" placeholder="Nomor HP" :required="isKnekTembak" class="w-full px-3 py-2 border border-amber-200 rounded-lg text-xs focus:ring-1 focus:ring-amber-500">
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Perjalanan</label>
                <textarea name="catatan" rows="3" placeholder="Contoh: Rute pengiriman wilayah Utara..." class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">{{ old('catatan') }}</textarea>
            </div>

            <div x-data="{ showModal: false, actionType: 'persiapan' }">
                <style> [x-cloak] { display: none !important; } </style>
                
                <div class="pt-4 flex items-center justify-end">
                    <button type="button" @click="showModal = true" class="px-8 py-3 bg-indigo-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition duration-200 hover:-translate-y-0.5">
                        Konfirmasi & Berangkatkan
                    </button>
                </div>

                <!-- Modal Overlay -->
                <template x-teleport="body">
                    <div x-show="showModal" class="fixed inset-0 z-[999] overflow-y-auto" x-cloak>
                        <!-- Backdrop -->
                        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="showModal = false"></div>

                        <!-- Modal Content -->
                        <div class="flex items-center justify-center min-h-screen p-4">
                            <div x-show="showModal" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                 class="relative bg-white rounded-3xl shadow-2xl transform transition-all sm:max-w-lg sm:w-full overflow-hidden border border-gray-100">
                                
                                <div class="p-8">
                                    <div class="sm:flex sm:items-start">
                                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-2xl bg-indigo-50 sm:mx-0">
                                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                        </div>
                                        <div class="mt-4 text-center sm:mt-0 sm:ml-6 sm:text-left">
                                            <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">Konfirmasi</h3>
                                            <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                                                Tentukan status keberangkatan truk saat ini.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mt-8 flex flex-col sm:flex-row-reverse gap-3">
                                        <button type="button" @click="actionType = 'berangkat'; $nextTick(() => $refs.sjForm.submit())" class="flex-1 px-6 py-3 bg-emerald-600 text-white text-sm font-bold rounded-2xl hover:bg-emerald-700 shadow-lg shadow-emerald-500/30 transition uppercase tracking-widest">
                                            Berangkat
                                        </button>
                                        <button type="button" @click="actionType = 'persiapan'; $nextTick(() => $refs.sjForm.submit())" class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 text-sm font-bold rounded-2xl hover:bg-gray-200 transition uppercase tracking-widest">
                                            Persiapan
                                        </button>
                                    </div>
                                    <button type="button" @click="showModal = false" class="mt-4 w-full text-center text-xs font-semibold text-gray-400 hover:text-gray-600 transition">
                                        Batal & Kembali ke Form
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <input type="hidden" name="action" :value="actionType">
            </div>
        </form>
    </div>
</div>
@endsection
