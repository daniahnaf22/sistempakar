<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalculateDs extends Model
{
    use HasFactory;

    protected $table = 'calculate_ds';
    protected $fillable = [
        'riwayat_id',
        'mass_function',
        'mass_function_gabungan',
        'hasil_akhir',
        'kesimpulan',
        'penyakit',
    ];

    public $timestamp = false;
}
