<?php

namespace Database\Seeders;

use App\Models\UserAccess;
use Illuminate\Database\Seeder;

class UserAccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserAccess::create([
            'divisi' => 'superadmin',
            'deposit' => true,
            'withdraw' => true,
            'manual_transaction' => true,
            'history_coin' => true,
            'member_list' => true,
            'referral' => true,
            'history_transaction' => true, 
            'running_order' => true, 
            'cashback_rollingan' => true,
            'report' => true,
            'bank' => true,
            'referral_bonus' => true,
            'memo' => true,
            'agent' => true,
            'game_setting' => true
        ]);
    }
}
