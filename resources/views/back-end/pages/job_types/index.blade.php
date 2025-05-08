@extends('back-end.layouts.main')

@section('content')
    <div class="main-panel" style="margin-top: 10px;">

        <div class="content-wrapper">
            <div class="mt-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <h2 class="text-center mb-4">Daftar Pekerjaan</h2>

                <!-- Tombol trigger modal -->
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahPekerjaanModal">
                    <i class="bi bi-plus"></i> Tambah Pekerjaan
                </button>

                <!-- Modal Tambah Pekerjaan -->
                <div class="modal fade" id="tambahPekerjaanModal" tabindex="-1" aria-labelledby="tambahPekerjaanModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('job-type.store') }}" method="POST" class="modal-content">
                            @csrf

                            <div class="modal-header">
                                <h5 class="modal-title" id="tambahPekerjaanModalLabel">Tambah Pekerjaan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Pekerjaan</label>
                                    <input type="text" class="form-control" id="name" name="name" required
                                        placeholder="Masukan Nama Pekerjaan">
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>

                <table id="myTable" class="table table-bordered">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Nama</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jobType as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->name }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center gap-1">
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $item->id }}" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $item->id }}" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1"
                                        aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{ route('job-type.update', $item->id) }}" method="POST"
                                                class="modal-content">
                                                @csrf
                                                @method('PUT')

                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel{{ $item->id }}">Edit
                                                        Pekerjaan
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Tutup"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="name{{ $item->id }}" class="form-label">Nama
                                                            Pekerjaan</label>
                                                        <input type="text" class="form-control"
                                                            id="name{{ $item->id }}" name="name"
                                                            value="{{ $item->name }}" required>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Modal konfirmasi hapus -->
                                    <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1"
                                        aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Apakah Anda yakin ingin menghapus Pekerjaan ini?
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('job-type.destroy', $item->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                                    </form>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
