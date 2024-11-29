<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_access', function (Blueprint $table) {
            $table->id();
            $table->string('divisi', 150)->required();
            $table->boolean('deposit')->default(false);
            $table->boolean('withdraw')->default(false);
            $table->boolean('manual_transaction')->default(false);
            $table->boolean('history_coin')->default(false);

            $table->boolean('member_list')->default(false);
            $table->boolean('referral')->default(false);
            $table->boolean('history_transaction')->default(false);
            $table->boolean('running_order')->default(false);
            $table->boolean('cashback_rollingan')->default(false);
            $table->boolean('report')->default(false);

            $table->boolean('bank')->default(false);
            $table->boolean('referral_bonus')->default(false);
            $table->boolean('memo')->default(false);

            $table->boolean('agent')->default(false);
            $table->boolean('game_setting')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_access');
    }
};
