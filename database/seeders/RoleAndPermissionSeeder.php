<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $superadmin = Role::firstOrCreate(['name' => 'superadmin']);
        $adminKeuangan = Role::firstOrCreate(['name' => 'admin_keuangan']);
        $supirKnek = Role::firstOrCreate(['name' => 'supir_knek']);

        // Create permissions
        $permissions = [
            'view dashboard',
            'view master data',
            'create master data',
            'edit master data',
            'delete master data',
            'view penebusan',
            'create penebusan',
            'edit penebusan',
            'delete penebusan',
            'view sa',
            'create sa',
            'edit sa',
            'delete sa',
            'view stock',
            'view vehicle stock',
            'view surat jalan',
            'create surat jalan',
            'edit surat jalan',
            'delete surat jalan',
            'view penjualan',
            'create penjualan',
            'edit penjualan',
            'delete penjualan',
            'view pengeluaran',
            'create pengeluaran',
            'edit pengeluaran',
            'delete pengeluaran',
            'view piutang',
            'create piutang',
            'edit piutang',
            'delete piutang',
            'view laporan',
            'view user management',
            'create user',
            'edit user',
            'delete user',
            'assign roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign all permissions to superadmin
        $superadmin->syncPermissions(Permission::all());

        // Assign permissions to admin_keuangan
        $adminKeuangan->syncPermissions([
            'view dashboard',
            'view master data',
            'create master data',
            'edit master data',
            'delete master data',
            'view penebusan',
            'create penebusan',
            'edit penebusan',
            'delete penebusan',
            'view sa',
            'create sa',
            'edit sa',
            'delete sa',
            'view stock',
            'view vehicle stock',
            'view surat jalan',
            'create surat jalan',
            'edit surat jalan',
            'delete surat jalan',
            'view penjualan',
            'create penjualan',
            'edit penjualan',
            'view pengeluaran',
            'create pengeluaran',
            'edit pengeluaran',
            'view piutang',
            'create piutang',
            'edit piutang',
            'view laporan',
        ]);

        // Assign permissions to supir_knek
        $supirKnek->syncPermissions([
            'view dashboard',
            'view surat jalan',
            'view vehicle stock',
            'view penjualan',
            'create penjualan', // Allow supir to input sales
            'view piutang', // Allow supir to see their own receivables
        ]);
    }
}
