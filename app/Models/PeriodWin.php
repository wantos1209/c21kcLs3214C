<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Transactions;
use App\Models\TransactionsSaldo;

class PeriodWin extends Model
{
    use HasFactory;

    protected $fillable = ['period_bet_id', 'periodno', 'username', 'mc', 'head_mc', 'body_mc', 'leg_mc', 'bm', 'head_bm', 'body_bm', 'leg_bm'];
    protected $table = 'period_win';
}
