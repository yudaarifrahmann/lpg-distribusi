<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings['site_name'] }} - Agen LPG</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .hero-pattern {
            background-color: #f8fafc;
            background-image:
                linear-gradient(rgba(15, 23, 42, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(15, 23, 42, 0.04) 1px, transparent 1px);
            background-size: 28px 28px;
        }
        .cylinder {
            width: 120px;
            height: 178px;
            border-radius: 28px 28px 34px 34px;
            background: linear-gradient(135deg, #14b8a6 0%, #0f766e 55%, #115e59 100%);
            box-shadow: inset -18px 0 24px rgba(15, 23, 42, 0.18), 0 24px 60px rgba(15, 23, 42, 0.18);
            position: relative;
        }
        .cylinder:before {
            content: "";
            position: absolute;
            left: 31px;
            top: -28px;
            width: 58px;
            height: 42px;
            border: 12px solid #0f766e;
            border-bottom: 0;
            border-radius: 20px 20px 0 0;
            background: transparent;
        }
        .cylinder:after {
            content: "LPG";
            position: absolute;
            left: 22px;
            right: 22px;
            top: 70px;
            padding: 8px 0;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.92);
            color: #0f766e;
            font-size: 20px;
            font-weight: 800;
            text-align: center;
            letter-spacing: 0;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 overflow-x-hidden">
    @php
        $logoPath = $settings['logo_path'] ?? null;
    @endphp

    <nav class="fixed w-full z-50 bg-white/95 backdrop-blur border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-5 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="/" class="flex items-center gap-3 min-w-0">
                    <span class="w-10 h-10 rounded-xl bg-teal-700 flex items-center justify-center overflow-hidden shrink-0">
                        @if($logoPath)
                            <img src="{{ asset('storage/' . $logoPath) }}" alt="{{ $settings['site_name'] }}" class="w-full h-full object-contain bg-white p-1">
                        @else
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-1.945-1.164c-.143-.225-.35-.373-.572-.444a1 1 0 00-1.287.8 3.007 3.007 0 00-.164 1.084c.058 1.233.662 2.342 1.593 3.11.525.434 1.156.753 1.826.927a6.02 6.02 0 002.75.09c.925-.196 1.79-.673 2.456-1.37.726-.762 1.19-1.753 1.292-2.882a5.01 5.01 0 00-.49-2.678c-.293-.556-.674-1.047-1.063-1.468a13.372 13.372 0 00-.964-.94c.067-.434.144-.872.233-1.29.178-.84.388-1.59.604-2.166.11-.293.214-.538.302-.712a2.38 2.38 0 01.082-.134z"/></svg>
                        @endif
                    </span>
                    <span class="font-extrabold text-slate-900 truncate">{{ $settings['site_name'] }}</span>
                </a>

                <div class="hidden md:flex items-center gap-8 text-sm font-bold text-slate-600">
                    <a href="#layanan" class="hover:text-teal-700 transition">Layanan</a>
                    <a href="#alur" class="hover:text-teal-700 transition">Alur Agen</a>
                    <a href="#kontak" class="hover:text-teal-700 transition">Kontak</a>
                </div>

                @auth
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-slate-900 text-white rounded-lg text-sm font-bold hover:bg-black transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2.5 bg-teal-700 text-white rounded-lg text-sm font-bold hover:bg-teal-800 transition">{{ $settings['landing_primary_button'] }}</a>
                @endauth
            </div>
        </div>
    </nav>

    <header class="hero-pattern pt-28 lg:pt-32">
        <div class="max-w-7xl mx-auto px-5 lg:px-8 pb-16 lg:pb-20">
            <div class="grid lg:grid-cols-[1.05fr_0.95fr] gap-10 lg:gap-16 items-center">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-2 bg-white border border-teal-100 text-teal-800 rounded-lg text-xs font-extrabold uppercase">
                        <span class="w-2 h-2 rounded-full bg-teal-600"></span>
                        {{ $settings['landing_badge'] }}
                    </div>
                    <h1 class="mt-6 text-4xl sm:text-5xl lg:text-6xl font-black leading-tight text-slate-950">
                        {{ $settings['landing_title'] }}
                    </h1>
                    <p class="mt-6 max-w-2xl text-base lg:text-lg text-slate-600 leading-relaxed">
                        {{ $settings['landing_subtitle'] }}
                    </p>
                    <div class="mt-8 flex flex-col sm:flex-row gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-7 py-4 bg-teal-700 text-white rounded-xl font-bold text-center hover:bg-teal-800 transition">Buka Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="px-7 py-4 bg-teal-700 text-white rounded-xl font-bold text-center hover:bg-teal-800 transition">{{ $settings['landing_primary_button'] }}</a>
                        @endauth
                        <a href="#layanan" class="px-7 py-4 bg-white text-slate-800 border border-slate-200 rounded-xl font-bold text-center hover:bg-slate-100 transition">{{ $settings['landing_secondary_button'] }}</a>
                    </div>
                </div>

                <div class="relative">
                    <div class="bg-white border border-slate-200 rounded-2xl shadow-xl p-5 lg:p-6">
                        <div class="bg-slate-100 rounded-xl p-5 min-h-[360px] flex flex-col justify-between">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-xs font-black text-slate-500 uppercase">Gudang Agen LPG</p>
                                    <h2 class="text-2xl font-black text-slate-950 mt-1">Operasional Hari Ini</h2>
                                </div>
                                <span class="px-3 py-1.5 bg-emerald-100 text-emerald-800 rounded-lg text-xs font-black">Aktif</span>
                            </div>

                            <div class="grid grid-cols-[auto_1fr] gap-6 items-center my-8">
                                <div class="flex justify-center">
                                    <div class="cylinder"></div>
                                </div>
                                <div class="space-y-3">
                                    <div class="bg-white border border-slate-200 rounded-xl p-4">
                                        <p class="text-xs text-slate-500 font-bold">Stok Gudang</p>
                                        <p class="text-2xl font-black text-slate-950 mt-1">Terkontrol</p>
                                    </div>
                                    <div class="bg-white border border-slate-200 rounded-xl p-4">
                                        <p class="text-xs text-slate-500 font-bold">Surat Jalan</p>
                                        <p class="text-2xl font-black text-slate-950 mt-1">Siap Kirim</p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-3">
                                <div class="bg-white border border-slate-200 rounded-xl p-3">
                                    <p class="text-[11px] text-slate-500 font-bold">DO</p>
                                    <p class="text-lg font-black text-teal-700">Masuk</p>
                                </div>
                                <div class="bg-white border border-slate-200 rounded-xl p-3">
                                    <p class="text-[11px] text-slate-500 font-bold">Armada</p>
                                    <p class="text-lg font-black text-teal-700">Jalan</p>
                                </div>
                                <div class="bg-white border border-slate-200 rounded-xl p-3">
                                    <p class="text-[11px] text-slate-500 font-bold">Piutang</p>
                                    <p class="text-lg font-black text-teal-700">Pantau</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="layanan" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-5 lg:px-8">
            <div class="max-w-2xl">
                <p class="text-xs font-black text-teal-700 uppercase tracking-widest">Layanan Operasional</p>
                <h2 class="text-3xl lg:text-4xl font-black text-slate-950 mt-3">Dibuat untuk ritme kerja agen LPG.</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-5 mt-10">
                @for($i = 1; $i <= 3; $i++)
                    <div class="border border-slate-200 rounded-xl p-6 bg-white">
                        <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center mb-5">
                            @if($i === 1)
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            @elseif($i === 2)
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4v11m9 0h2m-2 0h-2m2 0V9h3l3 4v3h-2"/></svg>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            @endif
                        </div>
                        <h3 class="text-lg font-black text-slate-950">{{ $settings['landing_feature_' . $i . '_title'] }}</h3>
                        <p class="text-sm text-slate-600 leading-relaxed mt-3">{{ $settings['landing_feature_' . $i . '_body'] }}</p>
                    </div>
                @endfor
            </div>
        </div>
    </section>

    <section id="alur" class="py-20 bg-slate-950 text-white">
        <div class="max-w-7xl mx-auto px-5 lg:px-8">
            <div class="grid lg:grid-cols-[0.8fr_1.2fr] gap-10 items-start">
                <div>
                    <p class="text-xs font-black text-teal-300 uppercase tracking-widest">Alur Agen</p>
                    <h2 class="text-3xl lg:text-4xl font-black mt-3">Dari DO sampai rekap penjualan.</h2>
                    <p class="text-slate-300 text-sm leading-relaxed mt-5">Landing page ini menonjolkan proses nyata agen LPG: penebusan, stok gudang, pengiriman ke pangkalan, retur tabung, pembayaran, dan laporan.</p>
                </div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @for($i = 1; $i <= 4; $i++)
                        <div class="border border-white/10 bg-white/5 rounded-xl p-5">
                            <p class="text-2xl font-black text-white">{{ $settings['landing_stat_' . $i . '_value'] }}</p>
                            <p class="text-xs font-bold text-teal-200 mt-2 uppercase">{{ $settings['landing_stat_' . $i . '_label'] }}</p>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </section>

    <footer id="kontak" class="py-12 bg-white border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-5 lg:px-8 flex flex-col md:flex-row justify-between gap-6">
            <div>
                <p class="text-xl font-black text-slate-950">{{ $settings['site_name'] }}</p>
                <p class="text-sm text-slate-500 mt-2">Sistem manajemen distribusi untuk agen LPG.</p>
            </div>
            <div class="flex items-center gap-5 text-xs font-bold text-slate-500 uppercase">
                <a href="{{ route('terms') }}" class="hover:text-teal-700 transition">Syarat & Ketentuan</a>
                <span>&copy; 2026</span>
            </div>
        </div>
    </footer>
</body>
</html>
