@extends('back-end.layouts.main')

@section('content')
<div class="main-panel" style="margin-top: 10px;">
    <div class="content-wrapper">
        <div class="col-lg-8 mx-auto grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-center mb-4">Tambah Project</h4>

                    <form action="{{ route('project.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="nama_project">Nama Project</label>
                            <input type="text" name="nama_project" id="nama_project" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control" required></textarea>
                        </div>

                        {{-- Hidden input untuk user yang sedang login --}}
                        <input type="hidden" name="id_project_manager" value="{{ Auth::user()->id }}">

                        <button type="submit" class="btn btn-primary w-100">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
