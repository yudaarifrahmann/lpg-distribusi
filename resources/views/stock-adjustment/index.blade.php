@extends('layouts.admin')
@section('title', 'Penyesuaian Stok - LPG Distribution')
@section('page_title', 'Stock Adjustment (SuperAdmin Only)')

@section('content')
<div class="mb-8 flex justify-end">
    <a href="{{ route('stock-adjustment.create') }}" class="px-6 py-3 bg-red-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-red-500/30 hover:bg-red-700 transition">
        Buat Penyesuaian Manual
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full divide-y divide-gray-100">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Tanggal</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Lokasi</th>
                    <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase">Sebelum</th>
                    <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase">Setelah</th>
                    <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase">Selisih</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Alasan</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">User</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($adjustments as $adj)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 text-xs font-bold text-gray-600">{{ $adj->tanggal_adjustment->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-gray-100 text-[10px] font-black uppercase text-gray-500 rounded">
                            {{ $adj->lokasi_stok }} {{ $adj->truck_id ? '(' . $adj->truck->nomor_polisi . ')' : '' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center text-xs text-gray-500 font-bold">{{ $adj->stok_sebelum }}</td>
                    <td class="px-6 py-4 text-center text-xs text-gray-900 font-black">{{ $adj->stok_setelah }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($adj->selisih > 0)
                        <span class="text-xs font-black text-emerald-600">+{{ $adj->selisih }}</span>
                        @elseif($adj->selisih < 0)
                        <span class="text-xs font-black text-red-600">{{ $adj->selisih }}</span>
                        @else
                        <span class="text-xs text-gray-400">0</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-xs text-gray-600 max-w-xs truncate">{{ $adj->alasan_penyesuaian }}</td>
                    <td class="px-6 py-4 text-xs text-gray-500">{{ $adj->user->name }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400 italic">Belum ada riwayat penyesuaian stok.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
