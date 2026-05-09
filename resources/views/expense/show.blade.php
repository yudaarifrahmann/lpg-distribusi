@extends('layouts.admin')
@section('title', 'Detail Pengeluaran - LPG Distribution')
@section('page_title', 'Detail Pengeluaran Operasional')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('expense.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition font-bold mb-2">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
                Kembali ke Daftar
            </a>
            <h1 class="text-2xl font-bold text-gray-800">{{ $expense->nama_pengeluaran }}</h1>
        </div>
        
        <div class="flex items-center space-x-3">
            @php
                $st = [
                    'pending' => 'bg-amber-100 text-amber-700',
                    'disetujui' => 'bg-emerald-100 text-emerald-700',
                    'ditolak' => 'bg-red-100 text-red-700',
                ];
            @endphp
            <span class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider {{ $st[$expense->status_verifikasi] }}">
                {{ $expense->status_verifikasi }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Details --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-8 border-b border-gray-50 pb-4">Rincian Pengeluaran</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Kategori</p>
                        <p class="text-sm font-bold text-gray-800">{{ $expense->category ? $expense->category->nama_kategori : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Tanggal</p>
                        <p class="text-sm font-bold text-gray-800">{{ $expense->tanggal_pengeluaran->format('l, d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Nominal</p>
                        <p class="text-2xl font-black text-gray-900">Rp {{ number_format($expense->nominal) }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Metode Pembayaran</p>
                        <span class="px-3 py-1 bg-gray-100 rounded-full text-[10px] font-black uppercase text-gray-600">{{ $expense->metode_pembayaran }}</span>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-gray-50">
                    <p class="text-[10px] font-bold text-gray-400 uppercase mb-2">Keterangan</p>
                    <p class="text-sm text-gray-600 leading-relaxed italic">"{{ $expense->keterangan ?? 'Tidak ada keterangan.' }}"</p>
                </div>
            </div>

            {{-- Photo Gallery --}}
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Bukti Nota / Lampiran ({{ $expense->attachments->count() }})
                </h3>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @foreach($expense->attachments as $file)
                    <a href="{{ Storage::url($file->path_file) }}" target="_blank" class="group relative aspect-square rounded-2xl overflow-hidden bg-gray-100 border border-gray-100 hover:shadow-xl transition duration-300">
                        <img src="{{ Storage::url($file->path_file) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                        </div>
                    </a>
                    @endforeach
                    @if($expense->attachments->isEmpty())
                    <div class="col-span-full py-12 flex flex-col items-center justify-center text-gray-300 border-2 border-dashed border-gray-100 rounded-2xl">
                        <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-xs font-bold uppercase tracking-wider">Tanpa Lampiran Nota</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right: Verification & Audit --}}
        <div class="space-y-6">


            {{-- Audit Trail --}}
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6">Audit Trail</h3>
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center mr-3 shrink-0">
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Diajukan Oleh</p>
                            <p class="text-xs font-bold text-gray-800">{{ $expense->user->name }}</p>
                            <p class="text-[10px] text-gray-400">{{ $expense->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    @if($expense->verified_by)
                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center mr-3 shrink-0">
                            <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Diverifikasi Oleh</p>
                            <p class="text-xs font-bold text-gray-800">{{ $expense->verifier->name }}</p>
                            <p class="text-[10px] text-gray-400">{{ $expense->verified_at->format('d/m/Y H:i') }}</p>
                            @if($expense->catatan_verifikasi)
                            <div class="mt-2 p-2 bg-gray-50 rounded-lg text-[10px] italic text-gray-500 border border-gray-100">
                                "{{ $expense->catatan_verifikasi }}"
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>


        </div>
    </div>
</div>
@endsection
