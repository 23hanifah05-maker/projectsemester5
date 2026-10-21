<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformedConsent extends Model
{
    protected $table = 'informed_consent';
    protected $primaryKey = 'id_informed';
    protected $guarded = [];
}