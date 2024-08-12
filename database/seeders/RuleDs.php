<?php

namespace Database\Seeders;

use App\Models\BasisPengetahuan;
use Illuminate\Database\Seeder;

class RuleDs extends Seeder
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
                'kode_penyakit' => 'P1',
                'kode_gejala' => 'G1',
            ],
            [
                'kode_penyakit' => 'P1',
                'kode_gejala' => 'G2',
            ],
            [
                'kode_penyakit' => 'P1',
                'kode_gejala' => 'G3',
            ],
            [
                'kode_penyakit' => 'P2',
                'kode_gejala' => 'G2',
            ],
            [
                'kode_penyakit' => 'P2',
                'kode_gejala' => 'G3',
            ],
            [
                'kode_penyakit' => 'P2',
                'kode_gejala' => 'G4',
            ],
            [
                'kode_penyakit' => 'P2',
                'kode_gejala' => 'G5',
            ],
            [
                'kode_penyakit' => 'P2',
                'kode_gejala' => 'G6',
            ],
            [
                'kode_penyakit' => 'P3',
                'kode_gejala' => 'G2',
            ],
            [
                'kode_penyakit' => 'P3',
                'kode_gejala' => 'G5',
            ],
            [
                'kode_penyakit' => 'P3',
                'kode_gejala' => 'G7',
            ],
            [
                'kode_penyakit' => 'P3',
                'kode_gejala' => 'G8',
            ],
            [
                'kode_penyakit' => 'P3',
                'kode_gejala' => 'G9',
            ],
            [
                'kode_penyakit' => 'P4',
                'kode_gejala' => 'G2',
            ],
            [
                'kode_penyakit' => 'P4',
                'kode_gejala' => 'G3',
            ],
            [
                'kode_penyakit' => 'P4',
                'kode_gejala' => 'G10',
            ],
            [
                'kode_penyakit' => 'P4',
                'kode_gejala' => 'G11',
            ],
            [
                'kode_penyakit' => 'P4',
                'kode_gejala' => 'G12',
            ],
            [
                'kode_penyakit' => 'P4',
                'kode_gejala' => 'G13',
            ],
            [
                'kode_penyakit' => 'P4',
                'kode_gejala' => 'G14',
            ],
            [
                'kode_penyakit' => 'P5',
                'kode_gejala' => 'G3',
            ],
            [
                'kode_penyakit' => 'P5',
                'kode_gejala' => 'G11',
            ],
            [
                'kode_penyakit' => 'P5',
                'kode_gejala' => 'G15',
            ],
            [
                'kode_penyakit' => 'P5',
                'kode_gejala' => 'G16',
            ],
            [
                'kode_penyakit' => 'P5',
                'kode_gejala' => 'G17',
            ],
            [
                'kode_penyakit' => 'P6',
                'kode_gejala' => 'G7',
            ],
            [
                'kode_penyakit' => 'P6',
                'kode_gejala' => 'G8',
            ],
            [
                'kode_penyakit' => 'P6',
                'kode_gejala' => 'G18',
            ],
            [
                'kode_penyakit' => 'P6',
                'kode_gejala' => 'G19',
            ],
            [
                'kode_penyakit' => 'P6',
                'kode_gejala' => 'G20',
            ],
            [
                'kode_penyakit' => 'P7',
                'kode_gejala' => 'G11',
            ],
            [
                'kode_penyakit' => 'P7',
                'kode_gejala' => 'G21',
            ],
            [
                'kode_penyakit' => 'P7',
                'kode_gejala' => 'G22',
            ],
            [
                'kode_penyakit' => 'P7',
                'kode_gejala' => 'G23',
            ],
            [
                'kode_penyakit' => 'P8',
                'kode_gejala' => 'G24',
            ],
            [
                'kode_penyakit' => 'P8',
                'kode_gejala' => 'G25',
            ],
            [
                'kode_penyakit' => 'P8',
                'kode_gejala' => 'G26',
            ],

        ];

        BasisPengetahuan::insert($data);
    }
}
