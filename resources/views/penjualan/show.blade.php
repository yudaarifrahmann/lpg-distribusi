@extends('layouts.admin')
@section('title', 'Invoice - LPG Distribution')
@section('page_title', 'Detail Penjualan')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between no-print">
        <a href="{{ route('penjualan.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition font-bold">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
            Kembali
        </a>
        <a href="{{ route('penjualan.print', $penjualan) }}" target="_blank" class="px-6 py-2 bg-gray-800 text-white text-sm font-bold rounded-xl hover:bg-gray-700 transition shadow-lg flex items-center">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 2H7V4h6v2zM9 14v2H7v-2h2zm2 2v-2h2v2h-2z" clip-rule="evenodd"/></svg>
            Cetak Invoice
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden relative">
        {{-- Watermark Status --}}
        <div class="absolute top-10 right-[-40px] transform rotate-45 px-16 py-1 opacity-20 border-y-4 border-emerald-500 text-emerald-600 font-black text-4xl pointer-events-none uppercase">
            {{ $penjualan->status_pembayaran }}
        </div>

        <div class="p-8 sm:p-12">
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row justify-between mb-12 gap-8">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tighter">INVOICE</h2>
                    <p class="text-gray-500 font-bold uppercase tracking-widest text-xs mt-1">LPG Distribution System</p>
                    <div class="mt-8 space-y-1">
                        <p class="text-sm font-bold text-gray-800">{{ $penjualan->nomor_invoice }}</p>
                        <p class="text-xs text-gray-500">Tanggal Transaksi: {{ $penjualan->tanggal_penjualan->format('d F Y') }}</p>
                    </div>
                </div>
                <div class="text-sm sm:text-right">
                    <p class="text-gray-400 font-bold uppercase tracking-widest text-[10px] mb-2">Ditujukan Kepada:</p>
                    <p class="text-xl font-black text-gray-800 uppercase">{{ $penjualan->pangkalan->nama_pangkalan }}</p>
                    <p class="text-gray-500 mt-1">{{ $penjualan->pangkalan->alamat }}</p>
                    <p class="text-gray-500">{{ $penjualan->pangkalan->no_hp }}</p>
                </div>
            </div>

            {{-- Table --}}
            <div class="mb-12">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-900">
                            <th class="py-4 text-left text-xs font-black uppercase tracking-widest text-gray-500">Deskripsi Barang</th>
                            <th class="py-4 text-center text-xs font-black uppercase tracking-widest text-gray-500">Qty</th>
                            <th class="py-4 text-right text-xs font-black uppercase tracking-widest text-gray-500">Harga Satuan</th>
                            <th class="py-4 text-right text-xs font-black uppercase tracking-widest text-gray-500">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="py-6">
                                <p class="font-bold text-gray-800">LPG 3Kg (Isi Ulang)</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">Harga: {{ $penjualan->lpgPrice->nama_harga }}</p>
                            </td>
                            <td class="py-6 text-center font-bold text-gray-800">{{ $penjualan->jumlah_tabung }} Pcs</td>
                            <td class="py-6 text-right font-medium text-gray-600">Rp {{ number_format($penjualan->harga_satuan, 0, ',', '.') }}</td>
                            <td class="py-6 text-right font-black text-gray-900">Rp {{ number_format($penjualan->total_penjualan, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Totals --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-8 border-t-2 border-gray-100 pt-8">
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Metode Pembayaran</p>
                        <div class="inline-flex items-center px-3 py-1 bg-gray-100 rounded-lg text-sm font-black uppercase">
                            {{ $penjualan->metode_pembayaran }}
                        </div>
                    </div>
                    @if($penjualan->metode_pembayaran == 'utang' && $penjualan->piutang)
                    <div>
                        <p class="text-[10px] text-red-400 font-bold uppercase tracking-widest mb-1">Jatuh Tempo</p>
                        <p class="text-sm font-bold text-red-600">{{ $penjualan->piutang->tanggal_jatuh_tempo->format('d M Y') }}</p>
                    </div>
                    @endif
                </div>
                <div class="w-full sm:w-64 space-y-3">
                    <div class="flex justify-between items-center text-gray-500">
                        <span class="text-xs font-bold uppercase tracking-widest">Total Belanja</span>
                        <span class="font-bold">Rp {{ number_format($penjualan->total_penjualan, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-gray-500">
                        <span class="text-xs font-bold uppercase tracking-widest">Pajak (0%)</span>
                        <span class="font-bold">Rp 0</span>
                    </div>
                    <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                        <span class="text-sm font-black uppercase tracking-tighter text-gray-900">Total Akhir</span>
                        <span class="text-2xl font-black text-emerald-600">Rp {{ number_format($penjualan->total_penjualan, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Footer Info --}}
            <div class="mt-20 pt-8 border-t border-gray-50 text-center">
                <p class="text-xs text-gray-400 font-medium italic">"Terima kasih telah berlangganan. Pastikan segel tabung dalam keadaan utuh."</p>
                <div class="mt-8 grid grid-cols-2 gap-8 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    <div class="text-left">
                        Petugas Pengirim:<br>
                        <span class="text-gray-800">{{ $penjualan->supir->nama }} ({{ $penjualan->truck->nomor_polisi }})</span>
                    </div>
                    <div class="text-right">
                        Invoice ID:<br>
                        <span class="text-gray-800">REF-{{ $penjualan->suratJalan->nomor_surat_jalan }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print { display: none !important; }
        body { background: white; }
        .bg-white { box-shadow: none !important; border: none !important; }
    }
</style>
@endsection
