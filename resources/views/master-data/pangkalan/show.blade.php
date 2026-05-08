@extends('layouts.admin')
@section('title', 'Detail Pangkalan - LPG Distribution')
@section('page_title', 'Detail Pangkalan')

@section('content')
<div class="max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('pangkalan.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
            Kembali ke Data Pangkalan
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 bg-gradient-to-r from-blue-600 to-blue-500 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-white">{{ $pangkalan->nama_pangkalan }}</h2>
                <p class="text-blue-100 text-sm mt-0.5">ID: #{{ $pangkalan->id }}</p>
            </div>
            @if($pangkalan->status == 'aktif')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white border border-white/30"><span class="w-2 h-2 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span>Aktif</span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/20 text-white border border-white/30"><span class="w-2 h-2 rounded-full bg-red-400 mr-1.5"></span>Nonaktif</span>
            @endif
        </div>
        <div class="p-6 space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Nama Pangkalan</label>
                    <p class="mt-1 text-sm font-semibold text-gray-800">{{ $pangkalan->nama_pangkalan }}</p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Nama Pemilik</label>
                    <p class="mt-1 text-sm font-semibold text-gray-800">{{ $pangkalan->nama_pemilik }}</p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">No HP</label>
                    <p class="mt-1 text-sm font-semibold text-gray-800">{{ $pangkalan->no_hp }}</p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Status</label>
                    <p class="mt-1">
                        @if($pangkalan->status == 'aktif')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Aktif</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Nonaktif</span>
                        @endif
                    </p>
                </div>
            </div>
            <div>
                <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Alamat</label>
                <p class="mt-1 text-sm text-gray-800">{{ $pangkalan->alamat }}</p>
            </div>
            <div class="grid grid-cols-2 gap-5 pt-4 border-t border-gray-100">
                <div>
                    <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Dibuat</label>
                    <p class="mt-1 text-sm text-gray-600">{{ $pangkalan->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-400 uppercase tracking-wider">Diperbarui</label>
                    <p class="mt-1 text-sm text-gray-600">{{ $pangkalan->updated_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
