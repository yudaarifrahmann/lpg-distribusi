@extends('layouts.admin')
@section('title', 'Pengeluaran - LPG Distribution')
@section('page_title', 'History Pengeluaran')

@section('content')
<div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Pengeluaran Hari Ini</p>
        <h3 class="text-2xl font-black text-gray-800">Rp {{ number_format($totalHariIni) }}</h3>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Filtered</p>
        <h3 class="text-2xl font-black text-blue-600">Rp {{ number_format($totalFiltered) }}</h3>
    </div>
    <div class="bg-gray-900 p-6 rounded-2xl shadow-lg flex items-center justify-between">
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Action</p>
            <h3 class="text-lg font-bold text-white">Input Baru</h3>
        </div>
        <a href="{{ route('expense.create') }}" class="p-3 bg-emerald-500 rounded-xl text-white hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" action="{{ route('expense.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
        <div class="lg:col-span-1">
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Deskripsi..." class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition">
        </div>

        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Status</label>
            <select name="status" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>

        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Kategori</label>
            <select name="category_id" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                @endforeach
            </select>
        </div>

        @if($users->count() > 0)
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">User</label>
            <select name="user_id" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition">
                <option value="">Semua User</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Mulai</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition">
        </div>

        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Akhir</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition">
        </div>

        <div class="lg:col-span-6 flex gap-2">
            <button type="submit" class="flex-1 bg-gray-800 text-white font-bold py-2 rounded-xl text-sm hover:bg-gray-700 transition">Filter Data</button>
            <a href="{{ route('expense.index') }}" class="px-6 bg-gray-100 text-gray-600 font-bold py-2 rounded-xl text-sm hover:bg-gray-200 transition">Reset</a>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full divide-y divide-gray-100">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Tanggal</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Kategori</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Keterangan</th>
                    <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-400 uppercase">Nominal</th>
                    <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase">Status</th>
                    <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase">Nota</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($expenses as $exp)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 text-xs font-bold text-gray-600">{{ $exp->tanggal_pengeluaran->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-gray-100 text-[10px] font-bold text-gray-500 rounded uppercase">{{ $exp->category ? $exp->category->nama_kategori : 'TANPA KATEGORI' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-xs font-bold text-gray-800">{{ $exp->nama_pengeluaran }}</p>
                        <p class="text-[10px] text-gray-400 italic">Input oleh: {{ $exp->user->name }}</p>
                    </td>
                    <td class="px-6 py-4 text-right text-sm font-black text-gray-900">Rp {{ number_format($exp->nominal) }}</td>
                    <td class="px-6 py-4 text-center">
                        @php
                            $st = [
                                'pending' => 'bg-amber-100 text-amber-700',
                                'disetujui' => 'bg-emerald-100 text-emerald-700',
                                'ditolak' => 'bg-red-100 text-red-700',
                            ];
                        @endphp
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $st[$exp->status_verifikasi] }}">
                            {{ $exp->status_verifikasi }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex -space-x-2 justify-center">
                            @foreach($exp->attachments->take(3) as $file)
                            <div class="w-6 h-6 rounded-full border-2 border-white overflow-hidden bg-gray-100">
                                <img src="{{ Storage::url($file->path_file) }}" class="w-full h-full object-cover">
                            </div>
                            @endforeach
                            @if($exp->attachments->count() > 3)
                            <div class="w-6 h-6 rounded-full border-2 border-white bg-gray-200 flex items-center justify-center text-[8px] font-bold text-gray-500">
                                +{{ $exp->attachments->count() - 3 }}
                            </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('expense.show', $exp) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition inline-block">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400 italic">Belum ada data pengeluaran.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-50">
        {{ $expenses->links() }}
    </div>
</div>
@endsection
