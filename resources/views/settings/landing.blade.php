@extends('layouts.admin')
@section('title', 'Pengaturan Landing Page - LPG Distribution')
@section('page_title', 'Pengaturan Landing Page')

@section('content')
@php
    $logoPath = $settings['logo_path'] ?? null;
@endphp

<div class="max-w-6xl mx-auto">
    <form action="{{ route('settings.landing.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div>
                    <p class="text-xs font-black text-blue-600 uppercase tracking-widest">Branding</p>
                    <h3 class="text-2xl font-black text-gray-900 mt-1">Logo dan Identitas Agen</h3>
                    <p class="text-sm text-gray-500 mt-2">Logo ini tampil di sidebar admin dan landing page.</p>
                </div>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-xl font-bold shadow-lg hover:bg-blue-700 transition">
                    Simpan Pengaturan
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
                <div class="lg:col-span-1">
                    <div class="border border-gray-200 rounded-2xl p-5 bg-gray-50">
                        <div class="w-28 h-28 rounded-2xl bg-white border border-gray-200 flex items-center justify-center overflow-hidden">
                            @if($logoPath)
                                <img src="{{ asset('storage/' . $logoPath) }}" alt="Logo" class="w-full h-full object-contain p-3">
                            @else
                                <span class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center text-white">
                                    <svg class="w-9 h-9" fill="currentColor" viewBox="0 0 20 20"><path d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-1.945-1.164c-.143-.225-.35-.373-.572-.444a1 1 0 00-1.287.8 3.007 3.007 0 00-.164 1.084c.058 1.233.662 2.342 1.593 3.11.525.434 1.156.753 1.826.927a6.02 6.02 0 002.75.09c.925-.196 1.79-.673 2.456-1.37.726-.762 1.19-1.753 1.292-2.882a5.01 5.01 0 00-.49-2.678c-.293-.556-.674-1.047-1.063-1.468a13.372 13.372 0 00-.964-.94c.067-.434.144-.872.233-1.29.178-.84.388-1.59.604-2.166.11-.293.214-.538.302-.712a2.38 2.38 0 01.082-.134z"/></svg>
                                </span>
                            @endif
                        </div>
                        <label class="block mt-5">
                            <span class="text-xs font-bold text-gray-600 uppercase">Upload Logo</span>
                            <input type="file" name="logo" accept="image/*" class="mt-2 block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-bold hover:file:bg-blue-100">
                        </label>
                        @error('logo')<p class="text-xs text-red-600 mt-2">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="block">
                        <span class="text-xs font-bold text-gray-600 uppercase">Nama Agen</span>
                        <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" class="mt-2 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                        @error('site_name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </label>
                    <label class="block">
                        <span class="text-xs font-bold text-gray-600 uppercase">Badge Hero</span>
                        <input type="text" name="landing_badge" value="{{ old('landing_badge', $settings['landing_badge']) }}" class="mt-2 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                        @error('landing_badge')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </label>
                    <label class="block md:col-span-2">
                        <span class="text-xs font-bold text-gray-600 uppercase">Judul Landing Page</span>
                        <input type="text" name="landing_title" value="{{ old('landing_title', $settings['landing_title']) }}" class="mt-2 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                        @error('landing_title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </label>
                    <label class="block md:col-span-2">
                        <span class="text-xs font-bold text-gray-600 uppercase">Deskripsi Hero</span>
                        <textarea name="landing_subtitle" rows="3" class="mt-2 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">{{ old('landing_subtitle', $settings['landing_subtitle']) }}</textarea>
                        @error('landing_subtitle')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </label>
                    <label class="block">
                        <span class="text-xs font-bold text-gray-600 uppercase">Tombol Utama</span>
                        <input type="text" name="landing_primary_button" value="{{ old('landing_primary_button', $settings['landing_primary_button']) }}" class="mt-2 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                        @error('landing_primary_button')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </label>
                    <label class="block md:col-span-2">
                        <span class="text-xs font-bold text-gray-600 uppercase">Tombol Kedua</span>
                        <input type="text" name="landing_secondary_button" value="{{ old('landing_secondary_button', $settings['landing_secondary_button']) }}" class="mt-2 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                        @error('landing_secondary_button')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </label>
                    <label class="block md:col-span-2">
                        <span class="text-xs font-bold text-gray-600 uppercase">Nomor WhatsApp Admin (Untuk Reset Password)</span>
                        <input type="text" name="whatsapp_admin" value="{{ old('whatsapp_admin', $settings['whatsapp_admin'] ?? '') }}" class="mt-2 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: 6281234567890">
                        <p class="text-[10px] text-gray-400 mt-1">Gunakan format 628... tanpa spasi/tanda hubung.</p>
                        @error('whatsapp_admin')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </label>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="font-black text-gray-900 mb-5">Konten Layanan</h4>
                @for($i = 1; $i <= 3; $i++)
                    <div class="border-t border-gray-100 py-5 first:border-t-0 first:pt-0">
                        <label class="block">
                            <span class="text-xs font-bold text-gray-600 uppercase">Judul Layanan {{ $i }}</span>
                            <input type="text" name="landing_feature_{{ $i }}_title" value="{{ old('landing_feature_' . $i . '_title', $settings['landing_feature_' . $i . '_title']) }}" class="mt-2 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                        </label>
                        <label class="block mt-3">
                            <span class="text-xs font-bold text-gray-600 uppercase">Deskripsi Layanan {{ $i }}</span>
                            <textarea name="landing_feature_{{ $i }}_body" rows="3" class="mt-2 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">{{ old('landing_feature_' . $i . '_body', $settings['landing_feature_' . $i . '_body']) }}</textarea>
                        </label>
                    </div>
                @endfor
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="font-black text-gray-900 mb-5">Statistik Landing Page</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @for($i = 1; $i <= 4; $i++)
                        <div class="border border-gray-100 rounded-2xl p-4">
                            <label class="block">
                                <span class="text-xs font-bold text-gray-600 uppercase">Nilai {{ $i }}</span>
                                <input type="text" name="landing_stat_{{ $i }}_value" value="{{ old('landing_stat_' . $i . '_value', $settings['landing_stat_' . $i . '_value']) }}" class="mt-2 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                            </label>
                            <label class="block mt-3">
                                <span class="text-xs font-bold text-gray-600 uppercase">Label {{ $i }}</span>
                                <input type="text" name="landing_stat_{{ $i }}_label" value="{{ old('landing_stat_' . $i . '_label', $settings['landing_stat_' . $i . '_label']) }}" class="mt-2 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">
                            </label>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
