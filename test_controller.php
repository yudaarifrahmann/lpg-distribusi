<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$controller = new App\Http\Controllers\StockController();
$request = new Illuminate\Http\Request();
$response = $controller->index($request);
$data = $response->getData();
echo 'Summary stok_saat_ini: ' . $data['summary']->stok_saat_ini . PHP_EOL;