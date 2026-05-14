@extends('layouts.admin')

@section('title', 'User Management - LPG Distribution')
@section('page_title', 'User Management')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">User Management</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola dan pantau akun pengguna sistem.</p>
        </div>
        @can('create user')
        <a href="{{ route('user-management.create') }}" class="inline-flex items-center px-4 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-200 hover:-translate-y-0.5">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
            Tambah User
        </a>
        @endcan
    </div>

    <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-4">
        <form method="GET" action="{{ route('user-management.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div class="md:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex gap-2">
                <select name="role" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Role</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->name }}" @selected(request('role') === $role->name)>{{ str_replace('_', ' ', $role->name) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Cabang</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Profil Driver</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Dibuat</th>
                        <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-semibold text-gray-800">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1.5">
                                @forelse($user->roles as $role)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                    {{ str_replace('_', ' ', $role->name) }}
                                </span>
                                @empty
                                <span class="text-xs text-gray-400">Belum ada role</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $user->branch ? $user->branch->name : 'PT. FARHAN ENERGI GASINDO' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($user->driver_count > 0)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Terhubung</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-500 border border-gray-100">Tidak ada</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-1">
                                @can('edit user')
                                @if(auth()->user()->hasRole('superadmin') || !$user->hasRole('superadmin'))
                                <a href="{{ route('user-management.edit', $user) }}" class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Edit">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                                </a>
                                @endif
                                @endcan
                                @can('delete user')
                                @if($user->id !== Auth::id() && (auth()->user()->hasRole('superadmin') || !$user->hasRole('superadmin')))
                                <form action="{{ route('user-management.destroy', $user) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    </button>
                                </form>
                                @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">Data user tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
