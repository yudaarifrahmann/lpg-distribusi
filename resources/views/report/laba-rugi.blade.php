@extends('layouts.admin')
@section('title', 'Laporan Laba Rugi - LPG Distribution')
@section('page_title', 'Statement of Profit & Loss')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Filter --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 print:hidden">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 items-end gap-4">
            <div class="md:col-span-2">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Pilih Periode</label>
                <div class="flex items-center space-x-2">
                    <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm">
                    <span class="text-gray-300">s/d</span>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm">
                </div>
            </div>
            @if($isSuperAdmin)
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Cabang</label>
                <select name="branch_id" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm">
                    <option value="">Semua Cabang</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ $selectedBranchId == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-gray-900 text-white font-bold py-2.5 rounded-xl text-sm hover:bg-black transition">Filter</button>
                <button type="button" onclick="window.print()" class="p-2.5 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z"></path></svg>
                </button>
            </div>
        </form>
    </div>

    {{-- Report Content --}}
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-12 print:shadow-none print:border-none">
        <div class="text-center mb-12">
            <h1 class="text-2xl font-black text-gray-900 uppercase tracking-tighter">Laporan Laba Rugi</h1>
            <p class="text-sm text-gray-500 font-medium">Periode: {{ Carbon\Carbon::parse($startDate)->format('d F Y') }} - {{ Carbon\Carbon::parse($endDate)->format('d F Y') }}</p>
            <div class="w-12 h-1 bg-blue-600 mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="space-y-12">
            {{-- Pendapatan --}}
            <section>
                <div class="flex justify-between items-end border-b-2 border-gray-900 pb-2 mb-4">
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">I. Pendapatan</h3>
                    <span class="text-xs text-gray-400 font-bold italic">(Revenue)</span>
                </div>
                <div class="space-y-3 px-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 font-medium italic">Penjualan LPG 3Kg (Retail & Pangkalan)</span>
                        <span class="font-bold text-gray-900">Rp {{ number_format($totalPenjualan) }}</span>
                    </div>
                    <div class="flex justify-between pt-4 border-t border-dashed border-gray-200">
                        <span class="text-sm font-black text-gray-900 uppercase">Total Pendapatan Bersih</span>
                        <span class="text-sm font-black text-gray-900 underline decoration-double">Rp {{ number_format($totalPenjualan) }}</span>
                    </div>
                </div>
            </section>

            {{-- Harga Pokok Penjualan --}}
            <section>
                <div class="flex justify-between items-end border-b-2 border-gray-900 pb-2 mb-4">
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">II. Harga Pokok Penjualan (HPP)</h3>
                    <span class="text-xs text-gray-400 font-bold italic">(COGS)</span>
                </div>
                <div class="space-y-3 px-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 font-medium italic">Penebusan DO (Modal Pembelian LPG)</span>
                        <span class="font-bold text-gray-900">(Rp {{ number_format($totalPenebusan) }})</span>
                    </div>
                    <div class="flex justify-between pt-4 border-t border-dashed border-gray-200">
                        <span class="text-sm font-black text-gray-900 uppercase">Laba Kotor (Gross Profit)</span>
                        <span class="text-sm font-black text-emerald-600">Rp {{ number_format($labaKotor) }}</span>
                    </div>
                </div>
            </section>

            {{-- Biaya Operasional --}}
            <section>
                <div class="flex justify-between items-end border-b-2 border-gray-900 pb-2 mb-4">
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">III. Biaya Operasional</h3>
                    <span class="text-xs text-gray-400 font-bold italic">(Operating Expenses)</span>
                </div>
                <div class="space-y-3 px-4">
                    @forelse($expenseBreakdown as $exp)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 font-medium italic">Biaya {{ $exp['nama'] }}</span>
                        <span class="font-bold text-gray-700">Rp {{ number_format($exp['total']) }}</span>
                    </div>
                    @empty
                    <p class="text-xs text-gray-400 italic">Tidak ada biaya operasional.</p>
                    @endforelse
                    
                    <div class="flex justify-between pt-4 border-t border-dashed border-gray-200 text-red-600">
                        <span class="text-sm font-black uppercase">Total Biaya Operasional</span>
                        <span class="text-sm font-black">(Rp {{ number_format($totalExpense) }})</span>
                    </div>
                </div>
            </section>

            {{-- Laba Bersih Final --}}
            <section class="bg-gray-900 rounded-2xl p-8 text-white shadow-2xl">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">IV. Laba Bersih</h3>
                        <p class="text-lg font-bold">Net Profit / Loss</p>
                    </div>
                    <div class="text-right">
                        <h2 class="text-4xl font-black {{ $labaBersih >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                            Rp {{ number_format($labaBersih) }}
                        </h2>
                        <p class="text-[10px] text-gray-400 italic mt-1">Laba setelah dikurangi HPP dan Biaya Operasional</p>
                    </div>
                </div>
            </section>
        </div>

        <div class="mt-16 flex justify-between items-center text-[10px] text-gray-400 border-t border-gray-100 pt-8 italic">
            <span>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</span>
            <span>Oleh: {{ Auth::user()->name }}</span>
        </div>
    </div>
</div>
@endsection
