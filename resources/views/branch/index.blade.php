@extends('layouts.admin')

@section('title', 'Data Cabang')
@section('page_title', 'Data Cabang')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-800">Daftar Cabang</h3>
        <a href="{{ route('branch.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Cabang
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-700 text-sm border-b border-gray-200">
                    <th class="px-6 py-4 font-semibold">Nama Cabang</th>
                    <th class="px-6 py-4 font-semibold">Alamat</th>
                    <th class="px-6 py-4 font-semibold">Telepon</th>
                    <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($branches as $branch)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm text-gray-800 font-medium">{{ $branch->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $branch->address ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $branch->phone ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-right space-x-2">
                        <a href="{{ route('branch.edit', $branch) }}" class="inline-flex items-center text-amber-500 hover:text-amber-700 font-medium bg-amber-50 hover:bg-amber-100 px-3 py-1.5 rounded-lg transition">
                            Edit
                        </a>
                        <form action="{{ route('branch.destroy', $branch) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus cabang ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center text-red-500 hover:text-red-700 font-medium bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500 text-sm">Tidak ada data cabang.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($branches->hasPages())
    <div class="p-4 border-t border-gray-200">
        {{ $branches->links() }}
    </div>
    @endif
</div>
@endsection
