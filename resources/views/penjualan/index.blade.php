@extends('layouts.admin')
@section('title', 'Penjualan - LPG Distribution')
@section('page_title', 'Histori Penjualan')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Penjualan</h1>
        <p class="text-sm text-gray-500 mt-1">Pantau seluruh transaksi penjualan ke pangkalan</p>
    </div>
    @can('create penjualan')
    <a href="{{ route('penjualan.create') }}" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/50 transition-all duration-200 hover:-translate-y-0.5">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
        Input Penjualan
    </a>
    @endcan
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" action="{{ route('penjualan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
        <div class="lg:col-span-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor invoice..." class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 transition">
        </div>
        <select name="pangkalan_id" class="px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 transition">
            <option value="">Semua Pangkalan</option>
            @foreach($pangkalans as $p)
                <option value="{{ $p->id }}" {{ request('pangkalan_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_pangkalan }}</option>
            @endforeach
        </select>
        <select name="metode_pembayaran" class="px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 transition">
            <option value="">Metode Bayar</option>
            <option value="cash" {{ request('metode_pembayaran') == 'cash' ? 'selected' : '' }}>Cash</option>
            <option value="transfer" {{ request('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer</option>
            <option value="utang" {{ request('metode_pembayaran') == 'utang' ? 'selected' : '' }}>Utang</option>
        </select>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">Filter</button>
            <a href="{{ route('penjualan.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 transition text-center flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </a>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Invoice / Tanggal</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pangkalan</th>
                    <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Qty / Harga</th>
                    <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Bayar</th>
                    <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($penjualans as $p)
                <tr class="hover:bg-emerald-50/40 transition">
                    <td class="px-6 py-4">
                        <p class="text-sm font-bold text-gray-800">{{ $p->nomor_invoice }}</p>
                        <p class="text-xs text-gray-400">{{ $p->tanggal_penjualan->format('d/m/Y') }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-semibold text-gray-700">{{ $p->pangkalan->nama_pangkalan }}</p>
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest">{{ $p->truck->nomor_polisi }} | {{ $p->supir->nama }}</p>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <p class="text-sm font-bold text-gray-800">{{ $p->jumlah_tabung }} Pcs</p>
                        <p class="text-xs text-gray-400">@ Rp {{ number_format($p->harga_satuan, 0, ',', '.') }}</p>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <p class="text-sm font-black text-emerald-600">Rp {{ number_format($p->total_penjualan, 0, ',', '.') }}</p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider 
                            {{ $p->metode_pembayaran == 'utang' ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }}">
                            {{ $p->metode_pembayaran }}
                        </span>
                        <p class="text-[10px] mt-1 {{ $p->status_pembayaran == 'lunas' ? 'text-emerald-500' : 'text-amber-500' }} font-bold">
                            {{ strtoupper($p->status_pembayaran) }}
                        </p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('penjualan.show', $p) }}" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition" title="Detail">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                            </a>
                            @can('delete penjualan')
                            <form action="{{ route('penjualan.destroy', $p) }}" method="POST" onsubmit="return confirm('Batalkan transaksi ini? Stok kendaraan akan dikembalikan.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition" title="Batal">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-gray-500 italic">Belum ada data penjualan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($penjualans->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">{{ $penjualans->links() }}</div>
    @endif
</div>
@endsection
