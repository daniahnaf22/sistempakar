<?php

namespace Database\Seeders;

use App\Models\Rule;
use Illuminate\Database\Seeder;

class CreateRuleSeeder extends Seeder
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
                'G1' => true,
                'G2' => true,
                'G3' => true
            ],
            [
                'kode_penyakit' => 'P2',
                'G2' => true,
                'G3' => true,
                'G4' => true,
                'G5' => true,
                'G6' => true
            ],
            [
                'kode_penyakit' => 'P3',
                'G2' => true,
                'G5' => true,
                'G7' => true,
                'G8' => true,
                'G9' => true
            ],
            [
                'kode_penyakit' => 'P4',
                'G2' => true,
                'G3' => true,
                'G10' => true,
                'G11' => true,
                'G12' => true,
                'G13' => true,
                'G14' => true
            ],
            [
                'kode_penyakit' => 'P5',
                'G3' => true,
                'G11' => true,
                'G15' => true,
                'G16' => true,
                'G17' => true,
            ],
            [
                'kode_penyakit' => 'P6',
                'G7' => true,
                'G8' => true,
                'G18' => true,
                'G19' => true,
                'G20' => true
            ],
            [
                'kode_penyakit' => 'P7',
                'G11' => true,
                'G21' => true,
                'G22' => true,
                'G23' => true
            ],
            [
                'kode_penyakit' => 'P8',
                'G24' => true,
                'G25' => true,
                'G26' => true
            ],
        ];

        foreach($data as $row) {
            Rule::create($row);
        }
    }
}
