<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserAccess extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'wantos',
            'username' => 'wantos',
            'divisi' => 'superadmin',
            'status' => 'active',
            'password' => Hash::make('wantos123'), // Password dienkripsi
        ]);
    }
}
