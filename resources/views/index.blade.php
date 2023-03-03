@extends('layout')

@section('title', 'Keluarga')

@section('content')
    <h1 class="mb-5">Keluarga</h1>

    <div class="d-flex justify-content-between mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTree">Visualisasi Tree</button>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAddPerson">+ Tambah Anggota Keluarga</button>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr class="table-secondary">
                <th scope="col" style="width: 50px">#</th>
                <th scope="col">Nama</th>
                <th scope="col">Jenis Kelamin</th>
                <th scope="col">Orang Tua</th>
                <th scope="col" style="width: 150px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($persons as $i => $person)
                <tr>
                    <th scope="row">{{ $i + 1 }}</th>
                    <td>{{ $person->nama }}</td>
                    <td>{{ $person->jenis_kelamin }}</td>
                    <td>
                        @if($person->parent)
                            {{ $person->parent->nama }}
                        @else
                            <span class="badge bg-secondary">kosong</span>
                        @endif
                    </td>
                    <td>
                        <button onclick="editPerson({{ $person->id.',\''.$person->nama.'\',\''.$person->jenis_kelamin.'\','. ($person->parent_id ? '\''.$person->parent_id.'\'' : 'null') }})" class="btn btn-sm btn-outline-warning me-2">Edit</button>
                        <button onclick="deletePerson({{ $person->id }})" class="btn btn-sm btn-outline-danger">Hapus</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal Tree -->
    <div class="modal fade" id="modalTree" tabindex="-1" data-bs-backdrop="static" aria-labelledby="modalTreeLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalTreeLabel">Visualisasi Tree</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="tree-simple"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Person -->
    <div class="modal fade" id="modalAddPerson" tabindex="-1" data-bs-backdrop="static" aria-labelledby="modalAddPersonLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalAddPersonLabel">Tambah Anggota Keluarga Baru</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('/person') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama</label>
                            <input type="text" name="nama" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jenis Kelamin</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" value="laki-laki" checked>
                                <label class="form-check-label">
                                    Laki - Laki
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" value="perempuan">
                                <label class="form-check-label">
                                    Perempuan
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Orang Tua</label>
                            <select class="form-select" name="parent_id">
                                <option value="null" selected>Kosong</option>
                                @foreach($persons as $person)
                                    <option value="{{ $person->id }}">{{ $person->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Person -->
    <div class="modal fade" id="modalEditPerson" tabindex="-1" data-bs-backdrop="static" aria-labelledby="modalEditPersonLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalEditPersonLabel">Edit Anggota Keluarga Baru</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama</label>
                            <input type="text" name="nama" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jenis Kelamin</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" value="laki-laki" checked>
                                <label class="form-check-label">
                                    Laki - Laki
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" value="perempuan">
                                <label class="form-check-label">
                                    Perempuan
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Orang Tua</label>
                            <select class="form-select" name="parent_id">
                                <option value="null" selected>Kosong</option>
                                @foreach($persons as $person)
                                    <option value="{{ $person->id }}">{{ $person->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-warning">Edit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Delete Person -->
    <div class="modal fade" id="modalDeletePerson" tabindex="-1" data-bs-backdrop="static" aria-labelledby="modalDeletePersonLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalDeletePersonLabel">Hapus Anggota Keluarga</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Anda yakin ingin menghapus anggota keluarga ini?</p>
                    
                    <form action="" method="post">
                        @csrf
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .nodeExample1 {
            padding: .5rem 1rem;
            -webkit-border-radius: 6px;
            -moz-border-radius: 6px;
            border-radius: 6px;
            color: #fff;
            font-size: 16px;
        }
        .nodeExample1[data-gender="male"] {
            background-color: blue;
        }
        .nodeExample1[data-gender="female"] {
            background-color: deeppink;
        }
        .node-name {
            margin: 0;
            padding: 0;
            text-align: center;
            font-weight: 500;
        }
    </style>

@endsection

@push('scripts')
    <script>
        const baseUrl = "{{ url('/') }}";

        let simple_chart_config = {
            chart: {
                container: "#tree-simple",
                connectors: { type: 'step' },
                node: {
                    HTMLclass: 'nodeExample1',
                }
            },    
            nodeStructure: null,
        };

        document.getElementById('modalTree').addEventListener('shown.bs.modal', event => {
            document.querySelector('#modalTree .modal-body').innerHTML = '<p class="text-center fw-bold">Loading...</p>';
            fetch(baseUrl+'/api/person/tree')
                .then(res => res.json())
                .then(({data}) => {
                    simple_chart_config.nodeStructure = data;
                    document.querySelector('#modalTree .modal-body').innerHTML = '<div id="tree-simple"></div>';
                    const chart = new Treant(simple_chart_config);
                });
        });

        function deletePerson(id) {
            document.querySelector('#modalDeletePerson form').setAttribute('action', baseUrl+'/person/'+id+'/destroy');

            const modalDeletePerson = new bootstrap.Modal('#modalDeletePerson');
            modalDeletePerson.show();
        }

        function editPerson(id, nama, jenisKelamin, parentId) {
            document.querySelector('#modalEditPerson form').setAttribute('action', baseUrl+'/person/'+id+'/update');
            document.querySelector('#modalEditPerson form input[name="nama"]').value = nama;
            document.querySelector('#modalEditPerson form input[value="'+jenisKelamin+'"]').checked = true;
            document.querySelector('#modalEditPerson form select[name="parent_id"]').value = parentId;

            const modalEditPerson = new bootstrap.Modal('#modalEditPerson');
            modalEditPerson.show();
        }

    </script>
@endpush