<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $penjualan->nomor_invoice }}</title>
    <style>
        body { font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 11px; color: #333; margin: 0; padding: 30px; line-height: 1.4; }
        .invoice-box { max-width: 800px; margin: auto; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #f3f4f6; padding-bottom: 20px; margin-bottom: 30px; }
        .company-info h1 { margin: 0; font-size: 20px; color: #1e40af; font-weight: 800; letter-spacing: -0.5px; }
        .company-info p { margin: 2px 0; color: #6b7280; font-size: 10px; }
        .invoice-title { text-align: right; }
        .invoice-title h2 { margin: 0; font-size: 24px; color: #111827; font-weight: 900; text-transform: uppercase; }
        .invoice-title p { margin: 5px 0 0; font-size: 12px; font-weight: 600; color: #3b82f6; }
        
        .info-grid { display: grid; grid-template-cols: 1fr 1fr; gap: 40px; margin-bottom: 40px; }
        .info-section h3 { font-size: 9px; font-bold; text-transform: uppercase; color: #9ca3af; margin-bottom: 8px; letter-spacing: 1px; }
        .info-card { background: #f9fafb; border-radius: 12px; padding: 15px; border: 1px solid #f3f4f6; }
        .info-card p { margin: 4px 0; font-size: 11px; font-weight: 600; }
        .info-card .label { color: #6b7280; font-weight: 400; width: 100px; display: inline-block; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background: #111827; color: #fff; text-align: left; padding: 12px 15px; font-size: 9px; text-transform: uppercase; letter-spacing: 1px; }
        td { padding: 15px; border-bottom: 1px solid #f3f4f6; font-size: 11px; }
        .text-right { text-align: right; }
        .font-bold { font-weight: 700; }
        
        .totals-section { display: flex; justify-content: flex-end; }
        .totals-table { width: 300px; }
        .totals-table td { padding: 8px 0; border: none; }
        .totals-table tr.grand-total td { border-top: 2px solid #111827; padding-top: 15px; font-size: 16px; font-weight: 900; color: #1e40af; }
        
        .footer { margin-top: 60px; text-align: center; color: #9ca3af; font-size: 9px; }
        .signatures { display: grid; grid-template-cols: 1fr 1fr; gap: 100px; margin-top: 50px; }
        .signature-box { text-align: center; }
        .signature-line { margin-top: 60px; border-top: 1px solid #d1d5db; width: 200px; margin-left: auto; margin-right: auto; padding-top: 5px; font-weight: 700; }
        
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 9px; font-weight: 800; text-transform: uppercase; }
        .badge-lunas { background: #dcfce7; color: #166534; }
        .badge-hutang { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #3b82f6; color: #fff; border: none; border-radius: 8px; cursor: pointer; font-weight: 700;">Cetak Sekarang</button>
    </div>

    <div class="invoice-box">
        <div class="header">
            <div class="company-info">
                <h1>PT. LPG DISTRIBUSI SEJAHTERA</h1>
                <p>Jl. Raya Utama No. 45, Kawasan Industri Hijau</p>
                <p>Email: finance@lpg-distribusi.com | Telp: (021) 555-0199</p>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <p>#{{ $penjualan->nomor_invoice }}</p>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-section">
                <h3>Ditagihkan Kepada:</h3>
                <div class="info-card">
                    <p><span class="label">Pangkalan</span>: {{ $penjualan->pangkalan->nama_pangkalan }}</p>
                    <p><span class="label">Pemilik</span>: {{ $penjualan->pangkalan->nama_pemilik }}</p>
                    <p><span class="label">Alamat</span>: {{ $penjualan->pangkalan->alamat }}</p>
                    <p><span class="label">No. HP</span>: {{ $penjualan->pangkalan->no_hp }}</p>
                </div>
            </div>
            <div class="info-section">
                <h3>Detail Transaksi:</h3>
                <div class="info-card">
                    <p><span class="label">Tanggal</span>: {{ $penjualan->tanggal_penjualan->format('d F Y') }}</p>
                    <p><span class="label">Armada</span>: {{ $penjualan->truck->nomor_polisi }}</p>
                    <p><span class="label">Supir</span>: {{ $penjualan->supir->nama }}</p>
                    <p><span class="label">Metode</span>: 
                        <span class="badge {{ $penjualan->status_pembayaran == 'lunas' ? 'badge-lunas' : 'badge-hutang' }}">
                            {{ strtoupper($penjualan->metode_pembayaran) }} ({{ strtoupper($penjualan->status_pembayaran) }})
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Deskripsi Barang</th>
                    <th class="text-right">Kuantitas</th>
                    <th class="text-right">Harga Satuan</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td class="font-bold">LPG 3Kg (Isi Tabung)</td>
                    <td class="text-right">{{ number_format($penjualan->jumlah_tabung) }} Tabung</td>
                    <td class="text-right">Rp {{ number_format($penjualan->harga_satuan, 0, ',', '.') }}</td>
                    <td class="text-right font-bold">Rp {{ number_format($penjualan->total_penjualan, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td>Subtotal</td>
                    <td class="text-right">Rp {{ number_format($penjualan->total_penjualan, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Potongan/Diskon</td>
                    <td class="text-right">Rp 0</td>
                </tr>
                <tr>
                    <td>Pembayaran Tunai (Cash)</td>
                    <td class="text-right">Rp {{ number_format($penjualan->nominal_cash, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Pembayaran Transfer</td>
                    <td class="text-right">Rp {{ number_format($penjualan->nominal_transfer, 0, ',', '.') }}</td>
                </tr>
                @if($penjualan->piutang)
                <tr>
                    <td style="color: #ef4444; font-weight: 700;">Sisa Tagihan (Hutang)</td>
                    <td class="text-right" style="color: #ef4444; font-weight: 700;">Rp {{ number_format($penjualan->piutang->sisa_tagihan, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr class="grand-total">
                    <td>TOTAL AKHIR</td>
                    <td class="text-right">Rp {{ number_format($penjualan->total_penjualan, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 40px; border-top: 1px solid #f3f4f6; padding-top: 20px;">
            <p style="font-size: 10px; color: #6b7280;"><span class="font-bold">Catatan:</span> {{ $penjualan->catatan ?? 'Terima kasih atas kepercayaan Anda.' }}</p>
        </div>

        <div class="signatures">
            <div class="signature-box">
                <p>Hormat Kami,</p>
                <div class="signature-line">Administrasi</div>
            </div>
            <div class="signature-box">
                <p>Penerima,</p>
                <div class="signature-line">{{ $penjualan->pangkalan->nama_pemilik }}</div>
            </div>
        </div>

        <div class="footer">
            <p>Invoice ini sah dan dicetak secara otomatis oleh Sistem Manajemen LPG Distribusi.</p>
        </div>
    </div>
</body>
</html>
