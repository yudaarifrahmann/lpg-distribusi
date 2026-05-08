@extends('layouts.admin')
@section('title', 'Stok Gudang - LPG Distribution')
@section('page_title', 'Stok Gudang Realtime')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Summary Card --}}
    <div class="lg:col-span-1">
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center">
            <div class="p-5 bg-blue-50 rounded-2xl text-blue-600 mb-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
            </div>
            <p class="text-gray-500 font-medium">Total Stok Saat Ini</p>
            <h2 class="text-5xl font-extrabold text-gray-800 mt-2">{{ number_format($summary->stok_saat_ini) }}</h2>
            <p class="text-sm text-gray-400 mt-2">Tabung LPG 3Kg</p>
            
            <div class="mt-8 w-full border-t border-gray-50 pt-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center">
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Terakhir Update</p>
                        <p class="text-sm font-semibold text-gray-700">{{ $summary->updated_at->diffForHumans() }}</p>
                    </div>
                    <div class="text-center border-l border-gray-50">
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Status</p>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-emerald-100 text-emerald-700">NORMAL</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- History Timeline --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-50 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">Mutasi Stok</h3>
                <div class="flex items-center space-x-2">
                    <button class="p-2 hover:bg-gray-50 rounded-lg text-gray-400" title="Filter"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 01-.707.293V19a1 1 0 01-.447.894l-4 2A1 1 0 019 21v-7.414a1 1 0 01-.293-.707L2.293 7.586A1 1 0 012 6.586V4z"></path></svg></button>
                </div>
            </div>
            
            <div class="p-6">
                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        @forelse($histories as $h)
                        <li>
                            <div class="relative pb-8">
                                @if(!$loop->last)
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-100" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white {{ $h->stok_masuk > 0 ? 'bg-emerald-500' : 'bg-red-500' }}">
                                            @if($h->stok_masuk > 0)
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                            @else
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0 flex justify-between space-x-4 pt-1.5">
                                        <div>
                                            <p class="text-sm text-gray-500">{{ $h->keterangan }} <span class="font-bold text-gray-900">#{{ $h->referensi }}</span></p>
                                            <p class="text-xs text-gray-400 mt-1 uppercase tracking-tighter">{{ ucfirst($h->jenis_transaksi) }}</p>
                                        </div>
                                        <div class="text-right whitespace-nowrap">
                                            <p class="text-sm font-bold {{ $h->stok_masuk > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                                {{ $h->stok_masuk > 0 ? '+' : '-' }}{{ number_format(max($h->stok_masuk, $h->stok_keluar)) }}
                                            </p>
                                            <time datetime="{{ $h->tanggal }}" class="text-xs text-gray-400">{{ $h->tanggal->format('d M Y') }}</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="text-center py-12 text-gray-400 italic">Belum ada mutasi stok.</li>
                        @endforelse
                    </ul>
                </div>
                
                <div class="mt-8">
                    {{ $histories->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
