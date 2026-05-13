@extends('layouts.admin')
@section('title', 'Tambah Penebusan - LPG Distribution')
@section('page_title', 'Catat Penebusan DO')

@section('content')
<div class="max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('penebusan.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800">Form Penebusan DO</h3>
            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">Harga: Rp 6.487.298 / DO</span>
        </div>
        <form action="{{ route('penebusan.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor DO <span class="text-red-500">*</span></label>
                    <input type="text" name="nomor_do" value="{{ old('nomor_do') }}" required placeholder="Contoh: 800012345" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
                    @error('nomor_do')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Penebusan <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_penebusan" value="{{ old('tanggal_penebusan', date('Y-m-d')) }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
                    @error('tanggal_penebusan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Schedule Agreement (SA) <span class="text-red-500">*</span></label>
                <select name="schedule_agreement_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
                    <option value="">-- Pilih SA --</option>
                    @foreach($sas as $sa)
                        <option value="{{ $sa->id }}" {{ (old('schedule_agreement_id') == $sa->id || ($selectedSa && $selectedSa->id == $sa->id)) ? 'selected' : '' }}>
                            SA {{ $sa->tanggal_sa->format('d/m/Y') }} - {{ $sa->jumlah_do }} DO ({{ number_format($sa->jumlah_tabung) }} tabung)
                            @if(!$isDriver)
                                | Supir: {{ $sa->driver->nama ?? '-' }}
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('schedule_agreement_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            @if($isDriver)
                <input type="hidden" name="driver_id" value="{{ $driverProfile->id }}">
                <div class="p-4 bg-blue-50 rounded-xl border border-blue-100">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                Penebusan akan dicatat atas nama <strong>{{ $driverProfile->nama }}</strong>.
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Supir / Knek <span class="text-red-500">*</span></label>
                    <select name="driver_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition">
                        <option value="">-- Pilih Personil --</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}" {{ (old('driver_id') == $driver->id || ($selectedSa && $selectedSa->driver_id == $driver->id)) ? 'selected' : '' }}>
                                {{ $driver->nama }} ({{ $driver->truck->nomor_polisi ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Truck armada akan ditentukan secara otomatis berdasarkan SA atau Truck Default dari supir.</p>
                    @error('driver_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            @endif

            <div x-data="{ photoName: null, photoPreview: null }">
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Nota Penebusan (Opsional)</label>
                <div class="mt-1 flex items-center">
                    <input type="file" name="foto_nota" class="hidden" x-ref="photo"
                        @change="
                            photoName = $refs.photo.files[0].name;
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                photoPreview = e.target.result;
                            };
                            reader.readAsDataURL($refs.photo.files[0]);
                        ">
                    <button type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" @click.prevent="$refs.photo.click()">
                        Pilih Foto
                    </button>
                    <span class="ml-3 text-sm text-gray-500" x-text="photoName ? photoName : 'Belum ada foto dipilih'"></span>
                </div>
                <template x-if="photoPreview">
                    <div class="mt-4">
                        <img :src="photoPreview" class="h-40 w-auto rounded-lg shadow-sm border border-gray-200">
                    </div>
                </template>
                @error('foto_nota')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="pt-4 flex items-center justify-end">
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/50 transition duration-200">
                    Proses Penebusan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
