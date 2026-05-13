@extends('layouts.admin')
@section('title', 'Detail SA - LPG Distribution')
@section('page_title', 'Detail Schedule Agreement')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('schedule-agreement.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
            Kembali
        </a>
        <div class="flex space-x-2">
            @if($scheduleAgreement->status_sa == 'pending')
            <a href="{{ route('penebusan.create', ['sa_id' => $scheduleAgreement->id]) }}" class="px-4 py-2 bg-emerald-600 text-white text-sm font-bold rounded-lg hover:bg-emerald-700 transition shadow-lg shadow-emerald-500/30">
                Tebus DO
            </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- SA Summary --}}
        <div class="md:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Informasi SA</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-tighter">Tanggal SA</p>
                        <p class="text-lg font-bold text-gray-800">{{ $scheduleAgreement->tanggal_sa->format('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-tighter">Supir & Truk</p>
                        <p class="text-sm font-bold text-gray-800">{{ $scheduleAgreement->driver->nama ?? '-' }}</p>
                        <p class="text-xs text-gray-500">{{ $scheduleAgreement->truck->nomor_polisi ?? '-' }} {{ $scheduleAgreement->truck->nama_truk ? '('.$scheduleAgreement->truck->nama_truk.')' : '' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-tighter">Target Volume</p>
                        <p class="text-lg font-bold text-blue-600">{{ $scheduleAgreement->jumlah_do }} DO <span class="text-sm font-normal text-gray-400">({{ number_format($scheduleAgreement->jumlah_tabung) }} tabung)</span></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-tighter">Status</p>
                        @if($scheduleAgreement->status_sa == 'pending')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 mt-1">Pending</span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 mt-1">Selesai</span>
                        @endif
                    </div>
                    @if($scheduleAgreement->keterangan)
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-tighter">Keterangan</p>
                        <p class="text-sm text-gray-600">{{ $scheduleAgreement->keterangan }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Linked Penebusans --}}
        <div class="md:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Riwayat Penebusan DO</h3>
                    <span class="text-xs text-gray-500">{{ $scheduleAgreement->penebusans->count() }} Penebusan</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor DO</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Armada</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Tabung</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($scheduleAgreement->penebusans as $p)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm font-bold text-gray-800">#{{ $p->nomor_do }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $p->tanggal_penebusan->format('d/m/y') }}</td>
                                <td class="px-6 py-4 text-xs text-gray-500">{{ $p->truck->nomor_polisi }} / {{ $p->driver->nama }}</td>
                                <td class="px-6 py-4 text-right text-sm font-semibold text-gray-700">{{ number_format($p->jumlah_tabung) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-400 italic text-sm">Belum ada penebusan untuk SA ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($scheduleAgreement->penebusans->count() > 0)
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase">Total Realisasi</td>
                                <td class="px-6 py-3 text-right text-sm font-bold text-blue-600">{{ number_format($scheduleAgreement->penebusans->sum('jumlah_tabung')) }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
