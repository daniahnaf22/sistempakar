<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade as PDF;
use App\Models\{CalculateDs, Gejala, GejalaPenyakit, Penyakit, Rule, Riwayat};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Services\DempsterShaferService;
// use App\Helpers\DempsterShaferHelper;

class DiagnosaController extends Controller
{
    protected $dempsterShaferService;

    function __construct(DempsterShaferService $dempsterShaferService)
    {
        $this->middleware('permission:diagnosa', ['only' => ['index']]);
        $this->middleware('permission:diagnosa-create', ['only' => ['diagnosa']]);
        $this->dempsterShaferService = $dempsterShaferService;
    }

    public function index()
    {
        $gejala = Gejala::all();

        return view('admin.diagnosa', compact('gejala'));
    }

    public function tingkat_keyakinan($keyakinan)
    {
        switch ($keyakinan) {
            case 0.2:
                return 'Kurang Yakin';
                break;
            case 0.4:
                return 'Mungkin';
                break;
            case 0.6:
                return 'Kemungkinan Besar';
                break;
            case 0.8:
                return 'Hampir pasti';
                break;
            case 1:
                return 'Pasti';
                break;
        }
    }

    public function kalkulasi_cf($data)
    {
        $data_penyakit = [];
        $gejala_terpilih = [];
        foreach ($data['diagnosa'] as $input) {
            if (!empty($input)) {
                $opts = explode('+', $input);
                $gejala = Gejala::with('penyakits')->find($opts[0]);

                foreach ($gejala->penyakits as $penyakit) {
                    if (empty($data_penyakit[$penyakit->id])) {
                        $data_penyakit[$penyakit->id] = [$penyakit, [$gejala, $opts[1], $penyakit->pivot->value_cf]];
                    } else {
                        array_push($data_penyakit[$penyakit->id], [$gejala, $opts[1], $penyakit->pivot->value_cf]);
                    }

                    if (empty($gejala_terpilih[$gejala->id])) {
                        $gejala_terpilih[$gejala->id] = [
                            'nama' => $gejala->nama,
                            'kode' => $gejala->kode,
                            'cf_user' => $opts[1],
                            'keyakinan' => $this->tingkat_keyakinan($opts[1])
                        ];
                    }
                }
            }
        }

        $hasil_diagnosa = [];
        $cf_max = null;
        foreach ($data_penyakit as $final) {
            if (count($final) < 3) {
                continue;
            }
            $cf1 = null;
            $cf2 = null;
            $cf_combine = 0;
            $hasil_cf = null;
            foreach ($final as $key => $value) {
                if ($key == 0) {
                    continue;
                }
                if ($key == 1) {
                    $cf1 = $final[$key][2] * $final[$key][1];
                } else {
                    if ($cf_combine != 0) {
                        $cf1 = $cf_combine;
                        $cf2 = $final[$key][2] * $final[$key][1];

                        if ($cf1 < 0 || $cf2 < 0) {
                            $cf_combine = ($cf1 + $cf2) / (1 - min($cf1, $cf2));
                        } else {
                            $cf_combine = $cf1 + ($cf2 * (1 - $cf1));
                        }

                        $hasil_cf = $cf_combine;
                    } else {
                        $cf2 = $final[$key][2] * $final[$key][1];

                        if ($cf1 < 0 || $cf2 < 0) {
                            $cf_combine = ($cf1 + $cf2) / (1 - min($cf1, $cf2));
                        } else {
                            $cf_combine = $cf1 + ($cf2 * (1 - $cf1));
                        }

                        $hasil_cf = $cf_combine;
                    }
                }

                if (count($final) - 1 == $key) {
                    if ($cf_max == null) {
                        $cf_max = [$hasil_cf, "{$final[0]->nama} ({$final[0]->kode})"];
                    } else {
                        $cf_max = ($hasil_cf > $cf_max[0])
                            ? [$hasil_cf, "{$final[0]->nama} ({$final[0]->kode})"]
                            : $cf_max;
                    }

                    $hasil_diagnosa[$final[0]->id]['hasil_cf'] = $hasil_cf;

                    $cf1 = null;
                    $cf2 = null;
                    $cf_combine = 0;
                    $hasil_cf = null;
                }

                if (empty($hasil_diagnosa[$final[0]->id])) {
                    $hasil_diagnosa[$final[0]->id] = [
                        'nama_penyakit' => $final[0]->nama,
                        'kode_penyakit' => $final[0]->kode,
                        'gejala' => [
                            [
                                'nama' => $final[$key][0]->nama,
                                'kode' => $final[$key][0]->kode,
                                'cf_user' => $final[$key][1],
                                'cf_role' => $final[$key][2],
                                'hasil_perkalian' => $final[$key][2] * $final[$key][1]
                            ]
                        ]
                    ];
                } else {
                    array_push($hasil_diagnosa[$final[0]->id]['gejala'], [
                        'nama' => $final[$key][0]->nama,
                        'kode' => $final[$key][0]->kode,
                        'cf_user' => $final[$key][1],
                        'cf_role' => $final[$key][2],
                        'hasil_perkalian' => $final[$key][2] * $final[$key][1]
                    ]);
                }
            }
        }


        return [
            'hasil_diagnosa' => $hasil_diagnosa,
            'gejala_terpilih' => $gejala_terpilih,
            'cf_max' => $cf_max
        ];
    }

    public function kalkulasi_ds($gejala_beliefs, $penyakit_gejala)
    {
        echo "<pre>";
        echo "Gejala Belief:";
        print_r($gejala_beliefs);
        echo "</pre>";

        echo "<pre>";
        echo "Penyakit Belief:";
        print_r($penyakit_gejala);
        echo "</pre>";
        $masses = [];

        // Periksa apakah gejala_beliefs tidak kosong
        if (!empty($gejala_beliefs)) {
            foreach ($penyakit_gejala as $penyakit => $gejalas) {
                $belief = 1;
                foreach ($gejalas as $gejala) {
                    // Pastikan gejala yang dicari ada dalam gejala_beliefs
                    if (isset($gejala_beliefs[$gejala]) && is_numeric($gejala_beliefs[$gejala])) {
                        $belief *= $gejala_beliefs[$gejala];
                    } else {
                        // Handle jika gejala tidak memiliki nilai belief yang valid
                        $belief = 0; // Atau sesuaikan dengan logika bisnis Anda
                        break; // Hentikan perhitungan jika ada gejala tanpa nilai belief
                    }
                }

                if ($belief > 0) {
                    $masses[$penyakit] = [
                        'belief' => $belief,
                        'plausibility' => 1 - $belief
                    ];
                }
            }
        }

        dd($masses);

        // Jika tidak ada masses, kembalikan array kosong
        if (empty($masses)) {
            return [];
        }

        $combinedMass = $this->combineAllMasses($masses);

        // Pastikan combinedMass tidak kosong
        if (empty($combinedMass)) {
            return [];
        }

        $totalMass = array_sum(array_column($combinedMass, 'belief'));
        $results = [];
        foreach ($combinedMass as $penyakit => $mass) {
            if (is_array($mass) && isset($mass['belief']) && $totalMass != 0) {
                $results[$penyakit] = $mass['belief'] / $totalMass;
            }
        }

        return $results;
    }

    private function combineAllMasses($masses)
    {
        $combined = array_shift($masses);

        while (!empty($masses)) {
            $next = array_shift($masses);
            $combined = $this->combineTwoMasses($combined, $next);
        }

        return $combined;
    }


    private function combineTwoMasses($m1, $m2)
    {
        $combined = [];

        foreach ($m1 as $h1 => $v1) {
            foreach ($m2 as $h2 => $v2) {
                if (is_numeric($v1) && is_numeric($v2)) {
                    $new_h = $this->combineHypotheses($h1, $h2);
                    if (!isset($combined[$new_h])) {
                        $combined[$new_h] = 0;
                    }
                    $combined[$new_h] += $v1 * $v2;
                }
            }
        }

        // Normalisasi
        $normalizingFactor = 1 - ($combined['empty'] ?? 0);
        if ($normalizingFactor == 0) {
            return $combined;
        }

        foreach ($combined as $h => $v) {
            if ($h != 'empty') {
                $combined[$h] = $v / $normalizingFactor;
            }
        }
        unset($combined['empty']);

        return $combined;
    }



    private function combineHypotheses($h1, $h2)
    {
        if ($h1 == 'empty' || $h2 == 'empty') {
            return 'empty';
        }

        $hypothesis1 = explode(',', $h1);
        $hypothesis2 = explode(',', $h2);

        $intersection = array_intersect($hypothesis1, $hypothesis2);
        if (empty($intersection)) {
            return 'empty';
        }

        sort($intersection);
        return implode(',', $intersection);
    }

    public function diagnosa(Request $request)
    {
        $name = $request->nama;

        if (auth()->user()->hasRole('Admin')) {
            $request->validate(['nama' => 'required|string|max:100']);
            $name = $request->nama;
        }

        $data = $request->all();

        // try {
        $result = $this->kalkulasi_cf($data);

        if (!isset($result['cf_max']) || !isset($result['hasil_diagnosa'])) {
            return back()->withErrors(['Terjadi kesalahan pada hasil perhitungan CF']);
        }
        // Perhitungan DS menggunakan hasil CF yang didapat
        $hasil_cf = $result['hasil_diagnosa'];

        $gejala_beliefs     = [];
        // Ambil nilai densitas dari tabel gejalas berdasarkan kode gejala
        foreach ($hasil_cf as $penyakit) {
            foreach ($penyakit['gejala'] as $gejala) {
                $kode = $gejala['kode'];
                $gejala_model = Gejala::where('kode', $kode)->first();
                if ($gejala_model) {
                    $gejala_beliefs[$kode] = $gejala_model->nilai_densitas;
                }
            }
        }

        $penyakit_gejala    = [];
        // Array penyakit dengan gejala terkait
        foreach ($hasil_cf as $penyakit) {
            foreach ($penyakit['gejala'] as $gejala) {
                $kode_gejala = $gejala['kode'];
                $gejala_model = Gejala::where('kode', $kode_gejala)->first();
                if ($gejala_model) {
                    $gejala_penyakit_relations = GejalaPenyakit::where('gejala_id', $gejala_model->id)->get();
                    foreach ($gejala_penyakit_relations as $relation) {
                        $penyakit_model = Penyakit::find($relation->penyakit_id);
                        if ($penyakit_model) {
                            $kode_penyakit = $penyakit_model->kode;
                            if (!isset($penyakit_gejala[$kode_penyakit])) {
                                $penyakit_gejala[$kode_penyakit] = [];
                            }
                            if (!in_array($kode_gejala, $penyakit_gejala[$kode_penyakit])) {
                                $penyakit_gejala[$kode_penyakit][] = $kode_gejala;
                            }
                        }
                    }
                }
            }
        }

        $hasil_ds = $this->kalkulasi_ds($gejala_beliefs, $penyakit_gejala);
        echo "<pre>";
        print_r($hasil_ds);
        echo "</pre>";
    }
}
