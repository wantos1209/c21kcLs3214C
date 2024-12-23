<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    //  $table->string('divisi')->nullable();      
    //  $table->string('image')->nullable();      
    //  $table->string('status')->nullable();     
    //  $table->string('last_login')->nullable(); 
    //  $table->string('ip_login')->nullable();   
    //  $table->string('pin')->nullable();        
    //  $table->integer('pin_attempts')->default(0); 


    protected $fillable = [
        'name',
        'username', 
        'password',
        'divisi',
        'image',
        'status',
        'last_login',
        'ip_login',
        'pin',
        'pin_attempts',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime', // Hapus jika tidak diperlukan
        'password' => 'hashed',
    ];

    /**
     * Override default authentication field.
     *
     * @return string
     */
    public function getAuthIdentifierName(): string
    {
        return 'username'; // Menggunakan 'username' sebagai kunci autentikasi
    }
}
