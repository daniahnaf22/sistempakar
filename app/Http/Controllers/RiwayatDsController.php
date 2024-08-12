<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DiagnosaDs;
use App\Models\Hasil;
use Illuminate\Http\Request;

class RiwayatDsController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:riwayatDs-list', ['only' => ['index']]);
         $this->middleware('permission:riwayatDs-show', ['only' => ['showdata']]);
    }
    public function index()
    {
        $datas = [
            'titlePage' => 'Data Riwayat',
            'navLink' => 'data-riwayat',
            'dataDiagnosa' => DiagnosaDs::all()
        ];

        return view('admin.riwayatDs', $datas);
    }

    public function showdata($id_diagnosa)
    {
        $dataDiagnosa = DiagnosaDs::find($id_diagnosa)->toArray();

        $dataTampilan = [
            'titlePage' => 'Hasil Diagnosa',
            'navLink' => 'diagnosa',
            'namaPemilik' => $dataDiagnosa['nama_pemilik'],
            'diagnosa' => json_decode($dataDiagnosa['diagnosa']),
            'penyebab' => json_decode($dataDiagnosa['penyebab'])
        ];

        return view('admin.hasilDs', $dataTampilan);
    
    }

    public function destroy($id_diagnosa)
    {
        $dataDiagnosa = DiagnosaDs::find($id_diagnosa);
        $dataDiagnosa->delete();

        return back()->with('success', 'Data Riwayat Berhasil Dihapus');
    }
}
