<?php

use App\Models\Driver;
use App\Models\User;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (Driver::all() as $d) {
    echo "Driver ID: {$d->id}, User ID: {$d->user_id}, Name: {$d->nama}\n";
}
