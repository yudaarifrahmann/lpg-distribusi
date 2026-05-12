<?php

use App\Models\Expense;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (Expense::all() as $e) {
    echo "ID: {$e->id}, Branch ID: {$e->branch_id}, User ID: {$e->user_id}\n";
}
