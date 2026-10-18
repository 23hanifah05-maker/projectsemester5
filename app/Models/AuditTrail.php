<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditTrail extends Model
{
    protected $table = 'audit_trail';

    protected $fillable = [
        'user_id', 'username', 'role', 'aktivitas', 'modul', 'no_rm',
        'bidang_kolom', 'sebelum', 'sesudah', 'status',
        'ip_address', 'nama_perangkat',
    ];
}