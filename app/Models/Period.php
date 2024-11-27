<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;

    protected $fillable = ['periodno', 'win_state', 'statusgame'];
    protected $table = 'period';

    /**
     * Generate the periodno for each created period.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($period) {
            // Generate the periodno before creating the record
            $period->periodno = $period->generatePeriodBetNo();
        });
    }

    /**
     * Generate the period number.
     */
    public function generatePeriodBetNo()
    {
        $year = now()->format('y');
        $month = now()->format('m');

        $lastPeriodBet = Period::where('periodno', 'like', "P{$year}{$month}%")
            ->orderBy('periodno', 'desc')
            ->first();

        if ($lastPeriodBet) {
            $lastNumber = (int)substr($lastPeriodBet->periodno, -6);
            $newNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '000001';
        }

        return "P{$year}{$month}{$newNumber}";
    }
}
