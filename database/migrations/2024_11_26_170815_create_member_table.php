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
        Schema::create('member', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('referral')->nullable();
            $table->string('hp')->nullable();
            $table->string('bank')->nullable();
            $table->string('namarek')->nullable();
            $table->string('norek')->nullable();
            $table->decimal('balance', 15, 2)->default(0);
            $table->string('periodno')->nullable();
            $table->integer('statusgame')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member');
    }
};
