<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Penjualan;
use App\Models\Expense;
use App\Models\Penebusan;

echo "--- DATA PENJUALAN ---\n";
foreach (Penjualan::with('pangkalan')->get() as $p) {
    echo "{$p->tanggal_penjualan->format('d/m/Y')} | " . ($p->pangkalan->nama_pangkalan ?? '-') . " | {$p->jumlah_tabung} Pcs | Rp" . number_format($p->total_penjualan) . "\n";
}

echo "\n--- DATA PENGELUARAN ---\n";
foreach (Expense::get() as $e) {
    echo "{$e->tanggal_pengeluaran} | {$e->nama_pengeluaran} | Rp" . number_format($e->nominal) . "\n";
}

echo "\n--- DATA PENEBUSAN DO ---\n";
foreach (Penebusan::get() as $pb) {
    echo "{$pb->tanggal_penebusan->format('d/m/Y')} | {$pb->nomor_do} | {$pb->jumlah_tabung} Pcs | Rp" . number_format($pb->total_penebusan) . "\n";
}
