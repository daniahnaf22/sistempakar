<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BasisPengetahuan extends Model
{
    protected $table = 'rule_ds';
    protected $primaryKey = 'id_basis_pengetahuan';

    protected $fillable = [
        'kode_penyakit',
        'kode_gejala'
    ];
}
