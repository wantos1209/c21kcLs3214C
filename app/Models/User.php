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
    protected $fillable = [
        'name',
        'username', // Mengganti 'email' dengan 'username'
        'password',
        // 'balance',
        // 'periodno',
        // 'statusgame'
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
