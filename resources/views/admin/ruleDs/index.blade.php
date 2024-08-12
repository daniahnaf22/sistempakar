<x-app-layout>
    <x-slot name="title">Daftar Data Basis DS</x-slot>
    <x-alert-error></x-alert-error>
    @if (session()->has('success'))
        <x-alert type="success" message="{{ session()->get('success') }}" />
    @endif
    <x-card>
        <x-slot name="option">
            <div class="btn btn-success add">
                <i class="fas fa-plus mr-1"></i> Tambahkan Data Basis DS
            </div>
        </x-slot>
        <table class="table table-bordered custom-table" id="responsive-table">
            <thead class="text-center">
                <tr>
                    <th class="align-middle">No.</th>
                    <th class="align-middle">Kode Penyakit</th>
                    <th class="align-middle">Kode Gejala</th>
                    <th class="align-middle"></th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                @endphp
                @foreach ($dataBasisPengetahuan as $listBasisPengetahuan)
                    <tr>
                        <td class="align-middle text-center">{{ $i }}</td>
                        <td class="align-middle text-center">{{ $listBasisPengetahuan->kode_penyakit }}</td>
                        <td class="align-middle text-center">{{ $listBasisPengetahuan->kode_gejala }}</td>
                        <td class="align-middle text-center">
                            <div class="d-flex justify-between-space">
                                <div>
                                    <button class="btn btn-primary btn-sm edit"
                                        data-id="{{ $listBasisPengetahuan->id_basis_pengetahuan }}"><i
                                            class="fas fa-edit"></i></button>
                                </div>
                                <form
                                    action="{{ route('admin.ruleDs.destroy', $listBasisPengetahuan->id_basis_pengetahuan) }}"
                                    method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm ml-1 delete"><i
                                            class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @php
                        $i++;
                    @endphp
                @endforeach
            </tbody>
        </table>
    </x-card>

    <x-modal title="Tambahkan Data Basis" id="ruleDs">
        <form action="{{ route('admin.ruleDs.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="kode_penyakit">Kode Penyakit</label>
                        <input type="text" class="form-control" name="kode_penyakit">
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="kode_gejala">Kode Gejala</label>
                            <input type="text" class="form-control" name="kode_gejala">
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-2">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </x-modal>

    <x-modal title="Update Data Basis" id="edit-ruleDs">
        <form action="{{ route('admin.ruleDs.update') }}" method="POST">
            @csrf
            <input type="hidden" name="id">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="kode_penyakit">Kode Penyakit</label>
                        <input type="text" class="form-control" name="kode_penyakit">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="kode_gejala">Kode Gejala</label>
                        <input type="text" class="form-control" name="kode_gejala">
                    </div>
                </div>
            </div>
            <div class="mt-2">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </x-modal>

    <x-slot name="script">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $('.add').click(function() {
                $('#ruleDs').modal('show')
            })

            $('.delete').click(function(e) {
                e.preventDefault()
                Swal.fire({
                    title: 'Hapus data ruleDs?',
                    text: "Kamu tidak akan bisa mengembalikannya kembali!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).parent().submit()
                    }
                })
            })

            $('.edit').click(function() {
                const id = $(this).data('id')

                $.get(`{{ route('admin.ruleDs.json') }}?id=${id}`, function(res) {
                    $('#edit-ruleDs input[name="id"]').val(res.id_basis_pengetahuan)
                    $('#edit-ruleDs input[name="kode_penyakit"]').val(res.kode_penyakit)
                    $('#edit-ruleDs input[name="kode_gejala"]').val(res.kode_gejala)

                    $('#edit-ruleDs').modal('show')
                })
            })
        </script>
    </x-slot>
</x-app-layout>
