<?php

use App\Models\Expense;
use App\Models\Driver;
use Illuminate\Support\Facades\Auth;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Total Expenses: " . Expense::count() . "\n";
echo "Approved Expenses: " . Expense::where('status_verifikasi', 'disetujui')->count() . "\n";

foreach (Expense::where('status_verifikasi', 'disetujui')->get() as $e) {
    echo "ID: {$e->id}, User: {$e->user_id}, Date: {$e->tanggal_pengeluaran->format('Y-m-d')}, Nominal: {$e->nominal}\n";
}
