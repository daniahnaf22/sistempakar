<?php

namespace Database\Seeders;

use App\Models\Gejala;
use Illuminate\Database\Seeder;

class CreateGejalaSeeder extends Seeder
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
                'nama' => 'Diare Berdarah',
                'kode' => 'G1',
                'nilai_densitas'=> '0.8',
            ],
            [
                'nama' => 'Demam',
                'kode' => 'G2',
                'nilai_densitas'=> '0.9',
            ],
            [
                'nama' => 'Muntah',
                'kode' => 'G3',
                'nilai_densitas'=> '0.5',
            ],
            [
                'nama' => 'Sariawan',
                'kode' => 'G4',
                'nilai_densitas'=> '1',
            ],
            [
                'nama' => 'Sesak Nafas',
                'kode' => 'G5',
                'nilai_densitas'=> '0.6',
            ],
            [
                'nama' => 'Gusi berdarah',
                'kode' => 'G6',
                'nilai_densitas'=> '0.8',
            ],
            [
                'nama' => 'Bersin',
                'kode' => 'G7',
                'nilai_densitas'=> '0.9',
            ],
            [
                'nama' => 'Batuk',
                'kode' => 'G8',
                'nilai_densitas'=> '0.9',
            ],
            [
                'nama' => 'Muntah Dahak',
                'kode' => 'G9',
                'nilai_densitas'=> '0.8',
            ],
            [
                'nama' => 'Anemia',
                'kode' => 'G10',
                'nilai_densitas'=> '0.6',
            ],
            [
                'nama' => 'Tidak Nafsu Makan',
                'kode' => 'G11',
                'nilai_densitas'=> '0.6',
            ],
            [
                'nama' => 'Denyut Jantung Cepat',
                'kode' => 'G12',
                'nilai_densitas'=> '0.2',
            ],
            [
                'nama' => 'Diarea',
                'kode' => 'G13',
                'nilai_densitas'=> '0.6',
            ],
            [
                'nama' => 'Dehidrasi',
                'kode' => 'G14',
                'nilai_densitas'=> '0.6',
            ],
            [
                'nama' => 'Susah Mengeluarkan Urin',
                'kode' => 'G15',
                'nilai_densitas'=> '1',
            ],
            [
                'nama' => 'Darah Dalam Urin',
                'kode' => 'G16',
                'nilai_densitas'=> '1',
            ],
            [
                'nama' => 'Sering Menjilati Daerah Genital',
                'kode' => 'G17',
                'nilai_densitas'=> '0.8',
            ],
            [
                'nama' => 'Peradangan pada selaput lendir mata',
                'kode' => 'G18',
                'nilai_densitas'=> '1',
            ],
            [
                'nama' => 'Benjolan bola mata',
                'kode' => 'G19',
                'nilai_densitas'=> '0.6',
            ],
            [
                'nama' => 'Sekresi mata',
                'kode' => 'G20',
                'nilai_densitas'=> '0.6',
            ],
            [
                'nama' => 'Kotoran yang sangat keras',
                'kode' => 'G21',
                'nilai_densitas'=> '0.8',
            ],
            [
                'nama' => 'Kotoran tidak bisa keluar',
                'kode' => 'G22',
                'nilai_densitas'=> '1',
            ],
            [
                'nama' => 'Lesu',
                'kode' => 'G23',
                'nilai_densitas'=> '0.6',
            ],
            [
                'nama' => 'Bulu Rontok',
                'kode' => 'G24',
                'nilai_densitas'=> '0.8',
            ],
            [
                'nama' => 'Jamur dikulit',
                'kode' => 'G25',
                'nilai_densitas'=> '0.8',
            ],
            [
                'nama' => 'Kulit Berkerak',
                'kode' => 'G26',
                'nilai_densitas'=> '0.8',
            ]
        ];

        Gejala::insert($data);
    }
}
