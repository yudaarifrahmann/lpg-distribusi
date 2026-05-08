@extends('layouts.admin')
@section('title', 'Penebusan DO - LPG Distribution')
@section('page_title', 'Penebusan DO')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Data Penebusan</h1>
        <p class="text-sm text-gray-500 mt-1">Riwayat penebusan DO ke Pertamina</p>
    </div>
    @can('create penebusan')
    <a href="{{ route('penebusan.create') }}" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/50 transition-all duration-200 hover:-translate-y-0.5">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
        Tambah Penebusan
    </a>
    @endcan
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" action="{{ route('penebusan.index') }}" class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor DO..." class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
        </div>
        <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2.5 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">Filter</button>
            <a href="{{ route('penebusan.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 transition">Reset</a>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Info DO</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tabung</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Biaya</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Armada</th>
                    <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($penebusans as $i => $p)
                <tr class="hover:bg-blue-50/40 transition">
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $penebusans->firstItem() + $i }}</td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-bold text-gray-800">#{{ $p->nomor_do }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $p->tanggal_penebusan->format('d M Y') }}</p>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ number_format($p->jumlah_tabung) }} Tabung</td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-semibold text-gray-800">Rp {{ number_format($p->total_penebusan, 0, ',', '.') }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-xs font-semibold text-gray-700">{{ $p->truck->nomor_polisi }}</p>
                        <p class="text-xs text-gray-500">{{ $p->driver->nama }}</p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center space-x-1">
                            <a href="{{ route('penebusan.show', $p) }}" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition" title="Detail">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                            </a>
                            @can('delete penebusan')
                            <form action="{{ route('penebusan.destroy', $p) }}" method="POST" onsubmit="return confirm('Membatalkan penebusan akan mengurangi stok gudang. Lanjutkan?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg></button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-gray-500 italic">Belum ada riwayat penebusan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($penebusans->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">{{ $penebusans->links() }}</div>
    @endif
</div>
@endsection
