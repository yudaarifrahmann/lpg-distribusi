@extends('layouts.admin')
@section('title', 'Schedule Agreement - LPG Distribution')
@section('page_title', 'Schedule Agreement (SA)')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Data Schedule Agreement</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola rencana pengambilan DO (Schedule Agreement)</p>
    </div>
    @can('create sa')
    <a href="{{ route('schedule-agreement.create') }}" class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-200 hover:-translate-y-0.5">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
        Buat SA Baru
    </a>
    @endcan
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" action="{{ route('schedule-agreement.index') }}" class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari keterangan..." class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
        </div>
        <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
        </select>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2.5 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">Filter</button>
            <a href="{{ route('schedule-agreement.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 transition">Reset</a>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal SA</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Supir & Truk</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah DO</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah Tabung</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($sas as $i => $sa)
                <tr class="hover:bg-blue-50/40 transition">
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $sas->firstItem() + $i }}</td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-semibold text-gray-800">{{ $sa->tanggal_sa->format('d M Y') }}</p>
                        @if($sa->keterangan)
                            <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ $sa->keterangan }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-semibold text-gray-800">{{ $sa->driver->nama ?? '-' }}</p>
                        <p class="text-xs text-gray-500">{{ $sa->truck->nomor_polisi ?? '-' }}</p>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $sa->jumlah_do }} DO</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ number_format($sa->jumlah_tabung) }} Tabung</td>
                    <td class="px-6 py-4">
                        @if($sa->status_sa == 'pending')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>Pending
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Selesai
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('schedule-agreement.show', $sa) }}" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition" title="Detail">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                            </a>
                            @can('edit sa')
                            <a href="{{ route('schedule-agreement.edit', $sa) }}" class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Edit">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                            </a>
                            @endcan
                            @if($sa->status_sa == 'pending')
                                <a href="{{ route('penebusan.create', ['sa_id' => $sa->id]) }}" class="inline-flex items-center px-2.5 py-1.5 bg-emerald-500 text-white text-xs font-bold rounded-lg hover:bg-emerald-600 transition">
                                    Tebus DO
                                </a>
                                <form action="{{ route('schedule-agreement.destroy', $sa) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan Schedule Agreement ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition" title="Batal SA">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-gray-500 italic">Belum ada data Schedule Agreement.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($sas->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">{{ $sas->links() }}</div>
    @endif
</div>
@endsection
