<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosaDs extends Model
{
    protected $table = 'diagnosa_ds';
    protected $primaryKey = 'id_diagnosa';
    protected $fillable = [
        'nama_pemilik',
        'diagnosa',
        'penyebab'
    ];
}
