@extends('layouts.admin')
@section('title', 'Edit Penjualan - LPG Distribution')
@section('page_title', 'Update Transaksi Penjualan')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('penjualan.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800">{{ $penjualan->nomor_invoice }}</h3>
            <span class="text-xs font-bold text-gray-400 uppercase">{{ $penjualan->pangkalan->nama_pangkalan }}</span>
        </div>
        <form action="{{ route('penjualan.update', $penjualan) }}" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Penjualan <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_penjualan" required value="{{ old('tanggal_penjualan', $penjualan->tanggal_penjualan->format('Y-m-d')) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 transition">
                    @error('tanggal_penjualan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pangkalan <span class="text-red-500">*</span></label>
                    <select name="pangkalan_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 transition">
                        <option value="">Pilih Pangkalan</option>
                        @foreach(\App\Models\Pangkalan::where('status', 'aktif')->get() as $p)
                            <option value="{{ $p->id }}" {{ old('pangkalan_id', $penjualan->pangkalan_id) == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_pangkalan }}
                            </option>
                        @endforeach
                    </select>
                    @error('pangkalan_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Tabung <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_tabung" required min="1" value="{{ old('jumlah_tabung', $penjualan->jumlah_tabung) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 transition">
                    @error('jumlah_tabung')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Satuan (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="harga_satuan" required step="0.01" min="0" value="{{ old('harga_satuan', $penjualan->harga_satuan) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 transition">
                    @error('harga_satuan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Total Penjualan (Rp)</label>
                <div class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm bg-gray-50 text-gray-700 font-semibold">
                    <span id="total_display">Rp 0</span>
                </div>
                <p class="text-xs text-gray-400 mt-1">* Dihitung otomatis dari Jumlah Tabung × Harga Satuan</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran <span class="text-red-500">*</span></label>
                <select name="status_pembayaran" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 transition">
                    <option value="lunas" {{ old('status_pembayaran', $penjualan->status_pembayaran) == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="belum_lunas" {{ old('status_pembayaran', $penjualan->status_pembayaran) == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="cicilan" {{ old('status_pembayaran', $penjualan->status_pembayaran) == 'cicilan' ? 'selected' : '' }}>Cicilan</option>
                </select>
                @error('status_pembayaran')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan</label>
                <textarea name="catatan" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 transition">{{ old('catatan', $penjualan->catatan) }}</textarea>
                @error('catatan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                <p class="text-xs text-blue-700 leading-relaxed">
                    <strong>Catatan:</strong> Mengubah jumlah tabung akan otomatis menyesuaikan stok kendaraan. Mengubah status menjadi <b>LUNAS</b> akan otomatis menutup data piutang terkait transaksi ini jika ada.
                </p>
            </div>

            <div class="pt-4 flex items-center justify-end">
                <button type="submit" class="px-8 py-3 bg-gray-900 text-white text-sm font-bold rounded-xl shadow-lg hover:bg-gray-800 transition duration-200">
                    Update Transaksi
                </button>
            </div>
        </form>

        <script>
            function updateTotal() {
                const jumlah = parseInt(document.querySelector('input[name="jumlah_tabung"]').value) || 0;
                const harga = parseFloat(document.querySelector('input[name="harga_satuan"]').value) || 0;
                const total = jumlah * harga;
                document.querySelector('#total_display').textContent = 'Rp ' + total.toLocaleString('id-ID', { minimumFractionDigits: 0 });
            }

            document.querySelector('input[name="jumlah_tabung"]').addEventListener('input', updateTotal);
            document.querySelector('input[name="harga_satuan"]').addEventListener('input', updateTotal);

            // Initial calculation
            updateTotal();
        </script>
    </div>
</div>
@endsection
