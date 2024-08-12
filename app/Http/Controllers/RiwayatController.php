<?php

namespace App\Http\Controllers;

use App\Models\CalculateDs;
use App\Models\Gejala;
use App\Models\Penyakit;
use App\Models\Riwayat;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:riwayat-list', ['only' => ['index']]);
        // $this->middleware('permission:riwayat-show', ['only' => ['show']]);
        // $this->middleware('permission:riwayat-show', ['only' => ['show']]);
    }

    public function index()
    {
        if (auth()->user()->hasRole('Admin')) {
            $riwayat = Riwayat::with('penyakit')
                ->latest()
                ->paginate(10);
        } else {
            $riwayat = auth()->user()
                ->riwayats()
                ->with('penyakit')
                ->latest()
                ->paginate(10);
        }

        return view('admin.riwayat.index', compact('riwayat'));
    }

    public function show(Riwayat $riwayat)
    {
        $this->authorize('show', $riwayat);
        return view('admin.riwayat.show', compact('riwayat'));
    }

    public function showCalculateDs($riwayat_id)
    {
        $calculate_ds = CalculateDs::where('riwayat_id', $riwayat_id)->firstOrFail();
        $riwayat      = Riwayat::find($riwayat_id);

        $mass_functions = unserialize($calculate_ds->mass_function);
        $mass_function_gabungan = unserialize($calculate_ds->mass_function_gabungan);


        return view('admin.riwayat.show_ds', [
            'mass_functions'         => $mass_functions,
            'mass_function_gabungan' => $mass_function_gabungan,
            'kesimpulan'             => $calculate_ds,
            'riwayat'                => $riwayat
        ]);
    }
}
