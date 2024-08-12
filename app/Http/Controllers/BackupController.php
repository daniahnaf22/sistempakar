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

    public function kalkulasi_ds($hasil_cf)
    {
        $gejala_beliefs   = [];
        $penyakit_gejala  = [];

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

        // Array penyakit dengan gejala terkait (untuk contoh ini diambil dari hasil_cf)
        // foreach ($hasil_cf as $penyakit) {
        //     $kode_penyakit = $penyakit['kode_penyakit'];
        //     echo "<pre>";
        //     print_r($kode_penyakit);
        //     echo "</pre>";
        //     $penyakit_gejala[$kode_penyakit] = array_map(function ($gejala) {
        //         return $gejala['kode'];
        //     }, $penyakit['gejala']);
        // }

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


        // Inisialisasi belief function untuk setiap gejala
        $belief_functions = [];
        foreach ($gejala_beliefs as $kode_gejala => $nilai_densitas) {
            $belief_functions[] = $this->initializeBelief($kode_gejala, $nilai_densitas, $penyakit_gejala);
        }

        // dd($belief_functions);

        // Kombinasi belief function menggunakan Dempster-Shafer
        $combined_belief = $belief_functions[0];
        for ($i = 1; $i < count($belief_functions); $i++) {
            $combined_belief = $this->combine($combined_belief, $belief_functions[$i]);
        }

        return $combined_belief;
    }

    private function initializeBelief($kode_gejala, $nilai_densitas, $penyakit_gejala)
    {
        $belief = [];

        foreach ($penyakit_gejala as $kode_penyakit => $gejalas) {
            if (in_array($kode_gejala, $gejalas)) {
                $belief[$kode_penyakit] = $nilai_densitas;
            }
        }

        $belief['θ'] = 1 - $nilai_densitas;

        // Debugging: Print initialized belief
        // echo "Initialized belief for gejala $kode_gejala:<br>";
        // print_r($belief);

        return $belief;
    }

    private function combine($m1, $m2)
    {
        $result = [];
        $conflict = 0;

        foreach ($m1 as $h1 => $m1_value) {
            foreach ($m2 as $h2 => $m2_value) {
                $intersection = $this->getIntersection($h1, $h2);
                if ($intersection == 'θ') {
                    $conflict += $m1_value * $m2_value;
                } else {
                    if (!isset($result[$intersection])) {
                        $result[$intersection] = 0;
                    }
                    $result[$intersection] += $m1_value * $m2_value;
                }
            }
        }

        // Allocate conflict mass to θ
        if (!isset($result['θ'])) {
            $result['θ'] = 0;
        }
        $result['θ'] += $conflict;

        // Normalisasi hasil
        $total_mass = array_sum($result);
        foreach ($result as $key => $value) {
            $result[$key] = $value / $total_mass;
        }

        // Redistribute uncertainty (θ) proportionally
        if (isset($result['θ']) && $result['θ'] > 0) {
            $total_mass_without_theta = 1 - $result['θ'];
            foreach ($result as $key => $value) {
                if ($key != 'θ') {
                    $result[$key] = $value + ($value / $total_mass_without_theta) * $result['θ'];
                }
            }
            $result['θ'] = 0;
        }

        // Debugging: Print result after normalization and redistribution
        // echo "<br>";
        // echo "Result after normalization and redistribution:<br>";
        // print_r($result);

        return $result;
    }

    private function getIntersection($h1, $h2)
    {
        if ($h1 == 'θ') return $h2;
        if ($h2 == 'θ') return $h1;

        $set1 = explode(',', $h1);
        $set2 = explode(',', $h2);

        $intersection = array_intersect($set1, $set2);
        if (empty($intersection)) return 'θ';


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
        $hasil_ds = $this->kalkulasi_ds($hasil_cf);
        echo "<pre>";
        print_r($hasil_ds);
        echo "</pre>";

        // $namaPenyakit   = Penyakit::select('nama')->whereKode($hasil_ds['max_penyakit'])->first();
        // $kesimpulan = "Berdasarkan dari gejala yang sama dengan certainly factor dan berdasarkan Role/Basis aturan yang sudah ditentukan oleh seorang pakar penyakit maka perhitungan Algoritma Dhamster-Shaver mengambil nilai Max Belief yang paling tinggi yakni " . round($hasil_ds['max_belief'], 2) . " yaitu " . $namaPenyakit->nama . " (" . $hasil_ds['max_penyakit'] . ")";

        // $riwayat = Riwayat::create([
        //     'nama'              => $name,
        //     'jenis_kucing'      => $request->jenis_kucing,
        //     'hasil_diagnosa'    => serialize($result['hasil_diagnosa']),
        //     'cf_max'            => serialize($result['cf_max']),
        //     'gejala_terpilih'   => serialize($result['gejala_terpilih']),
        //     'user_id'           => auth()->id()
        // ]);

        // $riwayat_id = $riwayat->id;

        // // Siapkan data yang akan disimpan
        // $combined_data = [
        //     'gejala' => $hasil_ds['gejala'],
        //     'penyakit' => $hasil_ds['penyakit']
        // ];

        // CalculateDs::create([
        //     'riwayat_id'                => $riwayat_id,
        //     'mass_function'             => serialize($result['hasil_diagnosa']),
        //     'mass_function_gabungan'    => serialize($combined_data),
        //     'hasil_akhir'               => serialize($hasil_ds['penyakit_beliefs']),
        //     'kesimpulan'                => $kesimpulan,
        //     'penyakit'                  => $hasil_ds['max_penyakit'],
        //     'created_at'                => Carbon::now()
        // ]);

        // $path = public_path('storage/downloads');

        // if (!File::isDirectory($path)) {
        //     File::makeDirectory($path, 0777, true, true);
        // }

        // $file_pdf = 'Diagnosa-' . $name . '-' . time() . '.pdf';

        // PDF::loadView('pdf.riwayat', ['id' => $riwayat->id])->save($path . "/" . $file_pdf);

        // $riwayat->update(['file_pdf' => $file_pdf]);

        // return redirect()->to(route('admin.riwayat', $riwayat->id));
        // } catch (\Exception $e) {
        //     return back()->withErrors([$e->getMessage()]);
        // }
    }
}
