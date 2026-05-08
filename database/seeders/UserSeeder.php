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
        $superadmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@lpg.local',
            'password' => Hash::make('superadmin123'),
            'email_verified_at' => now(),
        ]);
        $superadmin->assignRole('superadmin');

        // Create Admin Keuangan
        $adminKeuangan = User::create([
            'name' => 'Admin Keuangan',
            'email' => 'admin_keuangan@lpg.local',
            'password' => Hash::make('keuangan123'),
            'email_verified_at' => now(),
        ]);
        $adminKeuangan->assignRole('admin_keuangan');

        // Create Supir/Knek
        $supirKnek = User::create([
            'name' => 'Supir Knek',
            'email' => 'supir_knek@lpg.local',
            'password' => Hash::make('supirknek123'),
            'email_verified_at' => now(),
        ]);
        $supirKnek->assignRole('supir_knek');
    }
}
