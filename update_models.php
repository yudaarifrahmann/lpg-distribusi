<?php
$models = ['Pangkalan', 'Truck', 'Driver', 'SuratJalan', 'Penjualan', 'Piutang', 'Expense', 'Penebusan', 'ReturTabung', 'ScheduleAgreement', 'StockSummary', 'StockHistory', 'VehicleStock', 'VehicleStockHistory', 'PembayaranPiutang'];
foreach($models as $m){
    $f = __DIR__.'/app/Models/'.$m.'.php';
    if(file_exists($f)) {
        $c = file_get_contents($f);
        if(strpos($c, 'BelongsToBranch') === false){
            $c = str_replace("use Illuminate\Database\Eloquent\Model;", "use Illuminate\Database\Eloquent\Model;\nuse App\Traits\BelongsToBranch;", $c);
            $c = preg_replace('/(class '.$m.' extends Model\s*\{)/s', "$1\n    use BelongsToBranch;\n", $c);
            file_put_contents($f, $c);
            echo "Updated $m\n";
        }
    }
}
