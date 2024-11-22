<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Member::create([
            'username' => 'wantos',
            'balance' => '950',
            'periodno' => 'P2411000003', 
            'statusgame' => '1', 
        ]);

        Member::create([
            'username' => 'tukiyem',
            'balance' => '500',
            'periodno' => 'P2411000002', 
            'statusgame' => '2', 
        ]);

        Member::create([
            'username' => 'daunsirih88',
            'balance' => '600',
            'periodno' => 'P2411000001', 
            'statusgame' => '2', 
        ]);
    }
}
