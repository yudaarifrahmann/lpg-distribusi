<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['nama_kategori' => 'Solar', 'jenis_kategori' => 'Operasional'],
            ['nama_kategori' => 'Uang Jalan', 'jenis_kategori' => 'Operasional'],
            ['nama_kategori' => 'Servis', 'jenis_kategori' => 'Perawatan'],
            ['nama_kategori' => 'Token', 'jenis_kategori' => 'Operasional'],
            ['nama_kategori' => 'THR', 'jenis_kategori' => 'Personalia'],
            ['nama_kategori' => 'Biaya Lain-Lain', 'jenis_kategori' => 'Lainnya'],
        ];

        foreach ($categories as $category) {
            ExpenseCategory::create($category);
        }
    }
}
