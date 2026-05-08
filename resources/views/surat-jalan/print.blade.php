<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak SJ - {{ $suratJalan->nomor_surat_jalan }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; font-size: 12px; color: #333; margin: 40px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 5px 0 0; }
        .info-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .info-table td { padding: 5px; vertical-align: top; }
        .content-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .content-table th, .content-table td { border: 1px solid #000; padding: 10px; text-align: left; }
        .footer-table { width: 100%; margin-top: 50px; }
        .footer-table td { width: 33%; text-align: center; }
        .signature-space { height: 80px; }
        @media print {
            .no-print { display: none; }
            body { margin: 20px; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="background: #fdf6e3; padding: 10px; border: 1px solid #eee; margin-bottom: 20px; border-radius: 5px;">
        <p>Tips: Gunakan <b>Ctrl + P</b> untuk mencetak dokumen ini. Simpan sebagai PDF jika perlu.</p>
    </div>

    <div class="header">
        <h1>PT. LPG DISTRIBUSI SEJAHTERA</h1>
        <p>Jl. Raya Gas No. 123, Kota Industri - Telp: (021) 8888-9999</p>
    </div>

    <h2 style="text-align: center; text-decoration: underline;">SURAT JALAN PENGIRIMAN</h2>

    <table class="info-table">
        <tr>
            <td style="width: 15%;">No. SJ</td>
            <td style="width: 35%;">: <b>{{ $suratJalan->nomor_surat_jalan }}</b></td>
            <td style="width: 15%;">Armada</td>
            <td style="width: 35%;">: {{ $suratJalan->truck->nomor_polisi }} ({{ $suratJalan->truck->nama_truk }})</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: {{ $suratJalan->tanggal_berangkat->format('d/m/Y') }}</td>
            <td>Supir</td>
            <td>: {{ $suratJalan->supir->nama }}</td>
        </tr>
        <tr>
            <td>No. DO</td>
            <td>: #{{ $suratJalan->penebusan->nomor_do }}</td>
            <td>Knek</td>
            <td>: {{ $suratJalan->knek->nama ?? '-' }}</td>
        </tr>
    </table>

    <table class="content-table">
        <thead>
            <tr style="background: #eee;">
                <th style="width: 10%;">No</th>
                <th>Keterangan Barang</th>
                <th style="width: 20%; text-align: right;">Jumlah (Tabung)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>
                    LPG 3Kg (Isi)<br>
                    <small>Catatan: {{ $suratJalan->catatan ?? 'Sesuai pesanan' }}</small>
                </td>
                <td style="text-align: right; font-weight: bold;">{{ number_format($suratJalan->jumlah_tabung) }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr style="font-weight: bold;">
                <td colspan="2" style="text-align: right;">TOTAL MUATAN</td>
                <td style="text-align: right;">{{ number_format($suratJalan->jumlah_tabung) }}</td>
            </tr>
        </tfoot>
    </table>

    <p>Kondisi barang telah diperiksa dan diterima dalam keadaan baik dan cukup.</p>

    <table class="footer-table">
        <tr>
            <td>
                Dibuat Oleh,<br>
                (Admin Gudang)
                <div class="signature-space"></div>
                ____________________
            </td>
            <td>
                Dibawa Oleh,<br>
                (Supir Utama)
                <div class="signature-space"></div>
                ____________________
            </td>
            <td>
                Diterima Oleh,<br>
                (Petugas Lokasi)
                <div class="signature-space"></div>
                ____________________
            </td>
        </tr>
    </table>

</body>
</html>
