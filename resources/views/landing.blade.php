<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LPG Distrib - Sistem Manajemen Distribusi Terpadu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); }
        .bg-mesh {
            background-color: #ffffff;
            background-image: radial-gradient(at 0% 0%, hsla(210,100%,95%,1) 0, transparent 50%), 
                              radial-gradient(at 100% 100%, hsla(220,100%,95%,1) 0, transparent 50%);
        }
    </style>
</head>
<body class="bg-mesh text-slate-900 overflow-x-hidden">

    {{-- Navigation --}}
    <nav class="fixed w-full z-50 glass border-b border-slate-200/50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-1.945-1.164c-.143-.225-.35-.373-.572-.444a1 1 0 00-1.287.8 3.007 3.007 0 00-.164 1.084c.058 1.233.662 2.342 1.593 3.11.525.434 1.156.753 1.826.927a6.02 6.02 0 002.75.09c.925-.196 1.79-.673 2.456-1.37.726-.762 1.19-1.753 1.292-2.882a5.01 5.01 0 00-.49-2.678c-.293-.556-.674-1.047-1.063-1.468a13.372 13.372 0 00-.964-.94c.067-.434.144-.872.233-1.29.178-.84.388-1.59.604-2.166.11-.293.214-.538.302-.712a2.38 2.38 0 01.082-.134z"/></svg>
                    </div>
                    <span class="text-xl font-extrabold tracking-tight text-slate-900 uppercase">LPG <span class="text-blue-600">Distrib</span></span>
                </div>
                <div class="hidden md:flex items-center space-x-10 text-sm font-semibold text-slate-600 uppercase tracking-widest">
                    <a href="#features" class="hover:text-blue-600 transition">Fitur</a>
                    <a href="#stats" class="hover:text-blue-600 transition">Statistik</a>
                    <a href="#about" class="hover:text-blue-600 transition">Tentang</a>
                </div>
                <div>
                    @auth
                    <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-slate-900 text-white rounded-full text-sm font-bold shadow-xl hover:bg-black transition-all">Dashboard</a>
                    @else
                    <a href="{{ route('login') }}" class="px-8 py-3 bg-blue-600 text-white rounded-full text-sm font-bold shadow-xl shadow-blue-500/20 hover:bg-blue-700 transition-all">Masuk Sistem</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <header class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 px-6 overflow-hidden">
        <div class="max-w-7xl mx-auto text-center">
            <div class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-full text-[10px] font-black uppercase tracking-widest mb-8 border border-blue-100 animate-pulse">
                Terintegrasi Dengan Laravel 12
            </div>
            <h1 class="text-5xl lg:text-7xl font-black text-slate-900 leading-[1.1] tracking-tight mb-8">
                Optimalkan Distribusi LPG <br> 
                <span class="text-blue-600">Lebih Cepat & Transparan.</span>
            </h1>
            <p class="max-w-2xl mx-auto text-lg text-slate-500 leading-relaxed mb-12">
                Sistem manajemen distribusi LPG paling modern untuk membantu agen mengelola stok gudang, penjualan pangkalan, hingga piutang dalam satu dashboard terpusat.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-10 py-5 bg-blue-600 text-white rounded-2xl font-bold shadow-2xl shadow-blue-500/40 hover:scale-105 transition-all">Mulai Sekarang</a>
                <a href="#features" class="w-full sm:w-auto px-10 py-5 bg-white text-slate-700 border border-slate-200 rounded-2xl font-bold hover:bg-slate-50 transition-all">Lihat Fitur</a>
            </div>
        </div>

        {{-- Floating UI Element --}}
        <div class="mt-20 relative max-w-5xl mx-auto">
            <div class="bg-white p-4 rounded-3xl shadow-[0_40px_100px_-15px_rgba(0,0,0,0.1)] border border-slate-100 overflow-hidden">
                <div class="bg-slate-50 rounded-2xl h-[400px] lg:h-[500px] flex items-center justify-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-blue-500/10"></div>
                    <div class="relative text-center p-8">
                        <div class="w-20 h-20 bg-white rounded-3xl shadow-xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <h4 class="text-2xl font-bold text-slate-800 mb-2">Dashboard Statistik Interaktif</h4>
                        <p class="text-slate-500 text-sm max-w-md mx-auto">Pantau grafik penjualan harian, tren laba rugi, dan pergerakan stok secara real-time dari manapun.</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Features Section --}}
    <section id="features" class="py-32 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-20">
                <h4 class="text-xs font-black text-blue-600 uppercase tracking-widest mb-4">Solusi Menyeluruh</h4>
                <h2 class="text-4xl lg:text-5xl font-black text-slate-900">Alur Kerja yang Modern</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                {{-- Feature 1 --}}
                <div class="group">
                    <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-4 uppercase tracking-tighter">Stok Real-time</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">Sinkronisasi stok gudang dan kendaraan pengirim secara instan. Tidak ada lagi selisih data.</p>
                </div>

                {{-- Feature 2 --}}
                <div class="group">
                    <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-4 uppercase tracking-tighter">Manajemen Piutang</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">Pantau pembayaran pangkalan dengan sistem cicilan terintegrasi dan notifikasi jatuh tempo.</p>
                </div>

                {{-- Feature 3 --}}
                <div class="group">
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-4 uppercase tracking-tighter">Laporan Audit</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">Laporan laba rugi, rekap penjualan, dan audit log yang transparan dan siap untuk audit.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats Section --}}
    <section id="stats" class="py-24 bg-slate-900 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-96 h-96 bg-blue-600 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-indigo-600 rounded-full blur-[100px]"></div>
        </div>
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-12 text-center">
                <div>
                    <h2 class="text-5xl font-black mb-2 tracking-tighter">100%</h2>
                    <p class="text-blue-400 text-[10px] font-bold uppercase tracking-[0.2em]">Akurasi Stok</p>
                </div>
                <div>
                    <h2 class="text-5xl font-black mb-2 tracking-tighter">24/7</h2>
                    <p class="text-blue-400 text-[10px] font-bold uppercase tracking-[0.2em]">Akses Sistem</p>
                </div>
                <div>
                    <h2 class="text-5xl font-black mb-2 tracking-tighter">Auto</h2>
                    <p class="text-blue-400 text-[10px] font-bold uppercase tracking-[0.2em]">Pencadangan Data</p>
                </div>
                <div>
                    <h2 class="text-5xl font-black mb-2 tracking-tighter">Push</h2>
                    <p class="text-blue-400 text-[10px] font-bold uppercase tracking-[0.2em]">Notifikasi Realtime</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="py-20 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
            <div class="mb-8">
                <span class="text-2xl font-black text-slate-900 uppercase">LPG <span class="text-blue-600">Distrib</span></span>
            </div>
            <p class="text-slate-400 text-sm mb-8">&copy; 2026 LPG Distribution Management System. All rights reserved.</p>
            <div class="flex justify-center space-x-6">
                <a href="#" class="text-slate-400 hover:text-blue-600 transition text-[10px] font-bold uppercase tracking-widest">Kebijakan Privasi</a>
                <a href="{{ route('terms') }}" class="text-slate-400 hover:text-blue-600 transition text-[10px] font-bold uppercase tracking-widest">Syarat & Ketentuan</a>
            </div>
        </div>
    </footer>

</body>
</html>
