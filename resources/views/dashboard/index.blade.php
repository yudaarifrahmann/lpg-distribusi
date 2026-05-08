@extends('layouts.admin')

@section('title', 'Dashboard - LPG Distribution')
@section('page_title', 'Dashboard Statistics')

@section('content')
{{-- Summary Row 1 --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-gray-900 p-6 rounded-2xl shadow-xl text-white">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Laba Bersih Bulan Ini</p>
        <h3 class="text-2xl font-black {{ $monthlyLaba['net'] >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
            Rp {{ number_format($monthlyLaba['net']) }}
        </h3>
        <p class="text-[10px] text-gray-500 italic mt-2">Omzet - (Penebusan + Operasional)</p>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Omzet Hari Ini</p>
        <h3 class="text-2xl font-black text-emerald-600">Rp {{ number_format($totalPenjualanHariIni) }}</h3>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Stok Gudang</p>
        <h3 class="text-2xl font-black text-blue-600">{{ number_format($stokSaatIni) }} <span class="text-xs font-normal text-gray-400">Pcs</span></h3>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Stok Kendaraan</p>
        <h3 class="text-2xl font-black text-amber-600">{{ number_format($stokKendaraanTotal) }} <span class="text-xs font-normal text-gray-400">Pcs</span></h3>
    </div>
</div>

{{-- Charts Section --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    {{-- Sales Trend Chart --}}
    <div class="lg:col-span-2 bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h4 class="text-lg font-black text-gray-800">Tren Penjualan (14 Hari)</h4>
                <p class="text-xs text-gray-400">Data omzet harian sistem distribusi</p>
            </div>
            <div class="p-2 bg-gray-50 rounded-lg text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                Real-time Data
            </div>
        </div>
        <div class="h-80 w-full relative">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    {{-- Top Pangkalan --}}
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <h4 class="text-lg font-black text-gray-800 mb-8">Top Pangkalan (Bulan Ini)</h4>
        <div class="space-y-6">
            @foreach($topPangkalan as $p)
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="font-bold text-gray-700">{{ $p->nama_pangkalan }}</span>
                    <span class="font-black text-blue-600">{{ $p->total }} Pcs</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-300 h-2 rounded-full" style="width: {{ ($p->total / max($topPangkalan->pluck('total')->toArray() ?: [1])) * 100 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Detailed Summaries --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    {{-- Financial Health --}}
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <h4 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-6">Kesehatan Keuangan</h4>
        <div class="space-y-4">
            <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex justify-between items-center">
                <span class="text-xs font-bold text-emerald-700">Omzet</span>
                <span class="text-sm font-black text-emerald-800">Rp {{ number_format($monthlyLaba['penjualan']) }}</span>
            </div>
            <div class="p-4 bg-red-50 rounded-2xl border border-red-100 flex justify-between items-center">
                <span class="text-xs font-bold text-red-700">Modal DO</span>
                <span class="text-sm font-black text-red-800">(Rp {{ number_format($monthlyLaba['penebusan']) }})</span>
            </div>
            <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100 flex justify-between items-center">
                <span class="text-xs font-bold text-amber-700">Operasional</span>
                <span class="text-sm font-black text-amber-800">(Rp {{ number_format($monthlyLaba['pengeluaran']) }})</span>
            </div>
        </div>
    </div>

    {{-- Stock & Returns --}}
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <h4 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-6">Aset & Inventaris</h4>
        <div class="flex flex-col items-center justify-center h-48 border-2 border-dashed border-gray-100 rounded-3xl">
            <h2 class="text-5xl font-black text-gray-800">{{ $stokSaatIni + $stokKendaraanTotal }}</h2>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-2">Total Tabung Beredar</p>
            <div class="flex space-x-4 mt-6">
                <div class="text-center">
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Retur</p>
                    <p class="text-sm font-black text-blue-600">{{ $totalReturHariIni }}</p>
                </div>
                <div class="w-px h-8 bg-gray-100"></div>
                <div class="text-center">
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Rusak</p>
                    <p class="text-sm font-black text-red-600">{{ $totalTabungRusak }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Receivables Status --}}
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex flex-col">
        <h4 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-6">Status Piutang</h4>
        <div class="flex-1 flex flex-col justify-center">
            <h2 class="text-3xl font-black text-amber-600 tracking-tighter">Rp {{ number_format($totalPiutangAktif) }}</h2>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6">Total Piutang Aktif</p>
            
            <div class="grid grid-cols-2 gap-4">
                <div class="p-3 bg-gray-50 rounded-xl">
                    <p class="text-[10px] text-gray-400 font-bold uppercase">Belum Bayar</p>
                    <p class="text-sm font-bold text-gray-700">{{ $totalBelumLunas }} Org</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-xl">
                    <p class="text-[10px] text-gray-400 font-bold uppercase">Mencicil</p>
                    <p class="text-sm font-bold text-gray-700">{{ $totalCicilan }} Org</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($salesChart->pluck('date')) !!},
            datasets: [{
                label: 'Omzet',
                data: {!! json_encode($salesChart->pluck('total')) !!},
                backgroundColor: '#3b82f6',
                borderRadius: 8,
                barThickness: 20
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f3f4f6' },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + (value/1000000).toFixed(1) + 'jt';
                        }
                    }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
</script>
@endsection
