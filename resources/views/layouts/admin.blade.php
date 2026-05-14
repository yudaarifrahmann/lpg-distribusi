<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard - LPG Distribution')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @yield('extra_css')
</head>
<body class="bg-gray-50 font-sans antialiased">
    @php
        $appName = \App\Models\AppSetting::getValue('site_name', 'LPG Distrib');
        $appLogo = \App\Models\AppSetting::getValue('logo_path');
    @endphp
    <div class="flex h-screen" x-data="{ sidebarOpen: true, masterDataOpen: {{ request()->is('master-data/*') ? 'true' : 'false' }}, reportOpen: {{ request()->is('laporan/*') ? 'true' : 'false' }}, settingsOpen: {{ request()->is('settings/*') ? 'true' : 'false' }} }">

        {{-- Overlay for mobile --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
             class="fixed inset-0 bg-black/50 z-20 lg:hidden" x-cloak></div>

        {{-- Sidebar --}}
        <aside class="fixed lg:static inset-y-0 left-0 z-30 w-64 bg-gray-900 text-gray-100 shadow-2xl transform transition-transform duration-300 ease-in-out overflow-y-auto flex flex-col no-scrollbar"
               :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">

            {{-- Logo --}}
            <div class="p-5 border-b border-gray-800">
                <h1 class="text-xl font-bold flex items-center">
                    <span class="w-9 h-9 bg-blue-600 rounded-lg flex items-center justify-center mr-3 shadow-lg overflow-hidden">
                        @if($appLogo)
                            <img src="{{ asset('storage/' . $appLogo) }}" alt="{{ $appName }}" class="w-full h-full object-contain bg-white p-1">
                        @else
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-1.945-1.164c-.143-.225-.35-.373-.572-.444a1 1 0 00-1.287.8 3.007 3.007 0 00-.164 1.084c.058 1.233.662 2.342 1.593 3.11.525.434 1.156.753 1.826.927a6.02 6.02 0 002.75.09c.925-.196 1.79-.673 2.456-1.37.726-.762 1.19-1.753 1.292-2.882a5.01 5.01 0 00-.49-2.678c-.293-.556-.674-1.047-1.063-1.468a13.372 13.372 0 00-.964-.94c.067-.434.144-.872.233-1.29.178-.84.388-1.59.604-2.166.11-.293.214-.538.302-.712a2.38 2.38 0 01.082-.134z"/></svg>
                        @endif
                    </span>
                    <span class="text-blue-500 truncate">{{ $appName }}</span>
                </h1>
            </div>

            {{-- User Info --}}
            <div class="p-4 border-b border-gray-800">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold">{{ Auth::user()->name }}</p>
                        <p class="text-xs mt-0.5">
                            @if(Auth::user()->hasRole('superadmin'))
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-500/20 text-red-400 border border-red-500/30">SuperAdmin</span>
                            @elseif(Auth::user()->hasRole('admin'))
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-500/20 text-blue-400 border border-blue-500/30">Admin</span>
                            @elseif(Auth::user()->hasRole('admin_keuangan'))
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Admin Keuangan</span>
                            @elseif(Auth::user()->hasRole('supir_knek'))
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-500/20 text-amber-400 border border-amber-500/30">Supir/Knek</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 mt-4 px-3 space-y-1">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                    Dashboard
                </a>

                @canany(['view master data', 'create master data'])
                <div>
                    <button @click="masterDataOpen = !masterDataOpen" class="sidebar-link w-full justify-between {{ request()->is('master-data/*') ? 'text-blue-400' : '' }}">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z"/><path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z"/><path d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z"/></svg>
                            Data Master
                        </span>
                        <svg class="w-4 h-4 transform transition-transform duration-200" :class="{ 'rotate-180': masterDataOpen }" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                    <div x-show="masterDataOpen" x-collapse class="ml-4 mt-1 space-y-1 border-l-2 border-gray-700 pl-3">
                        <a href="{{ route('pangkalan.index') }}" class="sidebar-link text-xs {{ request()->routeIs('pangkalan.*') ? 'active' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('pangkalan.*') ? 'bg-white' : 'bg-gray-600' }}"></span>Pangkalan
                        </a>
                        <a href="{{ route('truck.index') }}" class="sidebar-link text-xs {{ request()->routeIs('truck.*') ? 'active' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('truck.*') ? 'bg-white' : 'bg-gray-600' }}"></span>Truk
                        </a>
                        <a href="{{ route('driver.index') }}" class="sidebar-link text-xs {{ request()->routeIs('driver.*') ? 'active' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('driver.*') ? 'bg-white' : 'bg-gray-600' }}"></span>Supir / Knek
                        </a>
                        <a href="{{ route('lpg-price.index') }}" class="sidebar-link text-xs {{ request()->routeIs('lpg-price.*') ? 'active' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('lpg-price.*') ? 'bg-white' : 'bg-gray-600' }}"></span>Harga LPG
                        </a>
                        <a href="{{ route('expense-category.index') }}" class="sidebar-link text-xs {{ request()->routeIs('expense-category.*') ? 'active' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('expense-category.*') ? 'bg-white' : 'bg-gray-600' }}"></span>Kategori Pengeluaran
                        </a>
                        <a href="{{ route('branch.index') }}" class="sidebar-link text-xs {{ request()->routeIs('branch.*') ? 'active' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('branch.*') ? 'bg-white' : 'bg-gray-600' }}"></span>Cabang
                        </a>
                    </div>
                </div>
                @endcanany

                @can('view sa')
                <a href="{{ route('schedule-agreement.index') }}" class="sidebar-link {{ request()->routeIs('schedule-agreement.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/></svg>
                    Schedule Agreement
                </a>
                @endcan

                @can('view penebusan')
                <a href="{{ route('penebusan.index') }}" class="sidebar-link {{ request()->routeIs('penebusan.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/></svg>
                    Penebusan DO
                </a>
                @endcan

                <div class="pt-2 pb-1">
                    <p class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Gudang & Stok</p>
                </div>

                @if(auth()->user()->can('view stock') || auth()->user()->hasRole('supir_knek'))
                <a href="{{ route('stock.index') }}" class="sidebar-link {{ request()->routeIs('stock.index') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zM5 9a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zM5 13a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                    {{ auth()->user()->hasRole('supir_knek') ? 'Stok Kendaraan' : 'Stok' }}
                </a>
                @endif

                @if(auth()->user()->can('view stock') || auth()->user()->hasRole('supir_knek'))
                <a href="{{ route('retur.index') }}" class="sidebar-link {{ request()->routeIs('retur.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11 15l-3-3m0 0l3-3m-3 3h8m-13 5h18a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" clip-rule="evenodd"/></svg>
                    Retur Tabung
                </a>
                @if(!auth()->user()->hasRole('supir_knek'))
                <a href="{{ route('stock-history.index') }}" class="sidebar-link {{ request()->routeIs('stock-history.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/></svg>
                    Mutasi Stok
                </a>
                <a href="{{ route('titipan.index') }}" class="sidebar-link {{ request()->routeIs('titipan.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M11 17a1 1 0 001.447.894l4-2A1 1 0 0017 15V9.236a1 1 0 00-1.447-.894l-4 2a1 1 0 00-.553.894V17zM15.211 6.276a1 1 0 000-1.788l-4.764-2.382a1 1 0 00-.894 0L4.789 4.488a1 1 0 000 1.788l4.764 2.382a1 1 0 00.894 0l4.764-2.382zM4.447 8.342A1 1 0 003 9.236V15a1 1 0 00.553.894l4 2A1 1 0 009 17v-5.764a1 1 0 00-.553-.894l-4-2z"/></svg>
                    Titipan & Pinjam
                </a>
                @endif
                @endif

                @role('superadmin')
                <a href="{{ route('stock-adjustment.index') }}" class="sidebar-link {{ request()->routeIs('stock-adjustment.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/></svg>
                    Adjustment Stok
                </a>
                @endrole

                <div class="pt-2 pb-1">
                    <p class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Transaksi & Keuangan</p>
                </div>

                @canany(['view surat jalan', 'create surat jalan'])
                <a href="{{ route('surat-jalan.index') }}" class="sidebar-link {{ request()->routeIs('surat-jalan.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                    Surat Jalan
                </a>
                @endcanany

                @canany(['view penjualan', 'create penjualan'])
                <a href="{{ route('penjualan.index') }}" class="sidebar-link {{ request()->routeIs('penjualan.index') && !request()->has('history') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/></svg>
                    Penjualan & Pengeluaran
                </a>
                <a href="{{ route('penjualan.index', ['history' => 1]) }}" class="sidebar-link {{ request()->has('history') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                    History Penjualan
                </a>
                @endcanany

                @can('view piutang')
                <a href="{{ route('piutang.index') }}" class="sidebar-link {{ request()->routeIs('piutang.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                    Piutang / Cicilan
                </a>
                @endcan


                @can('view laporan')
                <div>
                    <button @click="reportOpen = !reportOpen" class="sidebar-link w-full justify-between {{ request()->is('laporan/*') ? 'text-blue-400' : '' }}">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm2 10a1 1 0 10-2 0v3a1 1 0 102 0v-3zm2-3a1 1 0 011 1v5a1 1 0 11-2 0v-5a1 1 0 011-1zm4-1a1 1 0 10-2 0v7a1 1 0 102 0V8z" clip-rule="evenodd"/></svg>
                            Laporan Keuangan
                        </span>
                        <svg class="w-4 h-4 transform transition-transform duration-200" :class="{ 'rotate-180': reportOpen }" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                    <div x-show="reportOpen" x-collapse class="ml-4 mt-1 space-y-1 border-l-2 border-gray-700 pl-3">
                        <a href="{{ route('report.global') }}" class="sidebar-link text-xs {{ request()->routeIs('report.global') ? 'active' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('report.global') ? 'bg-white' : 'bg-gray-600' }}"></span>Global
                        </a>
                        <a href="{{ route('report.penjualan') }}" class="sidebar-link text-xs {{ request()->routeIs('report.penjualan') ? 'active' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('report.penjualan') ? 'bg-white' : 'bg-gray-600' }}"></span>Penjualan
                        </a>
                        <a href="{{ route('report.pengeluaran') }}" class="sidebar-link text-xs {{ request()->routeIs('report.pengeluaran') ? 'active' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('report.pengeluaran') ? 'bg-white' : 'bg-gray-600' }}"></span>Pengeluaran
                        </a>
                        <a href="{{ route('report.laba-rugi') }}" class="sidebar-link text-xs {{ request()->routeIs('report.laba-rugi') ? 'active' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('report.laba-rugi') ? 'bg-white' : 'bg-gray-600' }}"></span>Laba Rugi
                        </a>
                        <a href="{{ route('report.piutang') }}" class="sidebar-link text-xs {{ request()->routeIs('report.piutang') ? 'active' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('report.piutang') ? 'bg-white' : 'bg-gray-600' }}"></span>Piutang
                        </a>
                        <a href="{{ route('report.stok') }}" class="sidebar-link text-xs {{ request()->routeIs('report.stok') ? 'active' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('report.stok') ? 'bg-white' : 'bg-gray-600' }}"></span>Stok & Retur
                        </a>
                    </div>
                </div>
                @endcan

                @can('view user management')
                <a href="{{ route('user-management.index') }}" class="sidebar-link {{ request()->routeIs('user-management.*') ? 'active' : '' }}"><svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>User Management</a>
                <a href="{{ route('audit-log.index') }}" class="sidebar-link {{ request()->routeIs('audit-log.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    Audit Log
                </a>
                @endcan

                @role('superadmin')
                <div>
                    <button @click="settingsOpen = !settingsOpen" class="sidebar-link w-full justify-between {{ request()->is('settings/*') ? 'text-blue-400' : '' }}">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/></svg>
                            Pengaturan
                        </span>
                        <svg class="w-4 h-4 transform transition-transform duration-200" :class="{ 'rotate-180': settingsOpen }" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                    <div x-show="settingsOpen" x-collapse class="ml-4 mt-1 space-y-1 border-l-2 border-gray-700 pl-3">
                        <a href="{{ route('settings.landing.edit') }}" class="sidebar-link text-xs {{ request()->routeIs('settings.landing.*') ? 'active' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('settings.landing.*') ? 'bg-white' : 'bg-gray-600' }}"></span>Landing Page
                        </a>
                        <a href="{{ route('backup.index') }}" class="sidebar-link text-xs {{ request()->routeIs('backup.*') ? 'active' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('backup.*') ? 'bg-white' : 'bg-gray-600' }}"></span>Backup Data
                        </a>
                    </div>
                </div>
                @endrole
            </nav>

            {{-- Logout --}}
            <div class="p-3 border-t border-gray-800">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sidebar-link w-full text-red-400 hover:bg-red-500/10 hover:text-red-300">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/></svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- Navbar --}}
            <nav class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                            </button>
                            <div class="ml-4 hidden sm:block">
                                <h2 class="text-lg font-semibold text-gray-700">@yield('page_title', 'Dashboard')</h2>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="relative" x-data="{ profileOpen: false }">
                                <button @click="profileOpen = !profileOpen" class="flex items-center text-sm border border-gray-200 rounded-lg px-3 py-2 hover:bg-gray-50 transition">
                                    <div class="relative mr-2">
                                        <div class="w-7 h-7 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xs">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                        @if(Auth::user()->unreadNotifications->count() > 0)
                                        <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 border-2 border-white rounded-full"></span>
                                        @endif
                                    </div>
                                    <span class="ml-2 text-gray-700 hidden sm:block">{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4 ml-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                </button>
                                <div x-show="profileOpen" @click.away="profileOpen = false" x-cloak
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                                    <div class="p-3 bg-gray-50 border-b">
                                        <p class="text-sm font-semibold text-gray-700">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                    </div>
                                    <div class="py-1">
                                        <a href="{{ route('notifications.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex justify-between items-center">
                                            <span>Notifikasi</span>
                                            @if(Auth::user()->unreadNotifications->count() > 0)
                                            <span class="bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-black">{{ Auth::user()->unreadNotifications->count() }}</span>
                                            @endif
                                        </a>
                                    </div>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">Keluar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            {{-- Flash Messages --}}
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                 class="mx-6 mt-4">
                <div class="flex items-center p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 shadow-sm">
                    <svg class="w-5 h-5 mr-3 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                    <button @click="show = false" class="ml-auto text-emerald-400 hover:text-emerald-600"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></button>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 class="mx-6 mt-4">
                <div class="flex items-center p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 shadow-sm">
                    <svg class="w-5 h-5 mr-3 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                    <button @click="show = false" class="ml-auto text-red-400 hover:text-red-600"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></button>
                </div>
            </div>
            @endif

            {{-- Page Content --}}
            <main class="flex-1 overflow-auto">
                <div class="p-6">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @yield('extra_js')
</body>
</html>
