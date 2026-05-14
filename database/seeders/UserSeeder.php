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
    }
}
