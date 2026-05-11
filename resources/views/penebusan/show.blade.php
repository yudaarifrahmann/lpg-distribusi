@extends('layouts.admin')
@section('title', 'Detail Penebusan - LPG Distribution')
@section('page_title', 'Detail Penebusan DO')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('penebusan.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-6">
            {{-- Main Info --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 bg-emerald-600">
                    <h3 class="text-xl font-bold text-white">DO #{{ $penebusan->nomor_do }}</h3>
                    <p class="text-emerald-100 text-sm mt-1">Berhasil dicatat pada {{ $penebusan->created_at->format('d M Y H:i') }}</p>
                </div>
                <div class="p-6 grid grid-cols-2 gap-y-6 gap-x-4 border-b border-gray-50">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-tighter">Tanggal Penebusan</p>
                        <p class="text-sm font-bold text-gray-800">{{ $penebusan->tanggal_penebusan->format('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-tighter">Jumlah Tabung</p>
                        <p class="text-sm font-bold text-gray-800">{{ number_format($penebusan->jumlah_tabung) }} Tabung</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-tighter">Harga per DO</p>
                        <p class="text-sm font-bold text-gray-800">Rp {{ number_format($penebusan->harga_per_do, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-tighter">Total Bayar</p>
                        <p class="text-sm font-bold text-emerald-600">Rp {{ number_format($penebusan->total_penebusan, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-tighter mb-2">Relasi SA</p>
                        <a href="{{ route('schedule-agreement.show', $penebusan->scheduleAgreement) }}" class="inline-flex items-center px-3 py-2 bg-blue-50 text-blue-700 text-xs font-bold rounded-lg hover:bg-blue-100 transition">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            SA Tanggal {{ $penebusan->scheduleAgreement->tanggal_sa->format('d/m/Y') }}
                        </a>
                    </div>
                </div>
            </div>

            {{-- Armada Info --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Informasi Pengambilan</h3>
                </div>
                <div class="p-6 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-tighter">Truk / Armada</p>
                        <p class="text-sm font-bold text-gray-800 mt-1">{{ $penebusan->truck->nomor_polisi }}</p>
                        <p class="text-xs text-gray-500">{{ $penebusan->truck->nama_truk }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-tighter">Supir / Personil</p>
                        <p class="text-sm font-bold text-gray-800 mt-1">{{ $penebusan->driver->nama }}</p>
                        <p class="text-xs text-gray-500">{{ ucfirst($penebusan->driver->role_pekerjaan) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Nota Preview --}}
        <div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Foto Nota Penebusan</h3>
                    @if($penebusan->foto_nota)
                        <a href="{{ asset('storage/' . $penebusan->foto_nota) }}" target="_blank" class="text-xs text-blue-600 hover:underline">Lihat Full</a>
                    @endif
                </div>
                <div class="p-6">
                    @if($penebusan->foto_nota)
                        <img src="{{ asset('storage/' . $penebusan->foto_nota) }}" class="w-full h-auto rounded-xl shadow-inner border border-gray-100" alt="Nota Penebusan">
                    @else
                        <div class="aspect-square bg-gray-50 rounded-xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center text-center p-8">
                            <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <p class="text-sm text-gray-400">Tidak ada foto nota tersedia</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
