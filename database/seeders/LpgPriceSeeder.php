<?php

namespace Database\Seeders;

use App\Models\LpgPrice;
use Illuminate\Database\Seeder;

class LpgPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prices = [
            ['nama_harga' => 'Harga Rp14.600', 'harga' => 14600, 'status' => 'aktif'],
            ['nama_harga' => 'Harga Rp15.000', 'harga' => 15000, 'status' => 'aktif'],
            ['nama_harga' => 'Harga Rp15.500', 'harga' => 15500, 'status' => 'aktif'],
            ['nama_harga' => 'Harga Rp16.000', 'harga' => 16000, 'status' => 'aktif'],
            ['nama_harga' => 'Harga Rp16.500', 'harga' => 16500, 'status' => 'aktif'],
            ['nama_harga' => 'Harga Rp17.000', 'harga' => 17000, 'status' => 'aktif'],
            ['nama_harga' => 'Harga Rp18.000', 'harga' => 18000, 'status' => 'aktif'],
        ];

        foreach ($prices as $price) {
            LpgPrice::create($price);
        }
    }
}
