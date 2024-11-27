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
        // Tabel users
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique(); // Mengganti email dengan username
            $table->timestamp('email_verified_at')->nullable(); // Bisa dihapus jika verifikasi email tidak diperlukan
            $table->string('password');
            // $table->decimal('balance', 15, 2)->default(0);
            // $table->string('periodno')->nullable();
            // $table->integer('statusgame')->default(1);
            $table->rememberToken();
            $table->timestamps();
        });

        // Tabel password_reset_tokens
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('username')->primary(); // Mengganti email dengan username
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Tabel sessions
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
