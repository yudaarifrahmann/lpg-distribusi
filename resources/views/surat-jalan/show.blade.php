@extends('layouts.admin')
@section('title', 'Detail SJ - LPG Distribution')
@section('page_title', 'Detail Surat Jalan')

@section('content')
<div class="max-w-5xl">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('surat-jalan.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
            Kembali
        </a>
        <div class="flex space-x-2">
            <a href="{{ route('surat-jalan.print', $suratJalan) }}" target="_blank" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-bold rounded-lg hover:bg-gray-200 transition flex items-center">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 2H7V4h6v2zM9 14v2H7v-2h2zm2 2v-2h2v2h-2z" clip-rule="evenodd"/></svg>
                Cetak SJ
            </a>
            @if($suratJalan->status_perjalanan != 'selesai')
                @can('edit surat jalan')
                <a href="{{ route('surat-jalan.edit', $suratJalan) }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">
                    Edit Status
                </a>
                @endcan
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column: Main Details --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 bg-gradient-to-r from-indigo-700 to-indigo-600 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-indigo-100 text-xs font-bold uppercase tracking-widest">Nomor Surat Jalan</p>
                            <h2 class="text-2xl font-black mt-1">{{ $suratJalan->nomor_surat_jalan }}</h2>
                        </div>
                        <div class="text-right">
                            <p class="text-indigo-100 text-xs font-bold uppercase tracking-widest">Status</p>
                            <span class="inline-flex mt-1 px-3 py-1 bg-white/20 backdrop-blur-md text-white text-xs font-bold rounded-full">
                                {{ strtoupper($suratJalan->status_perjalanan) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="p-8">
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-tighter mb-1">Tanggal Berangkat</p>
                            <p class="text-gray-800 font-bold">{{ $suratJalan->tanggal_berangkat->format('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-tighter mb-1">Volume Muatan</p>
                            <p class="text-indigo-600 font-black text-xl">{{ number_format($suratJalan->jumlah_tabung) }} <span class="text-xs font-normal text-gray-400">Tabung</span></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-tighter mb-1">Referensi DO</p>
                            <p class="text-gray-800 font-semibold">#{{ $suratJalan->penebusan->nomor_do }}</p>
                            <p class="text-[10px] text-gray-400">Tebus: {{ $suratJalan->penebusan->tanggal_penebusan->format('d/m/y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-tighter mb-1">Catatan</p>
                            <p class="text-gray-600 text-sm italic">{{ $suratJalan->catatan ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Personnel & Vehicle Card --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Informasi Armada & Kru</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                            </div>
                            <div>
                                <p class="text-gray-800 font-bold">{{ $suratJalan->truck->nomor_polisi }}</p>
                                <p class="text-xs text-gray-500 uppercase tracking-widest">{{ $suratJalan->truck->nama_truk }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-lg border border-emerald-100">KENDARAAN AKTIF</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 rounded-2xl">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Supir Utama</p>
                            <p class="text-sm font-bold text-gray-800">{{ $suratJalan->supir->nama }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-2xl">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Knek Pendamping</p>
                            <p class="text-sm font-bold text-gray-800">{{ $suratJalan->knek->nama ?? 'Tidak Ada' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Timeline --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                <div class="px-6 py-5 border-b border-gray-50 bg-gray-50/50">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Timeline Perjalanan</h3>
                </div>
                <div class="p-6">
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            {{-- Step 1: Persiapan --}}
                            <li>
                                <div class="relative pb-8">
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-indigo-200" aria-hidden="true"></span>
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm font-bold text-gray-900">Persiapan & Muat</p>
                                                <p class="text-xs text-gray-500">Stok telah dipindahkan dari gudang ke truk.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            {{-- Step 2: Berangkat --}}
                            <li>
                                <div class="relative pb-8">
                                    @if(in_array($suratJalan->status_perjalanan, ['selesai', 'retur']))
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-indigo-200" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            @if(in_array($suratJalan->status_perjalanan, ['berangkat', 'selesai', 'retur']))
                                            <span class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            </span>
                                            @else
                                            <span class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center ring-8 ring-white">
                                                <span class="w-2.5 h-2.5 rounded-full bg-gray-400"></span>
                                            </span>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm font-bold {{ $suratJalan->status_perjalanan == 'berangkat' ? 'text-indigo-600' : 'text-gray-400' }}">Berangkat</p>
                                                <p class="text-xs text-gray-500">Truk telah meninggalkan pangkalan.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            {{-- Step 3: Selesai / Retur --}}
                            <li>
                                <div class="relative pb-8">
                                    <div class="relative flex space-x-3">
                                        <div>
                                            @if($suratJalan->status_perjalanan == 'selesai')
                                            <span class="h-8 w-8 rounded-full bg-emerald-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            </span>
                                            @elseif($suratJalan->status_perjalanan == 'retur')
                                            <span class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                            </span>
                                            @else
                                            <span class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center ring-8 ring-white">
                                                <span class="w-2.5 h-2.5 rounded-full bg-gray-400"></span>
                                            </span>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm font-bold {{ in_array($suratJalan->status_perjalanan, ['selesai', 'retur']) ? ($suratJalan->status_perjalanan == 'selesai' ? 'text-emerald-600' : 'text-red-600') : 'text-gray-400' }}">
                                                    {{ $suratJalan->status_perjalanan == 'retur' ? 'Selesai (Retur)' : 'Selesai / Terkirim' }}
                                                </p>
                                                <p class="text-xs text-gray-500">Seluruh tabung telah didistribusikan / diproses.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
