@extends('layouts.admin')
@section('title', 'Retur Tabung - LPG Distribution')
@section('page_title', 'Manajemen Retur Tabung')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Retur Pending</p>
            <h3 class="text-xl font-black text-amber-600">{{ $returs->where('status_retur', 'pending')->count() }}</h3>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Retur Diterima</p>
            <h3 class="text-xl font-black text-emerald-600">{{ $returs->where('status_retur', 'diterima')->count() }}</h3>
        </div>
    </div>
    <a href="{{ route('retur.create') }}" class="inline-flex items-center px-6 py-3 bg-gray-900 text-white text-sm font-bold rounded-xl shadow-lg hover:bg-black transition">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Input Retur Baru
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full divide-y divide-gray-100">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Tanggal</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Truck / Driver</th>
                    <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase">Jumlah</th>
                    <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase">Kondisi</th>
                    <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase">Status</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Verifikasi</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($returs as $retur)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 text-xs font-bold text-gray-600">{{ $retur->tanggal_retur->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">
                        <p class="text-xs font-bold text-gray-800">{{ $retur->truck->nomor_polisi }}</p>
                        <p class="text-[10px] text-gray-400">{{ $retur->supir->nama }}</p>
                    </td>
                    <td class="px-6 py-4 text-center font-black text-gray-900">{{ $retur->jumlah_retur }}</td>
                    <td class="px-6 py-4 text-center">
                        @php
                            $cond = [
                                'baik' => 'bg-emerald-100 text-emerald-700',
                                'rusak' => 'bg-amber-100 text-amber-700',
                                'bocor' => 'bg-red-100 text-red-700',
                            ];
                        @endphp
                        <span class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $cond[$retur->kondisi_tabung] }}">
                            {{ $retur->kondisi_tabung }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @php
                            $st = [
                                'pending' => 'bg-amber-100 text-amber-700',
                                'diterima' => 'bg-emerald-500 text-white',
                                'ditolak' => 'bg-red-500 text-white',
                            ];
                        @endphp
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $st[$retur->status_retur] }}">
                            {{ $retur->status_retur }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($retur->verified_by)
                        <div class="flex items-center space-x-2">
                            <div class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center">
                                <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <span class="text-[10px] text-gray-500 font-medium">{{ $retur->verifier->name }}</span>
                        </div>
                        @else
                        <span class="text-[10px] text-gray-300 italic">Menunggu...</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($retur->status_retur == 'pending' && Auth::user()->can('edit stock'))
                        <form action="{{ route('retur.approve', $retur) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" onclick="return confirm('Terima retur ini? Stok gudang akan bertambah.')" class="px-3 py-1.5 bg-emerald-600 text-white text-[10px] font-bold rounded-lg hover:bg-emerald-700 transition">
                                TERIMA
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400 italic">Belum ada data retur.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
