<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // 1. Arahkan ke tabel yang benar
    protected $table = 'user';

    // 2. Sesuaikan Primary Key karena di database bernama 'id_user'
    protected $primaryKey = 'id_user';

    // 3. Matikan timestamps karena tabel tidak punya created_at & updated_at
    public $timestamps = false;

    // 4. Kolom yang boleh diisi (sesuai struktur database)
    protected $fillable = [
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}