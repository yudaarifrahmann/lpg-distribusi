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
                        <p class="text-sm font-bold text-gray-800">{{ $expense->category->nama_kategori }}</p>
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
            {{-- Verification Form (Admins only and only if pending) --}}
            @if(Auth::user()->can('edit pengeluaran') && $expense->status_verifikasi == 'pending')
            <div class="bg-gray-900 p-8 rounded-2xl shadow-xl text-white">
                <h3 class="text-sm font-bold uppercase tracking-widest mb-6 text-gray-400">Verifikasi Pengeluaran</h3>
                <form action="{{ route('expense.verify', $expense) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Tindakan</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="status" value="disetujui" class="hidden peer" checked>
                                <div class="py-3 text-center rounded-xl border border-gray-700 peer-checked:bg-emerald-600 peer-checked:border-emerald-500 transition font-bold text-xs">Setujui</div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="status" value="ditolak" class="hidden peer">
                                <div class="py-3 text-center rounded-xl border border-gray-700 peer-checked:bg-red-600 peer-checked:border-red-500 transition font-bold text-xs">Tolak</div>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Catatan Verifikasi</label>
                        <textarea name="catatan_verifikasi" rows="3" class="w-full bg-gray-800 border-gray-700 rounded-xl text-xs focus:ring-emerald-500" placeholder="Opsional..."></textarea>
                    </div>
                    <button type="submit" class="w-full py-4 bg-emerald-500 text-white font-black rounded-2xl shadow-lg shadow-emerald-500/20 hover:bg-emerald-600 transition">SIMPAN VERIFIKASI</button>
                </form>
            </div>
            @endif

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

            {{-- Quick Info for Supir --}}
            @if(Auth::user()->hasRole('supir_knek') && $expense->status_verifikasi == 'pending')
            <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100">
                <div class="flex items-center text-blue-700 mb-2 font-bold text-xs">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    Informasi
                </div>
                <p class="text-[10px] text-blue-600 leading-relaxed">
                    Pengeluaran Anda sedang dalam tahap antrian verifikasi. Harap tunggu hingga Admin Keuangan menyetujui klaim Anda.
                </p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
