<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccess extends Model
{
    use HasFactory;

    protected $fillable = ['divisi', 'deposit', 'withdraw', 'manual_transaction', 'history_coin', 'member_list', 'referral', 'history_transaction', 'running_order', 'cashback_rollingan', 'report', 'bank', 'referral_bonus', 'memo', 'agent', 'game_setting'];
    protected $table = 'user_access';
}
