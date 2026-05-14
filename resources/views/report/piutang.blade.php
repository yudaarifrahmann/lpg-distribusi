@extends('layouts.admin')
@section('title', 'Laporan Piutang - LPG Distribution')
@section('page_title', 'Laporan Rekap Piutang Pangkalan')

@section('content')
<div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Tagihan (Piutang)</p>
        <h3 class="text-2xl font-black text-gray-800">Rp {{ number_format($summary['total_piutang']) }}</h3>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Terbayar</p>
        <h3 class="text-2xl font-black text-emerald-600">Rp {{ number_format($summary['total_bayar']) }}</h3>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Sisa Piutang Aktif</p>
        <h3 class="text-2xl font-black text-red-600">Rp {{ number_format($summary['total_sisa']) }}</h3>
    </div>
</div>

{{-- Filters --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @if($isSuperAdmin)
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Cabang</label>
            <select name="branch_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs">
                <option value="">Semua Cabang</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ $selectedBranchId == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Pangkalan</label>
            <select name="pangkalan_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs">
                <option value="">Semua Pangkalan</option>
                @foreach($pangalans as $p)
                <option value="{{ $p->id }}" {{ request('pangkalan_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_pangkalan }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Status</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs">
                <option value="">Semua Status</option>
                <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                <option value="mencicil" {{ request('status') == 'mencicil' ? 'selected' : '' }}>Mencicil</option>
                <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
            </select>
        </div>
        <div class="flex items-end space-x-2">
            <button type="submit" class="flex-1 bg-gray-900 text-white font-bold py-2 rounded-lg text-xs hover:bg-black transition">Filter</button>
            <button type="button" onclick="window.print()" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2h2"></path></svg>
            </button>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-xs divide-y divide-gray-100">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Pangkalan</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">No. Piutang</th>
                    <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-400 uppercase">Tagihan</th>
                    <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-400 uppercase">Terbayar</th>
                    <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-400 uppercase">Sisa</th>
                    <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($piutangs as $p)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-6 py-4 font-bold text-gray-800">{{ $p->pangkalan->nama_pangkalan }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $p->nomor_piutang }}</td>
                    <td class="px-6 py-4 text-right font-bold">Rp {{ number_format($p->nominal_piutang) }}</td>
                    <td class="px-6 py-4 text-right text-emerald-600 font-bold">Rp {{ number_format($p->total_terbayar) }}</td>
                    <td class="px-6 py-4 text-right text-red-600 font-black">Rp {{ number_format($p->sisa_tagihan) }}</td>
                    <td class="px-6 py-4 text-center">
                        @php
                            $st = [
                                'belum_bayar' => 'bg-red-100 text-red-700',
                                'mencicil' => 'bg-amber-100 text-amber-700',
                                'lunas' => 'bg-emerald-100 text-emerald-700',
                            ];
                        @endphp
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $st[$p->status_piutang] }}">
                            {{ $p->status_piutang }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
