@extends('layouts.admin')
@section('title', 'Detail Piutang - LPG Distribution')
@section('page_title', 'Detail Tagihan Pangkalan')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('piutang.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition font-bold mb-2">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
                Kembali ke Daftar
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Piutang: {{ $piutang->nomor_piutang ?? $piutang->penjualan->nomor_invoice }}</h1>
        </div>
        <div class="flex items-center space-x-3">
            @if($piutang->status_piutang != 'lunas')
            <button @click="$dispatch('open-modal', 'modal-bayar')" class="px-6 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-500/30 hover:bg-emerald-700 transition duration-200">
                Catat Pembayaran
            </button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Stats & Info --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Status Card --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Status Tagihan</p>
                        @php
                            $colors = [
                                'belum_bayar' => 'bg-red-100 text-red-700',
                                'mencicil' => 'bg-amber-100 text-amber-700',
                                'lunas' => 'bg-emerald-100 text-emerald-700',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider {{ $colors[$piutang->status_piutang] ?? 'bg-gray-100' }}">
                            {{ str_replace('_', ' ', $piutang->status_piutang) }}
                        </span>
                    </div>
                    @if($piutang->tanggal_jatuh_tempo->isPast() && $piutang->status_piutang != 'lunas')
                    <div class="px-3 py-1 bg-red-500 text-white text-[10px] font-bold rounded-lg animate-pulse">OVERDUE</div>
                    @endif
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Progress Pelunasan</p>
                        <div class="mt-2 w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                            @php $percent = ($piutang->total_terbayar / $piutang->nominal_piutang) * 100; @endphp
                            <div class="bg-emerald-500 h-2.5 transition-all duration-1000" style="width: {{ $percent }}%"></div>
                        </div>
                        <p class="text-right text-[10px] font-bold text-gray-500 mt-1">{{ number_format($percent, 1) }}% Terbayar</p>
                    </div>

                    <div class="pt-4 border-t border-gray-50 space-y-3">
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-500">Total Tagihan</span>
                            <span class="text-sm font-bold text-gray-800">Rp {{ number_format($piutang->nominal_piutang) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-500">Sudah Dibayar</span>
                            <span class="text-sm font-bold text-emerald-600">Rp {{ number_format($piutang->total_terbayar) }}</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-dashed border-gray-200">
                            <span class="text-xs font-bold text-gray-700">Sisa Hutang</span>
                            <span class="text-lg font-black text-red-600">Rp {{ number_format($piutang->sisa_tagihan) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detail Pangkalan --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Informasi Pangkalan</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-bold text-gray-800">{{ $piutang->pangkalan->nama_pangkalan }}</p>
                        <p class="text-[10px] text-gray-500">{{ $piutang->pangkalan->alamat }}</p>
                    </div>
                    <div class="flex items-center text-xs text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                        {{ $piutang->pangkalan->no_hp }}
                    </div>
                    <div class="pt-4 border-t border-gray-50">
                        <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Referensi Pengiriman</p>
                        <p class="text-xs font-medium text-gray-700">{{ $piutang->penjualan->suratJalan->truck->nomor_polisi }} | {{ $piutang->penjualan->supir->nama }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: History --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Riwayat Utang Awal</h3>
                </div>
                <div class="p-6">
                    <div class="rounded-2xl border border-red-100 bg-red-50/40 p-5">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                            <div>
                                <p class="text-[10px] font-black text-red-500 uppercase tracking-widest mb-1">Transaksi Utang</p>
                                <a href="{{ route('penjualan.show', $piutang->penjualan) }}" class="text-sm font-black text-blue-600 hover:underline">
                                    {{ $piutang->penjualan->nomor_invoice }}
                                </a>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $piutang->penjualan->tanggal_penjualan->format('d/m/Y') }} • {{ $piutang->penjualan->jumlah_tabung }} tabung • {{ strtoupper($piutang->penjualan->metode_pembayaran) }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $piutang->penjualan->suratJalan->nomor_surat_jalan }} • {{ $piutang->penjualan->suratJalan->truck->nomor_polisi }}
                                </p>
                            </div>
                            <div class="text-left md:text-right">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nominal Utang Awal</p>
                                <p class="text-2xl font-black text-red-600">Rp {{ number_format($piutang->nominal_piutang) }}</p>
                                <p class="text-[10px] text-gray-500 mt-1">Data ini tetap tersimpan walaupun cicilan masuk atau sudah lunas.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Riwayat Pembayaran / Cicilan</h3>
                </div>
                <div class="p-0 overflow-x-auto custom-scrollbar">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-400 uppercase whitespace-nowrap">Tanggal</th>
                                <th class="px-6 py-3 text-right text-[10px] font-bold text-gray-400 uppercase whitespace-nowrap">Nominal</th>
                                <th class="px-6 py-3 text-center text-[10px] font-bold text-gray-400 uppercase whitespace-nowrap">Metode</th>
                                <th class="px-6 py-3 text-center text-[10px] font-bold text-gray-400 uppercase whitespace-nowrap">Status</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-400 uppercase whitespace-nowrap">Input Oleh</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-400 uppercase whitespace-nowrap">Keterangan</th>
                                <th class="px-6 py-3 text-center text-[10px] font-bold text-gray-400 uppercase whitespace-nowrap">Bukti</th>
                                @hasanyrole('superadmin|admin_keuangan')
                                <th class="px-6 py-3 text-center text-[10px] font-bold text-gray-400 uppercase whitespace-nowrap">Verifikasi</th>
                                @endhasanyrole
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            {{-- 1. Initial Payment from Penjualan --}}
                            @if($piutang->penjualan->nominal_cash > 0 || $piutang->penjualan->nominal_transfer > 0)
                                {{-- Initial Cash --}}
                                @if($piutang->penjualan->nominal_cash > 0)
                                <tr class="bg-gray-50/30 hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-xs font-bold text-gray-700 whitespace-nowrap">{{ $piutang->penjualan->tanggal_penjualan->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 text-xs text-right font-black text-emerald-600 whitespace-nowrap">Rp {{ number_format($piutang->penjualan->nominal_cash) }}</td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <span class="text-[10px] font-bold uppercase text-gray-500">CASH (AWAL)</span>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase">Verified</span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-500 whitespace-nowrap">{{ $piutang->penjualan->supir->nama ?? 'Sistem' }}</td>
                                    <td class="px-6 py-4 text-xs text-gray-500 italic whitespace-nowrap">Setoran tunai awal saat pengiriman</td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap"><span class="text-gray-300">-</span></td>
                                    @hasanyrole('superadmin|admin_keuangan')
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 text-emerald-700">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3.25-3.25a1 1 0 111.414-1.414l2.543 2.543 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        </span>
                                    </td>
                                    @endhasanyrole
                                </tr>
                                @endif

                                {{-- Initial Transfer --}}
                                @if($piutang->penjualan->nominal_transfer > 0)
                                <tr class="bg-blue-50/20 hover:bg-blue-50/40 transition border-l-4 border-blue-400">
                                    <td class="px-6 py-4 text-xs font-bold text-gray-700 whitespace-nowrap">{{ $piutang->penjualan->tanggal_penjualan->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 text-xs text-right font-black text-blue-600 whitespace-nowrap">Rp {{ number_format($piutang->penjualan->nominal_transfer) }}</td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <span class="text-[10px] font-bold uppercase text-blue-600">TRANSFER (AWAL)</span>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        @if($piutang->penjualan->status_transfer == 'pending')
                                        <span class="inline-flex items-center px-2 py-1 rounded-lg bg-amber-100 text-amber-700 text-[10px] font-black uppercase animate-pulse">Pending</span>
                                        @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase">Verified</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-500 whitespace-nowrap">{{ $piutang->penjualan->supir->nama ?? 'Sistem' }}</td>
                                    <td class="px-6 py-4 text-xs text-gray-500 italic whitespace-nowrap">Transfer awal saat pengiriman</td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap"><span class="text-gray-300">-</span></td>
                                    @hasanyrole('superadmin|admin_keuangan')
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        @if($piutang->penjualan->status_transfer == 'pending')
                                            @hasanyrole('superadmin|admin_keuangan')
                                            <form action="{{ route('penjualan.verify-transfer', $piutang->penjualan) }}" method="POST" onsubmit="return confirm('Verifikasi transfer awal ini?')">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white hover:bg-blue-700 transition shadow-lg shadow-blue-500/30" title="Verifikasi transfer awal">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3.25-3.25a1 1 0 111.414-1.414l2.543 2.543 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                </button>
                                            </form>
                                            @else
                                            <span class="text-amber-500 text-[10px] font-bold">Pending</span>
                                            @endhasanyrole
                                        @elseif($piutang->penjualan->status_transfer == 'verified')
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 text-emerald-700">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3.25-3.25a1 1 0 111.414-1.414l2.543 2.543 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        </span>
                                        @else
                                        <span class="text-gray-300">-</span>
                                        @endif
                                    </td>
                                    @endhasanyrole
                                </tr>
                                @endif
                            @endif

                            {{-- 2. Regular Installments (Pembayarans) --}}
                            @forelse($piutang->pembayarans as $bayar)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4 text-xs font-bold text-gray-700 whitespace-nowrap">{{ $bayar->tanggal_pembayaran->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-xs text-right font-black text-emerald-600 whitespace-nowrap">Rp {{ number_format($bayar->nominal_pembayaran) }}</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span class="text-[10px] font-bold uppercase text-gray-500">{{ $bayar->metode_pembayaran }}</span>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    @if($bayar->status_verifikasi == 'pending')
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg bg-amber-100 text-amber-700 text-[10px] font-black uppercase">Pending</span>
                                    @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase">Verified</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-500 whitespace-nowrap">{{ $bayar->user->name }}</td>
                                <td class="px-6 py-4 text-xs text-gray-500 whitespace-nowrap">{{ $bayar->keterangan ?? '-' }}</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    @if($bayar->bukti_pembayaran)
                                    <a href="{{ Storage::url($bayar->bukti_pembayaran) }}" target="_blank" class="text-blue-500 hover:text-blue-700">
                                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </a>
                                    @else
                                    <span class="text-gray-300">-</span>
                                    @endif
                                </td>
                                @hasanyrole('superadmin|admin_keuangan')
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    @if($bayar->status_verifikasi == 'pending')
                                        @hasanyrole('superadmin|admin_keuangan')
                                        <form action="{{ route('pembayaran-piutang.verify', $bayar) }}" method="POST" onsubmit="return confirm('Tandai transfer ini sudah masuk dan verifikasi pembayaran?')">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-600 text-white hover:bg-emerald-700 transition shadow-lg shadow-emerald-500/30" title="Verifikasi transfer">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3.25-3.25a1 1 0 111.414-1.414l2.543 2.543 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            </button>
                                        </form>
                                        @else
                                        <span class="text-amber-500 text-[10px] font-bold italic">Pending Verification</span>
                                        @endhasanyrole
                                    @elseif($bayar->status_verifikasi == 'verified')
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 text-emerald-700">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3.25-3.25a1 1 0 111.414-1.414l2.543 2.543 6.543-6.543a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    </span>
                                    @else
                                    <span class="text-gray-300">-</span>
                                    @endif
                                </td>
                                @endhasanyrole
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-400 italic text-sm">Belum ada riwayat cicilan tambahan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Pembayaran --}}
<div x-data="{ 
        open: false,
        sisa: {{ (int) $piutang->sisa_tagihan }},
        payments: [
            { aktif: true, metode: 'cash', nominal: 0 },
            { aktif: false, metode: 'transfer', nominal: 0 },
        ],
        formatRupiah(amount) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount || 0);
        },
        selectedTotal() {
            return this.payments.reduce((total, item) => total + (item.aktif ? (Number(item.nominal) || 0) : 0), 0);
        },
        verifiedTotal() {
            return this.payments.reduce((total, item) => total + (item.aktif && item.metode === 'cash' ? (Number(item.nominal) || 0) : 0), 0);
        },
        pendingTotal() {
            return this.payments.reduce((total, item) => total + (item.aktif && item.metode === 'transfer' ? (Number(item.nominal) || 0) : 0), 0);
        },
        sisaSetelahBayar() {
            return Math.max(this.sisa - this.verifiedTotal(), 0);
        },
     }" 
     x-show="open" 
     @open-modal.window="if($event.detail === 'modal-bayar') open = true"
     @close-modal.window="open = false"
     class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/50 transition-opacity" @click="open = false"></div>
        
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden max-w-md w-full z-50 transform transition-all">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Catat Pembayaran</h3>
                <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form action="{{ route('pembayaran-piutang.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="piutang_id" value="{{ $piutang->id }}">
                
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tanggal Bayar</label>
                    <input type="date" name="tanggal_pembayaran" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500">
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold text-gray-500 uppercase">Multi Pembayaran</label>
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Centang yang dipakai</span>
                    </div>

                    <div class="rounded-2xl border border-gray-100 p-4 space-y-3">
                        <label class="flex items-start gap-3">
                            <input type="hidden" name="payments[0][metode_pembayaran]" value="cash">
                            <input type="hidden" name="payments[0][aktif]" value="0">
                            <input type="checkbox" name="payments[0][aktif]" value="1" x-model="payments[0].aktif" class="mt-3 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                            <div class="flex-1">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-sm font-black text-gray-800 uppercase">Cash</span>
                                    <span class="text-[10px] font-bold text-emerald-600 uppercase">Langsung masuk</span>
                                </div>
                                <input type="number" name="payments[0][nominal_pembayaran]" min="1" max="{{ $piutang->sisa_tagihan }}" x-model.number="payments[0].nominal" :required="payments[0].aktif" :disabled="!payments[0].aktif" placeholder="Nominal cash" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-xl text-sm font-bold text-emerald-600 focus:ring-2 focus:ring-emerald-500 disabled:bg-gray-50 disabled:text-gray-300">
                                <input type="hidden" name="payments[0][keterangan]" value="Pembayaran cash">
                            </div>
                        </label>
                    </div>

                    <div class="rounded-2xl border border-gray-100 p-4 space-y-3">
                        <label class="flex items-start gap-3">
                            <input type="hidden" name="payments[1][metode_pembayaran]" value="transfer">
                            <input type="hidden" name="payments[1][aktif]" value="0">
                            <input type="checkbox" name="payments[1][aktif]" value="1" x-model="payments[1].aktif" class="mt-3 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <div class="flex-1">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-sm font-black text-gray-800 uppercase">Transfer</span>
                                    <span class="text-[10px] font-bold text-amber-600 uppercase">Pending verifikasi</span>
                                </div>
                                <input type="number" name="payments[1][nominal_pembayaran]" min="1" x-model.number="payments[1].nominal" :required="payments[1].aktif" :disabled="!payments[1].aktif" placeholder="Nominal transfer" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-xl text-sm font-bold text-blue-600 focus:ring-2 focus:ring-blue-500 disabled:bg-gray-50 disabled:text-gray-300">
                                <input type="file" name="payments[1][bukti_pembayaran]" :disabled="!payments[1].aktif" class="mt-2 w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 disabled:opacity-50">
                                <input type="hidden" name="payments[1][keterangan]" value="Transfer menunggu verifikasi">
                            </div>
                        </label>
                    </div>
                </div>

                <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4 space-y-2">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Sisa Sebelum Bayar</span>
                        <span class="font-bold text-gray-800">Rp {{ number_format($piutang->sisa_tagihan) }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Cash Langsung Masuk</span>
                        <span class="font-bold text-emerald-600" x-text="formatRupiah(verifiedTotal())"></span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Transfer Pending</span>
                        <span class="font-bold text-amber-600" x-text="formatRupiah(pendingTotal())"></span>
                    </div>
                    <div class="flex justify-between text-sm pt-2 border-t border-dashed border-gray-200">
                        <span class="font-bold text-gray-700">Sisa Setelah Pembayaran Terverifikasi</span>
                        <span class="font-black text-red-600" x-text="formatRupiah(sisaSetelahBayar())"></span>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-3 bg-gray-900 text-white font-bold rounded-2xl hover:bg-black transition shadow-xl">
                        SIMPAN PEMBAYARAN
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
