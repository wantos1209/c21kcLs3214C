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
        Schema::create('period_win', function (Blueprint $table) {
            $table->id();
            $table->integer('period_bet_id');
            $table->string('periodno');
            $table->string('username');
            $table->decimal('mc', 15, 2)->default(0);
            $table->decimal('head_mc', 15, 2)->default(0);
            $table->decimal('body_mc', 15, 2)->default(0);
            $table->decimal('leg_mc', 15, 2)->default(0);
            $table->decimal('bm', 15, 2)->default(0);
            $table->decimal('head_bm', 15, 2)->default(0);
            $table->decimal('body_bm', 15, 2)->default(0);
            $table->decimal('leg_bm', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('period_win');
    }
};
