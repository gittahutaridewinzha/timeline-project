@extends('back-end.layouts.main')

@section('content')
    <div class="main-panel" style="margin-top: 10px;">

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
                            <h4 class="card-title mb-0">Manajemen Role</h4>
                            <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Add New
                            </a>
                        </div>



                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Role</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $r)
                                    <tr>
                                        <td>{{ $r->name }}</td>
                                        <td>
                                            <a href="{{ route('roles.edit', $r->id) }}" class="btn btn-sm btn-warning text-white">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a type="button" class="btn btn-sm btn-danger text-white"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                data-id="{{ $r->id }}">
                                                <i class="bi bi-trash"></i>
                                            </a>
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
@endsection
