@extends('back-end.layouts.main')

@section('content')
    <div class="main-panel" style="margin-top: 40px;">

        <div class="content-wrapper">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="col-lg-12 grid-margin stretch-card">


                <div class="card">
                    <div class="card-body">
                        {{-- Judul dan Tombol Add New --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">Data User</h4>

                        </div>



                        <div class="table-responsive">
                            <table id="myTable" class="table table-striped display w-100">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user as $u)
                                        <tr>
                                            <td>{{ $u->name }}</td>
                                            <td>{{ $u->email }}</td>
                                            <td>{{ $u->role->name }}</td>
                                            <td>
                                                <div class="d-flex justify-content-center align-items-center gap-1">
                                                    <button
                                                        onclick="window.location='{{ route('data-user.edit', $u->id) }}'"
                                                        class="btn btn-warning btn-action-sm" title="Edit">
                                                        <i class="bi bi-pencil-square"></i>
                                                     </button>

                                                    <button class="btn btn-danger btn-action-sm" data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal{{ $u->id }}" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>

                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal{{ $u->id }}" tabindex="-1" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus Project ini?
                </div>
                <div class="modal-footer">
                    <form action="{{ route('data-user.destroy', $u->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
@endsection
