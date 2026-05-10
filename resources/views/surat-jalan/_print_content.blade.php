<div id="capture-container" style="padding: 30px; background-color: #ffffff; border: 2px solid #000000; font-family: 'Courier New', Courier, monospace; width: 800px; color: #000000;">
    <div style="text-align: center; border-bottom: 2px solid #000000; padding-bottom: 15px; margin-bottom: 25px;">
        <h1 style="text-transform: uppercase; font-size: 24px; font-weight: bold; margin: 0;">PT. LPG DISTRIBUSI SEJAHTERA</h1>
        <p style="font-size: 14px; margin: 5px 0 0;">Jl. Raya Gas No. 123, Kota Industri - Telp: (021) 8888-9999</p>
    </div>

    <h2 style="text-align: center; text-decoration: underline; font-size: 20px; font-weight: bold; margin-bottom: 25px;">SURAT JALAN PENGIRIMAN</h2>

    <table style="width: 100%; margin-bottom: 25px; font-size: 14px; border-collapse: collapse;">
        <tr>
            <td style="width: 15%; padding: 4px 0;">No. SJ</td>
            <td style="width: 35%; padding: 4px 0; font-weight: bold;">: {{ $suratJalan->nomor_surat_jalan }}</td>
            <td style="width: 15%; padding: 4px 0;">Armada</td>
            <td style="width: 35%; padding: 4px 0;">: {{ $suratJalan->truck->nomor_polisi }} ({{ $suratJalan->truck->nama_truk }})</td>
        </tr>
        <tr>
            <td style="padding: 4px 0;">Tanggal</td>
            <td style="padding: 4px 0;">: {{ $suratJalan->tanggal_berangkat->format('d/m/Y') }}</td>
            <td style="padding: 4px 0;">Supir</td>
            <td style="padding: 4px 0;">: {{ $suratJalan->supir->nama }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 0;">No. DO</td>
            <td style="padding: 4px 0;">: {{ $suratJalan->penebusan ? '#'.$suratJalan->penebusan->nomor_do : '(Muat Gudang)' }}</td>
            <td style="padding: 4px 0;">Knek</td>
            <td style="padding: 4px 0;">: {{ $suratJalan->knek->nama ?? '-' }}</td>
        </tr>
    </table>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px; font-size: 14px; border: 1px solid #000000;">
        <thead>
            <tr style="background-color: #f3f4f6; border: 1px solid #000000;">
                <th style="border: 1px solid #000000; padding: 10px; text-align: left; width: 10%;">No</th>
                <th style="border: 1px solid #000000; padding: 10px; text-align: left;">Keterangan Barang</th>
                <th style="border: 1px solid #000000; padding: 10px; text-align: right; width: 25%;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr style="border: 1px solid #000000;">
                <td style="border: 1px solid #000000; padding: 10px;">1</td>
                <td style="border: 1px solid #000000; padding: 10px;">
                    <p style="font-weight: bold; margin: 0;">LPG 3Kg (Isi)</p>
                    <small style="color: #4b5563;">Catatan: {{ $suratJalan->catatan ?? 'Sesuai pesanan' }}</small>
                </td>
                <td style="border: 1px solid #000000; padding: 10px; text-align: right; font-weight: bold;">{{ number_format($suratJalan->jumlah_tabung) }} Tabung</td>
            </tr>
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; border: 1px solid #000000;">
                <td colspan="2" style="border: 1px solid #000000; padding: 10px; text-align: right;">TOTAL MUATAN</td>
                <td style="border: 1px solid #000000; padding: 10px; text-align: right;">{{ number_format($suratJalan->jumlah_tabung) }}</td>
            </tr>
        </tfoot>
    </table>

    <p style="font-size: 12px; font-style: italic; margin-bottom: 50px;">Kondisi barang telah diperiksa dan diterima dalam keadaan baik dan cukup.</p>

    <table style="width: 100%; text-align: center; font-size: 12px; border-collapse: collapse;">
        <tr>
            <td style="width: 33%;">
                Dibuat Oleh,<br>
                (Admin Gudang)
                <div style="height: 80px;"></div>
                ____________________
            </td>
            <td style="width: 33%;">
                Dibawa Oleh,<br>
                (Supir Utama)
                <div style="height: 80px;"></div>
                ____________________
            </td>
            <td style="width: 33%;">
                Diterima Oleh,<br>
                (Petugas Lokasi)
                <div style="height: 80px;"></div>
                ____________________
            </td>
        </tr>
    </table>
</div>
