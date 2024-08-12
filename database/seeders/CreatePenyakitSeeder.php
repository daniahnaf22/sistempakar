<?php

namespace Database\Seeders;

use App\Models\Penyakit;
use Illuminate\Database\Seeder;

class CreatePenyakitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'nama' => 'Panleukopenia',
                'kode' => 'P1',
                'penyebab' => 'Panleukopenia disebabkan oleh feline parvovirus'
            ],
            [
                'nama' => 'Calici Virus',
                'kode' => 'P2',
                'penyebab' => 'yang menyebabkan infeksi pernapasan ringan hingga parah pada kucing'
            ],
            [
                'nama' => 'Rhinotracheitis',
                'kode' => 'P3',
                'penyebab' => 'penyebab utama feline rhinotracheitis yaitu disebabkan karena virus'
            ],
            [
                'nama' => 'Haemobartonellosis',
                'kode' => 'P4',
                'penyebab' => ' Parasit ini dapat menyebabkan anemia hemolitik pada kucing, yang ditandai dengan penurunan jumlah sel darah merah atau kadar hemoglobin'
            ],
            [
                'nama' => 'FLUTD',
                'kode' => 'P5',
                'penyebab' => 'FLUTD merujuk pada sekelompok penyakit yang mempengaruhi saluran kemih bagian bawah kucing'
            ],
            [
                'nama' => 'Chlamydiosis',
                'kode' => 'P6',
                'penyebab' => 'Gejala chlamydia pada kucing bisa meliputi infeksi saluran pernapasan atas dan dapat menyebabkan konjungtivitis atau peradangan pada mata'
            ],
            [
                'nama' => 'Megacolon',
                'kode' => 'P7',
                'penyebab' => 'kondisi kesehatan yang mempengaruhi usus besar kucing'
            ],
            [
                'nama' => 'Ring Worm',
                'kode' => 'P8',
                'penyebab' => 'salah satu penyakit infeksi jamur kulit yang paling banyak ditemui pada kucing'
            ],
        ];

        Penyakit::insert($data);
    }
}
