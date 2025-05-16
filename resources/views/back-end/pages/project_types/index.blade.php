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

            <h2 class="text-center mb-4">Daftar Tipe Project</h2>

            <!-- Button trigger modal -->
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahModal">
                <i class="bi bi-plus"></i> Tambah Tipe Project
            </button>

            <!-- Modal Tambah -->
            <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('project-type.store') }}" method="POST" class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="tambahModalLabel">Tambah Tipe Project</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Tipe Project</label>
                                <input type="text" class="form-control" name="name" id="name" required placeholder="Masukkan nama tipe project">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Nama</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ProjectType as $type)
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $type->name }}</td>
                                <td>
                                    <!-- Edit Button -->
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $type->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <!-- Delete Button -->
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $type->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="editModal{{ $type->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $type->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{ route('project-type.update', $type->id) }}" method="POST" class="modal-content">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel{{ $type->id }}">Edit Tipe Project</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="name{{ $type->id }}" class="form-label">Nama</label>
                                                        <input type="text" class="form-control" name="name" id="name{{ $type->id }}" value="{{ $type->name }}" required>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Modal Hapus -->
                                    <div class="modal fade" id="deleteModal{{ $type->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $type->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{ route('project-type.destroy', $type->id) }}" method="POST" class="modal-content">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $type->id }}">Konfirmasi Hapus</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body">
                                                    Apakah Anda yakin ingin menghapus tipe project ini?
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        @if ($ProjectType->isEmpty())
                            <tr>
                                <td colspan="3" class="text-center">Belum ada data tipe project.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $ProjectType->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
