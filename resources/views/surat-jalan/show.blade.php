@extends('layouts.admin')
@section('title', 'Detail SJ - LPG Distribution')
@section('page_title', 'Detail Surat Jalan')

@section('content')
<div class="max-w-5xl">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('surat-jalan.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
            Kembali
        </a>
        <div class="flex space-x-2">
            <a href="{{ route('surat-jalan.print', $suratJalan) }}" target="_blank" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-bold rounded-lg hover:bg-gray-200 transition flex items-center">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 2H7V4h6v2zM9 14v2H7v-2h2zm2 2v-2h2v2h-2z" clip-rule="evenodd"/></svg>
                Cetak SJ
            </a>
            @if(Auth::user()->hasRole('supir_knek'))
                <button onclick="downloadSJImage()" class="px-4 py-2 bg-pink-600 text-white text-sm font-bold rounded-lg hover:bg-pink-700 transition flex items-center shadow-lg shadow-pink-500/30">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Simpan Gambar
                </button>
            @else
                <a href="{{ route('surat-jalan.download', $suratJalan) }}" class="px-4 py-2 bg-emerald-600 text-white text-sm font-bold rounded-lg hover:bg-emerald-700 transition flex items-center shadow-lg shadow-emerald-500/30">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Download PDF
                </a>
            @endif
            @if($suratJalan->status_perjalanan != 'selesai')
                @can('edit surat jalan')
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition shadow-lg shadow-blue-500/30 flex items-center">
                        Update Status
                        <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                        <form action="{{ route('surat-jalan.update-status', $suratJalan) }}" method="POST">
                            @csrf @method('PATCH')
                            <div class="p-1.5 space-y-1">
                                @if($suratJalan->status_perjalanan !== 'persiapan')
                                <button type="submit" name="status_perjalanan" value="persiapan" class="w-full text-left px-3 py-2 text-xs font-bold text-amber-700 hover:bg-amber-50 rounded-lg transition">Persiapan</button>
                                @endif
                                @if($suratJalan->status_perjalanan !== 'berangkat')
                                <button type="submit" name="status_perjalanan" value="berangkat" class="w-full text-left px-3 py-2 text-xs font-bold text-blue-700 hover:bg-blue-50 rounded-lg transition">Berangkat</button>
                                @endif
                                <button type="submit" name="status_perjalanan" value="selesai" class="w-full text-left px-3 py-2 text-xs font-bold text-emerald-700 hover:bg-emerald-50 rounded-lg transition">Selesai</button>
                                <button type="submit" name="status_perjalanan" value="dibatalkan" class="w-full text-left px-3 py-2 text-xs font-bold text-red-600 hover:bg-red-50 rounded-xl transition flex items-center" onclick="return confirm('Yakin ingin membatalkan SJ ini?')">Batalkan SJ</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endcan
            @endif
        </div>
    </div>

    {{-- Loading Overlay for Image Export --}}
    <div id="download-loading" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] flex items-center justify-center">
        <div class="bg-white p-6 rounded-2xl shadow-xl flex items-center space-x-4">
            <svg class="animate-spin h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <span class="font-bold text-gray-800">Menyiapkan Gambar...</span>
        </div>
    </div>

    {{-- Capture Area --}}
    <div id="sj-capture-area" class="bg-white" style="position: fixed; left: 0; top: 0; opacity: 0.01; z-index: -1; pointer-events: none;">
        @include('surat-jalan._print_content')
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column: Main Details --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 bg-gradient-to-r from-indigo-700 to-indigo-600 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-indigo-100 text-xs font-bold uppercase tracking-widest">Nomor Surat Jalan</p>
                            <h2 class="text-2xl font-black mt-1">{{ $suratJalan->nomor_surat_jalan }}</h2>
                        </div>
                        <div class="text-right">
                            <p class="text-indigo-100 text-xs font-bold uppercase tracking-widest">Status</p>
                            <span class="inline-flex mt-1 px-3 py-1 bg-white/20 backdrop-blur-md text-white text-xs font-bold rounded-full">
                                {{ strtoupper($suratJalan->status_perjalanan) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="p-8">
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-tighter mb-1">Tanggal Berangkat</p>
                            <p class="text-gray-800 font-bold">{{ $suratJalan->tanggal_berangkat->format('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-tighter mb-1">Volume Muatan</p>
                            <p class="text-indigo-600 font-black text-xl">{{ number_format($suratJalan->jumlah_tabung) }} <span class="text-xs font-normal text-gray-400">Tabung</span></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-tighter mb-1">Referensi DO</p>
                            @if($suratJalan->penebusan)
                                <p class="text-gray-800 font-semibold">#{{ $suratJalan->penebusan->nomor_do }}</p>
                                <p class="text-[10px] text-gray-400">Tebus: {{ $suratJalan->penebusan->tanggal_penebusan->format('d/m/y') }}</p>
                            @else
                                <p class="text-indigo-600 font-bold text-sm">MUAT GUDANG</p>
                                <p class="text-[10px] text-gray-400 italic">Stok diambil dari gudang utama</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-tighter mb-1">Catatan</p>
                            <p class="text-gray-600 text-sm italic">{{ $suratJalan->catatan ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Personnel & Vehicle Card --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Informasi Armada & Kru</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                            </div>
                            <div>
                                <p class="text-gray-800 font-bold">{{ $suratJalan->truck->nomor_polisi }}</p>
                                <p class="text-xs text-gray-500 uppercase tracking-widest">{{ $suratJalan->truck->nama_truk }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 rounded-2xl col-span-2 md:col-span-1">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Supir Utama</p>
                            @if($suratJalan->is_supir_tembak)
                                <p class="text-sm font-bold text-amber-700">{{ $suratJalan->nama_supir_tembak }} <span class="text-[10px] bg-amber-100 px-1.5 py-0.5 rounded ml-1 uppercase">Tembak</span></p>
                                <p class="text-xs text-gray-500 mt-1"><span class="font-semibold">HP:</span> {{ $suratJalan->no_hp_supir_tembak }}</p>
                            @else
                                <p class="text-sm font-bold text-gray-800">{{ $suratJalan->supir->nama ?? '-' }}</p>
                            @endif
                        </div>
                        <div class="p-4 bg-gray-50 rounded-2xl col-span-2 md:col-span-1">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Knek Pendamping</p>
                            @if($suratJalan->is_knek_tembak)
                                <p class="text-sm font-bold text-amber-700">{{ $suratJalan->nama_knek_tembak }} <span class="text-[10px] bg-amber-100 px-1.5 py-0.5 rounded ml-1 uppercase">Tembak</span></p>
                            @else
                                <p class="text-sm font-bold text-gray-800">{{ $suratJalan->knek->nama ?? 'Tidak Ada' }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Timeline --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                <div class="px-6 py-5 border-b border-gray-50 bg-gray-50/50">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Timeline Perjalanan</h3>
                </div>
                <div class="p-6 text-sm">
                    <ul class="space-y-4">
                        <li class="flex items-center space-x-3">
                            <div class="w-2 h-2 rounded-full bg-indigo-600"></div>
                            <span class="font-bold">Persiapan</span>
                        </li>
                        <li class="flex items-center space-x-3 {{ in_array($suratJalan->status_perjalanan, ['berangkat', 'selesai', 'retur']) ? 'opacity-100' : 'opacity-30' }}">
                            <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                            <span class="font-bold">Berangkat</span>
                        </li>
                        <li class="flex items-center space-x-3 {{ in_array($suratJalan->status_perjalanan, ['selesai', 'retur']) ? 'opacity-100' : 'opacity-30' }}">
                            <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                            <span class="font-bold">Selesai</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>
    function downloadSJImage() {
        if (typeof html2canvas === 'undefined') {
            alert('Library gambar belum siap. Silakan tunggu sebentar dan coba lagi.');
            return;
        }

        const loader = document.getElementById('download-loading');
        const captureArea = document.getElementById('sj-capture-area');
        if (!captureArea || !loader) return;
        
        loader.classList.remove('hidden');

        // Create a hidden iframe for isolation
        const iframe = document.createElement('iframe');
        iframe.style.position = 'fixed';
        iframe.style.left = '-9999px';
        iframe.style.width = '850px'; // Slightly wider than capture container
        iframe.style.height = '1200px';
        document.body.appendChild(iframe);

        const iframeDoc = iframe.contentWindow.document;
        iframeDoc.open();
        iframeDoc.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { margin: 0; padding: 0; background: white; }
                </style>
            </head>
            <body>
                ${captureArea.innerHTML}
            </body>
            </html>
        `);
        iframeDoc.close();

        // Wait for iframe to render
        setTimeout(() => {
            const target = iframeDoc.getElementById('capture-container');
            html2canvas(target || iframeDoc.body, {
                scale: 2,
                useCORS: true,
                backgroundColor: '#ffffff'
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'SJ-{{ $suratJalan->nomor_surat_jalan }}.png';
                link.href = canvas.toDataURL('image/png');
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                document.body.removeChild(iframe);
                loader.classList.add('hidden');
            }).catch(err => {
                console.error('Image capture failed:', err);
                document.body.removeChild(iframe);
                loader.classList.add('hidden');
                alert('Gagal mengambil gambar. Detail: ' + err.message);
            });
        }, 500);
    }

    window.addEventListener('load', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('download_image') === '1') {
            setTimeout(downloadSJImage, 1000);
        }
    });
</script>
@endsection
