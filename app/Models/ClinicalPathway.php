<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicalPathway extends Model
{
    protected $table = 'clinical_pathway';

    protected $primaryKey = 'id_cp';

    protected $guarded = [];
}