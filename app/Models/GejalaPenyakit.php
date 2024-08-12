<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GejalaPenyakit extends Model
{
    use HasFactory;

    protected $table = 'gejala_penyakit';
    protected $fillable = [
        'gejala_id',
        'penyakit_id',
        'value_cf'
    ];

    public $timestamp = false;
}
