<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Superadmin
        $superadmin = User::updateOrCreate(
            ['email' => 'superadmin@lpg.local'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('superadmin123'),
                'email_verified_at' => now(),
            ]
        );
        $superadmin->assignRole('superadmin');

        // Create Admin Keuangan
        $adminKeuangan = User::updateOrCreate(
            ['email' => 'admin_keuangan@lpg.local'],
            [
                'name' => 'Admin Keuangan',
                'password' => Hash::make('keuangan123'),
                'email_verified_at' => now(),
            ]
        );
        $adminKeuangan->assignRole('admin_keuangan');

        // Create Supir/Knek
        $supirKnek = User::updateOrCreate(
            ['email' => 'supir_knek@lpg.local'],
            [
                'name' => 'Supir Knek',
                'password' => Hash::make('supirknek123'),
                'email_verified_at' => now(),
            ]
        );
        $supirKnek->assignRole('supir_knek');
    }
}
