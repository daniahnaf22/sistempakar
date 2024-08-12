<x-app-layout>
    <style>
        .info-item {
            display: inline-block;
            margin-right: 11px;
        }
    </style>
    <x-slot name="title">Hasil Dempster Shafer</x-slot>

    @if (session()->has('success'))
        <x-alert type="success" message="{{ session()->get('success') }}" />
    @endif
    <x-card title="Berikut hasil diagnosa penyakit">
        <p class="mb-4">
            <span class="info-item">
                <i class="fas fa-user mr-1"></i> {{ $riwayat->nama }}
            </span>
            <span class="info-item">
                <i class="fas fa-cat mr-1"></i> {{ $riwayat->jenis_kucing }}
            </span>
            <span class="info-item">
                <i class="fas fa-calendar ml-4 mr-1"></i> {{ $kesimpulan->created_at->format('d M Y, H:i:s') }}
            </span>

        </p>

        {{-- <div class="card-header bg-primary text-white p-2">
            <h6 class="font-weight-bold">Hasil Mass Function Gabungan</h6>
        </div>
        <table class="table table-hover border">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th></th>
                    <th>Belief</th>
                    <th>Plausibility</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mass_function_gabungan['hasil_konversi'] as $no => $value)
                    <tr>
                        <td>{{ $no + 1 }}</td>
                        @foreach ($value['data'] as $item)
                            <td>{{ implode(', ', $item['array']) }}</td>
                        @endforeach
                        @foreach ($value['data'] as $items)
                            <td>{{ $items['value'] }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table> --}}

        <div class="card card-body p-0 mt-5 border" style="box-shadow: none !important;">
            <div class="card-header bg-primary text-white p-2">
                <h6 class="font-weight-bold">Gejala Yang Dipilih</h6>
            </div>
            <table class="table table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Kode</th>
                        <th>Nama Gejala</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mass_function_gabungan['gejala_penyakit'] as $gejala)
                        <tr>
                            <td>{{ $gejala['kode'] }}</td>
                            <td>{{ $gejala['nama'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card card-body p-0 mt-5 border" style="box-shadow: none !important;">
            <div class="card-header bg-primary text-white p-2">
                <h6 class="font-weight-bold">Hasil Akhir</h6>
            </div>
            <table class="table table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Kode Penyakit</th>
                        <th>Value</th>
                    </tr>
                </thead>
                {{-- @dd($hasil_akhir) --}}
                <tbody>
                    @foreach ($mass_function_gabungan['result'] as $kd => $val)
                        <tr>
                            <td>{{ implode(', ', $val['array']) }}</td>
                            <td>{{ $val['value'] }}</td>
                            {{-- <td>{{ $hasil_akhir['m_tidak_penyakit'] }}</td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-5">
            <div class="alert alert-success">
                <h5 class="font-weight-bold">Kesimpulan</h5>
                <p>
                    {{ $kesimpulan->kesimpulan }}
                </p>
            </div>
            {{-- <div class="mt-3 text-center">
                <a href="{{ asset("storage/downloads/$riwayat->file_pdf") }}" target="_blank"
                    class="btn btn-primary mr-1"><i class="fas fa-print mr-1"></i> Print</a>
                <a href="{{ route('admin.diagnosa') }}" class="btn btn-warning mr-1"><i class="fas fa-redo mr-1"></i>
                    Diagnosa ulang</a>
                <a href="{{ route('admin.showCalculateDs') }}" class="btn btn-success mr-1"><i
                        class="fas fa-calculator mr-1"></i>
                    Dempster Shafer</a>
            </div> --}}
        </div>
    </x-card>
</x-app-layout>
