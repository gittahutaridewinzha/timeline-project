@extends('back-end.layouts.main')

@section('content')
<div class="main-panel" style="margin-top: 10px;">
    <div class="content-wrapper">
        <div class="col-lg-8 mx-auto grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-center mb-4">Edit Project</h4>

                    <form action="{{ route('project.update', $project->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="nama_project">Nama Project</label>
                            <input type="text" name="nama_project" id="nama_project" class="form-control" value="{{ $project->nama_project }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control" required>{{ $project->deskripsi }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
