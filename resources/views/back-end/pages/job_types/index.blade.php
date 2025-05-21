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

                <!-- Tombol trigger modal tetap di luar card -->
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahPekerjaanModal">
                    <i class="bi bi-plus"></i> Tambah Pekerjaan
                </button>

                <!-- Bungkus table dan modal dalam card -->
                <div class="card shadow-sm">
                    <div class="card-body">

                        <!-- Table -->
                        <div class="table-responsive">
                            <table id="myTable" class="table table-striped display w-100">
                                <thead class="text-center">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jobType as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $item->name }}</td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center align-items-center gap-1">
                                                    <button class="btn btn-action-sm btn-warning text-white" data-bs-toggle="modal"
                                                        data-bs-target="#editModal{{ $item->id }}" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>

                                                    <button class="btn btn-danger btn-action-sm text-white" data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal{{ $item->id }}" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>

                                                </div>

                                                <!-- Modal Edit -->
                                                <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1"
                                                    aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <form action="{{ route('job-type.update', $item->id) }}"
                                                            method="POST" class="modal-content">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="editModalLabel{{ $item->id }}">Edit
                                                                    Pekerjaan</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="name{{ $item->id }}"
                                                                        class="form-label">Nama
                                                                        Pekerjaan</label>
                                                                    <input type="text" class="form-control"
                                                                        id="name{{ $item->id }}" name="name"
                                                                        value="{{ $item->name }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Simpan
                                                                    Perubahan</button>
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Batal</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                                <!-- Modal Hapus -->
                                                <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1"
                                                    aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Apakah Anda yakin ingin menghapus pekerjaan ini?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form action="{{ route('job-type.destroy', $item->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-danger">Hapus</button>
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
                        <!-- End Table -->

                    </div>
                </div>
                <!-- End Card -->

                <!-- Modal Tambah Pekerjaan tetap di luar card -->
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
                                        placeholder="Masukkan Nama Pekerjaan">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
