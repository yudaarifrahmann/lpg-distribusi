@extends('layouts.admin')
@section('title', 'Surat Jalan - LPG Distribution')
@section('page_title', 'Surat Jalan (SJ)')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Surat Jalan</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola pengiriman tabung dari gudang ke pangkalan/toko</p>
    </div>
    @can('create surat jalan')
    <a href="{{ route('surat-jalan.create') }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all duration-200 hover:-translate-y-0.5">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
        Buat SJ Baru
    </a>
    @endcan
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" action="{{ route('surat-jalan.index') }}" class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor SJ..." class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
        </div>
        <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
            <option value="">Semua Status</option>
            <option value="persiapan" {{ request('status') == 'persiapan' ? 'selected' : '' }}>Persiapan</option>
            <option value="berangkat" {{ request('status') == 'berangkat' ? 'selected' : '' }}>Berangkat</option>
            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="retur" {{ request('status') == 'retur' ? 'selected' : '' }}>Retur</option>
            <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2.5 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">Filter</button>
            <a href="{{ route('surat-jalan.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 transition">Reset</a>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No SJ</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Armada & Supir</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tabung</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($suratJalans as $sj)
                <tr class="hover:bg-blue-50/40 transition">
                    <td class="px-6 py-4">
                        <p class="text-sm font-bold text-gray-800">{{ $sj->nomor_surat_jalan }}</p>
                        <p class="text-xs text-gray-400">
                            @if($sj->penebusan)
                                DO #{{ $sj->penebusan->nomor_do }}
                            @else
                                <span class="text-indigo-500 font-bold uppercase tracking-widest text-[9px]">Muat Gudang</span>
                            @endif
                        </p>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $sj->tanggal_berangkat->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">
                        <p class="text-xs font-bold text-gray-700">{{ $sj->truck->nomor_polisi }}</p>
                        <p class="text-xs text-gray-500">
                            @if($sj->is_supir_tembak)
                                <span class="text-amber-600 font-bold">[TEMBAK]</span> {{ $sj->nama_supir_tembak }}
                            @else
                                {{ $sj->supir->nama ?? '-' }}
                            @endif
                        </p>
                    </td>
                    <td class="px-6 py-4 text-sm font-bold text-indigo-600">{{ number_format($sj->jumlah_tabung) }}</td>
                    <td class="px-6 py-4">
                        @php
                            $statusColors = [
                                'persiapan' => 'bg-amber-100 text-amber-700',
                                'berangkat' => 'bg-blue-100 text-blue-700',
                                'selesai' => 'bg-emerald-100 text-emerald-700',
                                'retur' => 'bg-red-100 text-red-700',
                                'dibatalkan' => 'bg-red-100 text-red-700',
                            ];
                            $color = $statusColors[$sj->status_perjalanan] ?? 'bg-gray-100 text-gray-600';
                        @endphp
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $color }}">
                                {{ ucfirst(str_replace('_', ' ', $sj->status_perjalanan)) }}
                            </span>
                            @if(!in_array($sj->status_perjalanan, ['selesai', 'retur']))
                            <div class="relative inline-block">
                                <button type="button" onclick="toggleDropdown(event, 'status-menu-{{ $sj->id }}')" class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200" title="Ubah status">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/></svg>
                                </button>
                                <div id="status-menu-{{ $sj->id }}" class="hidden fixed z-[999] w-48 bg-white border border-gray-100 rounded-2xl shadow-2xl overflow-hidden transform transition-all duration-200 pointer-events-auto">
                                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Update Status</p>
                                    </div>
                                    <form action="{{ route('surat-jalan.update-status', $sj) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <div class="p-1.5 space-y-1">
                                            @if($sj->status_perjalanan !== 'persiapan')
                                            <button type="submit" name="status_perjalanan" value="persiapan" class="w-full text-left px-3 py-2 text-sm font-medium text-amber-700 hover:bg-amber-50 rounded-xl transition flex items-center">
                                                <span class="w-2 h-2 rounded-full bg-amber-500 mr-2.5"></span> Persiapan
                                            </button>
                                            @endif
                                            @if($sj->status_perjalanan !== 'berangkat')
                                            <button type="submit" name="status_perjalanan" value="berangkat" class="w-full text-left px-3 py-2 text-sm font-medium text-blue-700 hover:bg-blue-50 rounded-xl transition flex items-center">
                                                <span class="w-2 h-2 rounded-full bg-blue-500 mr-2.5"></span> Berangkat
                                            </button>
                                            @endif
                                            @if($sj->status_perjalanan !== 'selesai' && $sj->status_perjalanan !== 'persiapan')
                                            <button type="submit" name="status_perjalanan" value="selesai" class="w-full text-left px-3 py-2 text-sm font-medium text-emerald-700 hover:bg-emerald-50 rounded-xl transition flex items-center">
                                                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2.5"></span> Selesai
                                            </button>
                                            @endif
                                            <button type="submit" name="status_perjalanan" value="dibatalkan" class="w-full text-left px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-xl transition flex items-center" onclick="return confirm('Yakin ingin membatalkan SJ ini?')">
                                                <span class="w-2 h-2 rounded-full bg-red-500 mr-2.5"></span> Batalkan SJ
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('surat-jalan.show', $sj) }}" class="p-2 text-blue-500 hover:bg-blue-50 rounded-xl transition-all" title="Detail">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                            </a>
                             <a href="{{ route('surat-jalan.print', $sj) }}" target="_blank" class="p-2 text-gray-400 hover:bg-gray-100 rounded-xl transition-all" title="Cetak">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 2H7V4h6v2zM9 14v2H7v-2h2zm2 2v-2h2v2h-2z" clip-rule="evenodd"/></svg>
                            </a>
                            @if(Auth::user()->hasRole('supir_knek'))
                            <a href="{{ route('surat-jalan.show', [$sj, 'download_image' => 1]) }}" class="p-2 text-pink-500 hover:bg-pink-50 rounded-xl transition-all" title="Download Gambar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </a>
                            @else
                            <a href="{{ route('surat-jalan.download', $sj) }}" class="p-2 text-emerald-500 hover:bg-emerald-50 rounded-xl transition-all" title="Download PDF">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </a>
                            @endif
                            @can('delete surat jalan')
                                @if($sj->status_perjalanan == 'persiapan')
                                <form action="{{ route('surat-jalan.destroy', $sj) }}" method="POST" onsubmit="return confirm('Membatalkan SJ akan mengembalikan stok ke gudang. Lanjutkan?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-400 hover:bg-red-50 rounded-xl transition-all" title="Batal"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></button>
                                </form>
                                @endif
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-gray-500 italic">Belum ada data Surat Jalan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($suratJalans->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">{{ $suratJalans->links() }}</div>
    @endif
</div>

<script>
function toggleDropdown(event, menuId) {
    event.stopPropagation();
    const menu = document.getElementById(menuId);
    const button = event.currentTarget;
    const isHidden = menu.classList.contains('hidden');

    // Close all other dropdowns
    document.querySelectorAll('[id^="status-menu-"]').forEach(el => {
        if (el.id !== menuId) el.classList.add('hidden');
    });

    if (isHidden) {
        menu.classList.remove('hidden');
        
        // Dynamic Positioning using Fixed
        const rect = button.getBoundingClientRect();
        const menuWidth = 192; // w-48 = 12rem = 192px
        const menuHeight = menu.offsetHeight || 160;
        
        // Calculate horizontal position (align right to button)
        let left = rect.right - menuWidth;
        if (left < 10) left = 10; // keep away from left edge

        // Calculate vertical position
        const spaceBelow = window.innerHeight - rect.bottom;
        const spaceAbove = rect.top;
        
        if (spaceBelow < menuHeight && spaceAbove > menuHeight) {
            // Open upwards
            menu.style.top = 'auto';
            menu.style.bottom = (window.innerHeight - rect.top + 8) + 'px';
            menu.style.left = left + 'px';
            menu.classList.add('origin-bottom-right');
            menu.classList.remove('origin-top-right');
        } else {
            // Open downwards
            menu.style.bottom = 'auto';
            menu.style.top = (rect.bottom + 8) + 'px';
            menu.style.left = left + 'px';
            menu.classList.add('origin-top-right');
            menu.classList.remove('origin-bottom-right');
        }
    } else {
        menu.classList.add('hidden');
    }
}

// Close dropdowns on interaction outside
window.addEventListener('click', () => {
    document.querySelectorAll('[id^="status-menu-"]').forEach(el => el.classList.add('hidden'));
});

window.addEventListener('scroll', () => {
    document.querySelectorAll('[id^="status-menu-"]').forEach(el => el.classList.add('hidden'));
}, true);

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        document.querySelectorAll('[id^="status-menu-"]').forEach(el => el.classList.add('hidden'));
    }
});
</script>
@endsection
