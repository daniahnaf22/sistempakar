<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade as PDF;
use App\Models\{BasisPengetahuan, CalculateDs, Gejala, GejalaPenyakit, Penyakit, Rule, Riwayat};
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
        // Ambil nilai densitas dari tabel gejalas berdasarkan kode gejala
        $kodeGejalas = [];

        foreach ($hasil_cf as $penyakit) {
            $kodeGejalas[] = $penyakit['kode'];
        }

        // dd($kodeGejalas);

        foreach ($kodeGejalas as $key => $value) {
            $dataPenyakit[$key] = BasisPengetahuan::where('kode_gejala', $value)
                ->select('kode_penyakit')
                ->get()
                ->toArray();

            foreach ($dataPenyakit[$key] as $a => $b) {
                $resultData[$key]['daftar_penyakit'][$a] = $b['kode_penyakit'];
            }

            $dataNilaiDensitas[$key] = Gejala::where('kode', $value)
                ->select('nilai_densitas', 'nama')
                ->get()
                ->toArray();

            $dataGejala[$key] = $dataNilaiDensitas[$key][0]['nama'];
            $resultData[$key]['belief'] = $dataNilaiDensitas[$key][0]['nilai_densitas'];
            $resultData[$key]['plausibility'] = 1 - $dataNilaiDensitas[$key][0]['nilai_densitas'];
        }

        $variabelTampilan = $this->dempsterShaferService->combineBelief($resultData);

        foreach ($dataGejala as $key => $value) {
            $variabelTampilan['Gejala_Penyakit'][$key]['kode'] = $kodeGejalas[$key];
            $variabelTampilan['Gejala_Penyakit'][$key]['nama'] = $value;
        }


        $diagnosaSavedData = [
            'nama' => $variabelTampilan['Nama_Penyakit']['nama'],
            'hasil_konversi' => $variabelTampilan['hasil_konversi'],
            'nilai_belief' => $variabelTampilan['Nilai_Belief_Penyakit'],
            'persentase_penyakit' => $variabelTampilan['Persentase_Penyakit'],
            'gejala_penyakit' => $variabelTampilan['Gejala_Penyakit'],
            'result' => $variabelTampilan['result']
        ];


        return $diagnosaSavedData;
    }


    public function diagnosa(Request $request)
    {
        $name = $request->nama;

        if (auth()->user()->hasRole('Admin')) {
            $request->validate(['nama' => 'required|string|max:100']);
            $name = $request->nama;
        }

        $data = $request->all();

        $result = $this->kalkulasi_cf($data);

        if (!isset($result['cf_max']) || !isset($result['hasil_diagnosa'])) {
            return back()->withErrors(['Terjadi kesalahan pada hasil perhitungan CF']);
        }
        // Perhitungan DS menggunakan hasil CF yang didapat
        $hasil_cf = $result['hasil_diagnosa'];
        $hasil_ds = $this->kalkulasi_ds($result['gejala_terpilih']);

        DB::beginTransaction();
        try {

            $kode_penyakit = Penyakit::select('kode')->where('nama', 'like', '%' . $hasil_ds['nama'] . '%')->first();
            $kesimpulan = "Berdasarkan dari gejala yang sama dengan certainly factor dan berdasarkan Role/Basis aturan yang sudah ditentukan oleh seorang pakar penyakit maka perhitungan Algoritma Dempster-Shafer mengambil nilai Max Belief yang paling tinggi yakni " . $hasil_ds['nilai_belief'] . " yaitu " . $hasil_ds['nama'] . " (" . $kode_penyakit->kode . ")";


            $riwayat = Riwayat::create([
                'nama' => $name,
                'jenis_kucing' => $request->jenis_kucing,
                'hasil_diagnosa' => serialize($result['hasil_diagnosa']),
                'cf_max' => serialize($result['cf_max']),
                'gejala_terpilih' => serialize($result['gejala_terpilih']),
                'user_id' => auth()->id()
            ]);

            $riwayat_id = $riwayat->id;


            CalculateDs::create([
                'riwayat_id' => $riwayat_id,
                'mass_function' => serialize($result['hasil_diagnosa']),
                'mass_function_gabungan' => serialize($hasil_ds),
                'hasil_akhir' => $hasil_ds['nilai_belief'],
                'kesimpulan' => $kesimpulan,
                'penyakit' => $kode_penyakit->kode,
                'created_at' => Carbon::now()
            ]);

            $path = public_path('storage/downloads');

            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }

            $file_pdf = 'Diagnosa-' . $name . '-' . time() . '.pdf';

            PDF::loadView('pdf.riwayat', ['id' => $riwayat->id])->save($path . "/" . $file_pdf);

            $riwayat->update(['file_pdf' => $file_pdf]);
            DB::commit();

            return redirect()->to(route('admin.riwayat', $riwayat->id));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors([$e->getMessage()]);
        }
    }
}
