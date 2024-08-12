<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; 
use App\Models\BasisPengetahuan;
use App\Models\Gejala;
use App\Models\Penyakit;
use Illuminate\Http\Request;

class DataBasisDsController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:rulesDs-list', ['only' => ['index']]);
         $this->middleware('permission:rulesDs-edit', ['only' => ['update']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = [
            'titlePage' => 'ruleDs',
            'navLink' => 'ruleDs',
            'dataBasisPengetahuan' => BasisPengetahuan::all()
        ];

        return view('admin.ruleDs.index', $datas);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BasisPengetahuan $basisPengetahuan, Request $request)
    {
        $validateReq = $request->validate([
            'kode_penyakit' => 'required',
            'kode_gejala' => 'required'
        ]);

        $basisPengetahuan->kode_penyakit = $validateReq['kode_penyakit'];
        $basisPengetahuan->kode_gejala = $validateReq['kode_gejala'];
        $basisPengetahuan->save();

        return back()->with('success', 'Data Basis Pengetahuan berhasil ditambahkan');
    }

    public function json()
    {
        $data = BasisPengetahuan::find(request('id'));

        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BasisPengetahuan  $basisPengetahuan
     * @return \Illuminate\Http\Response
     */
    public function show(BasisPengetahuan $basisPengetahuan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BasisPengetahuan  $basisPengetahuan
     * @return \Illuminate\Http\Response
     */
    public function edit($data_basis_pengetahuan, BasisPengetahuan $basisPengetahuan)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BasisPengetahuan  $basisPengetahuan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // $dataBasisPengetahuan = $basisPengetahuan->find($data_basis_pengetahuan);

        $validateReq = $request->validate([
            'kode_penyakit' => 'required',
            'kode_gejala' => 'required'
        ]);

        $data = $request->all();

        BasisPengetahuan::find($request->id)->update($data);

        // $dataBasisPengetahuan->kode_penyakit = $validateReq['kode_penyakit'];
        // $dataBasisPengetahuan->kode_gejala = $validateReq['kode_gejala'];
        // $dataBasisPengetahuan->save();

        return back()->with('success', 'Data Basis Pengetahuan berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BasisPengetahuan  $basisPengetahuan
     * @return \Illuminate\Http\Response
     */
    public function destroy($data_basis_pengetahuan, BasisPengetahuan $basisPengetahuan)
    {
        $dataBasisPengetahuan = $basisPengetahuan->find($data_basis_pengetahuan);
        $dataBasisPengetahuan->delete();

        return back()->with('success', 'Data Basis Pengetahuan berhasil dihapus');
    }
}
