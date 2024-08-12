<?php

namespace App\Services;

use App\Models\Penyakit;

class DempsterShaferService
{
    public function combineBelief($dataAcuan)
    {
        $x = 0;
        for ($i = 0; $i < count($dataAcuan); $i++) {
            $hasilKonversi[$i]['data'][0]['array'] = $dataAcuan[$i]['daftar_penyakit'];
            $hasilKonversi[$i]['data'][0]['value'] = $dataAcuan[$i]['belief'];
            $hasilKonversi[$i]['data'][1]['array'] = [];
            $hasilKonversi[$i]['data'][1]['value'] = $dataAcuan[$i]['plausibility'];

            $x++;
        }


        $result = $this->iterasi(count($hasilKonversi) - 2, $hasilKonversi);
        // dd($result);

        $arrResult = [];
        foreach ($result['data'] as $key => $value) {
            $arrResult[$key] = $value['value'];
        }

        $indexMaxValue = array_search(max($arrResult), $arrResult);
        $nilaiBelief = round($result['data'][$indexMaxValue]['value'], 2);
        $persentase = (round($result['data'][$indexMaxValue]['value'], 2) * 100) . " %";

        $kodePenyakit = $result['data'][$indexMaxValue]['array'][0];
        $dataPenyakit = Penyakit::where('kode', $kodePenyakit)
            ->select('nama')
            ->get()
            ->toArray()[0];
        $datapenyebab = Penyakit::where('kode', $kodePenyakit)
            ->select('penyebab')
            ->get()
            ->toArray()[0];

        $jsonData = [
            'Nama_Penyakit'         => $dataPenyakit,
            'Nilai_Belief_Penyakit' => $nilaiBelief,
            'Persentase_Penyakit'   => $persentase,
            'penyebab_Penyakit'     => $datapenyebab,
            'hasil_konversi'        => $hasilKonversi,
            'result'                => $result['data'],
        ];

        return $jsonData;
    }

    public function iterasi(int $jumlah, array $myData, $data = [], int $indeks = 0)
    {

        if (count($data) == 0) {
            $hasilAkhir = $this->hasilAkhir($myData[$indeks], $myData[$indeks + 1]);
        } else {
            $hasilAkhir = $this->hasilAkhir($data, $myData[$indeks + 1]);
        }

        if ($indeks < $jumlah) {
            return $this->iterasi($jumlah, $myData, $hasilAkhir, $indeks + 1);
        } else {
            return $hasilAkhir;
        }
    }

    public function hasilAkhir($array1, $array2)
    {
        $hasilAkhir['data'] = [];

        $hasilSementara = [];
        $z = 0;

        for ($x = 0; $x < count($array1['data']); $x++) {
            for ($y = 0; $y < count($array2['data']); $y++) {
                if (count($array1['data'][$x]['array']) != 0 && count($array2['data'][$y]['array']) != 0) {
                    $hasilSementara[$z]['array'] = json_encode(array_values(array_intersect($array1['data'][$x]['array'], $array2['data'][$y]['array'])));
                    if (count(json_decode($hasilSementara[$z]['array'])) == 0) {
                        $hasilSementara[$z]['status'] = "Himpunan Kosong";
                    }
                } else {
                    $hasilSementara[$z]['array'] = json_encode(array_merge($array1['data'][$x]['array'], $array2['data'][$y]['array']));
                }
                $hasilSementara[$z]['value'] = $array1['data'][$x]['value'] * $array2['data'][$y]['value'];
                $z++;
            }
        }

        $pushArray = [];

        foreach ($hasilSementara as $hasil) {
            array_push($pushArray, $hasil['array']);
        }

        $pushArrayCat = [];
        foreach (array_count_values($pushArray) as $key => $value) {
            array_push($pushArrayCat, $key);
        }

        $tetapan = 0;
        foreach ($hasilSementara as $datahasil) {
            if (isset($datahasil['status']) && $datahasil['status'] == "Himpunan Kosong") {
                $tetapan += $datahasil['value'];
            }
        }

        $tetapan = 1 - $tetapan;

        $finalResult = [];
        for ($y = 0; $y < count($pushArrayCat); $y++) {
            $decode[$y] = json_decode($pushArrayCat[$y]);
            $finalResult[$y]['array'] = $decode[$y];
            $finalResult[$y]['value'] = 0;
            for ($x = 0; $x < count($hasilSementara); $x++) {
                $array[$x] = json_decode($hasilSementara[$x]['array']);
                if ($decode[$y] == $array[$x]) {
                    if (!isset($hasilSementara[$x]['status'])) {
                        $finalResult[$y]['value'] += $hasilSementara[$x]['value'];
                    }
                }
            }
            $finalResult[$y]['value'] = $finalResult[$y]['value'] / $tetapan;
        }

        for ($i = 0; $i < count($finalResult); $i++) {
            $hasilAkhir['data'][$i] = $finalResult[$i];
        }

        return $hasilAkhir;
    }
}
