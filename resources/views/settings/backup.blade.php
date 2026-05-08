@extends('layouts.admin')
@section('title', 'Backup Data - LPG Distribution')
@section('page_title', 'Database Management')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 mb-8">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <h4 class="text-xl font-black text-gray-900">Cadangkan Database</h4>
                <p class="text-xs text-gray-500 mt-1">Buat salinan instan seluruh data sistem Anda ke format SQL.</p>
            </div>
            <form action="{{ route('backup.create') }}" method="POST">
                @csrf
                <button type="submit" class="px-8 py-4 bg-gray-900 text-white rounded-2xl font-bold shadow-xl hover:bg-black transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Buat Backup Sekarang
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-8 py-6 bg-gray-50/50 border-b border-gray-100">
            <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest">Riwayat Backup</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs divide-y divide-gray-100">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-gray-400 uppercase">Nama File</th>
                        <th class="px-8 py-4 text-center text-[10px] font-bold text-gray-400 uppercase">Ukuran</th>
                        <th class="px-8 py-4 text-right text-[10px] font-bold text-gray-400 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($backups as $file)
                    @php 
                        $basename = basename($file);
                        $size = Storage::size($file);
                    @endphp
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-8 py-4">
                            <p class="font-bold text-gray-800">{{ $basename }}</p>
                            <p class="text-[10px] text-gray-400">{{ date('d/m/Y H:i:s', Storage::lastModified($file)) }}</p>
                        </td>
                        <td class="px-8 py-4 text-center font-mono text-gray-500">
                            {{ number_format($size / 1024, 2) }} KB
                        </td>
                        <td class="px-8 py-4 text-right">
                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('backup.download', $basename) }}" class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                </a>
                                <form action="{{ route('backup.destroy', $basename) }}" method="POST" onsubmit="return confirm('Hapus file backup ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-8 py-12 text-center text-gray-400 italic">Belum ada file backup.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-12 bg-amber-50 border border-amber-200 rounded-3xl p-8">
        <div class="flex space-x-4">
            <div class="w-12 h-12 bg-amber-200 rounded-2xl flex items-center justify-center text-amber-700 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h5 class="text-sm font-black text-amber-900 uppercase">Informasi Keamanan</h5>
                <p class="text-xs text-amber-800 mt-2 leading-relaxed">
                    Backup ini hanya mencakup database SQL. Pastikan Anda juga mencadangkan file lampiran di folder <span class="font-mono bg-amber-100 px-1 rounded">storage/app/public</span> secara manual. Disarankan untuk mendownload backup ke media penyimpanan eksternal secara berkala.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
