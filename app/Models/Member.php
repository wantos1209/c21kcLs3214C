<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Transactions;
use App\Models\TransactionsSaldo;
use Laravel\Sanctum\HasApiTokens;

class Member extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = 'member';

    protected $fillable = ['username', 'password', 'referral', 'hp', 'bank', 'namarek', 'norek', 'balance', 'periodno', 'statusgame'];
    protected $hidden = [
        'password',
    ];
}
