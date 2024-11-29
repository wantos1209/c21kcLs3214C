<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToUsersTable extends Migration
{
    /**
     * Menjalankan migration untuk menambah kolom ke tabel 'users'.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom baru
            $table->string('divisi')->nullable();      
            $table->string('image')->nullable();      
            $table->string('status')->nullable();     
            $table->string('last_login')->nullable(); 
            $table->string('ip_login')->nullable();   
            $table->string('pin')->nullable();        
            $table->integer('pin_attempts')->default(0); 
        });
    }

    /**
     * Membalikkan perubahan yang dilakukan pada migration ini.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom yang ditambahkan jika migration di-rollback
            $table->dropColumn([
                'divisi',
                'image',
                'status',
                'last_login',
                'ip_login',
                'pin',
                'pin_attempts'
            ]);
        });
    }
}