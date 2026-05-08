@extends('layouts.admin')
@section('title', 'Piutang - LPG Distribution')
@section('page_title', 'Manajemen Piutang')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Daftar Piutang Pangkalan</h1>
    <p class="text-sm text-gray-500 mt-1">Pantau tagihan yang belum lunas dari transaksi kredit</p>
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" action="{{ route('piutang.index') }}" class="flex flex-col sm:flex-row gap-3">
        <select name="pangkalan_id" class="flex-1 px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-red-500 transition">
            <option value="">Semua Pangkalan</option>
            @foreach($pangkalans as $p)
                <option value="{{ $p->id }}" {{ request('pangkalan_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_pangkalan }}</option>
            @endforeach
        </select>
        <select name="status" class="px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-red-500 transition">
            <option value="">Semua Status</option>
            <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
            <option value="mencicil" {{ request('status') == 'mencicil' ? 'selected' : '' }}>Mencicil</option>
            <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
        </select>
        <button type="submit" class="px-6 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">Filter</button>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pangkalan</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ref Penjualan</th>
                    <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Tagihan</th>
                    <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Sisa Hutang</th>
                    <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                    <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($piutangs as $p)
                <tr class="hover:bg-red-50/20 transition">
                    <td class="px-6 py-4">
                        <p class="text-sm font-bold text-gray-800">{{ $p->pangkalan->nama_pangkalan }}</p>
                        <p class="text-xs text-gray-400">{{ $p->pangkalan->nama_pemilik }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('penjualan.show', $p->penjualan) }}" class="text-xs font-bold text-blue-600 hover:underline">
                            {{ $p->penjualan->nomor_invoice }}
                        </a>
                        <p class="text-[10px] text-gray-400 uppercase">{{ $p->penjualan->tanggal_penjualan->format('d/m/y') }}</p>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <p class="text-sm font-semibold text-gray-600">Rp {{ number_format($p->nominal_piutang, 0, ',', '.') }}</p>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <p class="text-sm font-black text-red-600">Rp {{ number_format($p->sisa_tagihan, 0, ',', '.') }}</p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <p class="text-xs font-bold {{ $p->tanggal_jatuh_tempo->isPast() ? 'text-red-500' : 'text-gray-700' }}">
                            {{ $p->tanggal_jatuh_tempo->format('d/m/Y') }}
                        </p>
                        @if($p->tanggal_jatuh_tempo->isPast() && $p->status_piutang != 'lunas')
                            <span class="text-[9px] text-red-600 font-black uppercase tracking-tighter">Terlambat!</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @php
                            $colors = [
                                'belum_bayar' => 'bg-red-100 text-red-700',
                                'mencicil' => 'bg-amber-100 text-amber-700',
                                'lunas' => 'bg-emerald-100 text-emerald-700',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $colors[$p->status_piutang] ?? 'bg-gray-100' }}">
                            {{ str_replace('_', ' ', $p->status_piutang) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-gray-500 italic">Tidak ada data piutang aktif.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($piutangs->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">{{ $piutangs->links() }}</div>
    @endif
</div>
@endsection
