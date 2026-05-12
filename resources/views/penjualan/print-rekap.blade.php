<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - {{ now()->format('d/m/Y') }}</title>
    <style>
        body { font-family: 'Inter', sans-serif; font-size: 10px; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 10px; }
        
        .filter-info { margin-bottom: 15px; font-style: italic; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { border: 1px solid #000; padding: 6px; background: #f3f4f6; text-transform: uppercase; font-size: 9px; }
        td { border: 1px solid #000; padding: 6px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        
        .summary-box { display: flex; justify-content: flex-end; margin-top: 20px; }
        .summary-table { width: 250px; }
        .summary-table td { border: none; padding: 4px 0; }
        .summary-table tr.total-row td { border-top: 1px solid #000; font-weight: bold; padding-top: 8px; font-size: 12px; }
        
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 15px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #111827; color: #fff; border: none; cursor: pointer;">Cetak Laporan</button>
    </div>

    <div class="header">
        <h1>Laporan Rekap Penjualan LPG</h1>
        <p>PT. LPG DISTRIBUSI SEJAHTERA</p>
        <p>Periode: {{ request('start_date') ? Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : '-' }} s/d {{ request('end_date') ? Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : '-' }}</p>
    </div>

    <div class="filter-info">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }} | 
        Filter: {{ request('pangkalan_id') ? 'Pangkalan Tertentu' : 'Semua Pangkalan' }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Invoice</th>
                <th>Pangkalan</th>
                <th>Armada / Supir</th>
                <th class="text-right">Qty</th>
                <th class="text-center">Retur</th>
                <th class="text-center">Retur Gudang</th>
                <th class="text-right">Total (Rp)</th>
                <th class="text-right">Tunai</th>
                <th class="text-right">Transfer</th>
                <th class="text-right">Hutang</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualans as $index => $p)
            @php $piutang = $p->total_penjualan - $p->nominal_cash - $p->nominal_transfer; @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $p->tanggal_penjualan->format('d/m/y') }}</td>
                <td>{{ $p->nomor_invoice }}</td>
                <td>{{ $p->pangkalan->nama_pangkalan }}</td>
                <td>{{ $p->truck->nomor_polisi }} / {{ $p->supir->nama }}</td>
                <td class="text-right">{{ number_format($p->jumlah_tabung) }}</td>
                <td class="text-center">{{ $p->returs->sum('jumlah_retur') ?: '-' }}</td>
                <td class="text-center">{{ $p->returs->where('status_retur', 'diterima')->sum('jumlah_retur') ?: '-' }}</td>
                <td class="text-right">{{ number_format($p->total_penjualan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($p->nominal_cash, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($p->nominal_transfer, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($piutang, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="font-bold" style="background: #f9fafb;">
                <td colspan="5" class="text-right">TOTAL</td>
                <td class="text-right">{{ number_format($summary['total_tabung']) }}</td>
                <td class="text-center">{{ number_format($summary['total_retur']) }}</td>
                <td class="text-center">{{ number_format($summary['total_sisa_kembali']) }}</td>
                <td class="text-right">{{ number_format($summary['total_omzet'], 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($summary['total_cash'], 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($summary['total_transfer'], 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($summary['total_piutang'], 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="summary-box">
        <table class="summary-table">
            <tr>
                <td>Total Tabung Terjual</td>
                <td class="text-right">{{ number_format($summary['total_tabung']) }} Tabung</td>
            </tr>
            <tr>
                <td>Total Tabung Retur (Bocor/Rusak)</td>
                <td class="text-right">{{ number_format($summary['total_retur']) }} Tabung</td>
            </tr>
            <tr>
                <td>Total Pengembalian Sisa Stok</td>
                <td class="text-right">{{ number_format($summary['total_sisa_kembali'] - $summary['total_retur']) }} Tabung</td>
            </tr>
            <tr style="border-top: 1px solid #eee;">
                <td class="font-bold">TOTAL KEMBALI KE GUDANG</td>
                <td class="text-right font-bold">{{ number_format($summary['total_sisa_kembali']) }} Tabung</td>
            </tr>
            <tr>
                <td>Total Pemasukan Tunai</td>
                <td class="text-right">Rp {{ number_format($summary['total_cash'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Pemasukan Transfer</td>
                <td class="text-right">Rp {{ number_format($summary['total_transfer'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Piutang Berjalan</td>
                <td class="text-right">Rp {{ number_format($summary['total_piutang'], 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td>GRAND TOTAL OMZET</td>
                <td class="text-right">Rp {{ number_format($summary['total_omzet'], 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 50px;">
        <table style="width: 100%; border: none;">
            <tr style="border: none;">
                <td style="border: none; text-align: center; width: 33%;">
                    Dibuat Oleh,<br><br><br><br>
                    ( ................................ )
                </td>
                <td style="border: none; text-align: center; width: 33%;">
                </td>
                <td style="border: none; text-align: center; width: 33%;">
                    Disetujui Oleh,<br><br><br><br>
                    ( ................................ )
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
