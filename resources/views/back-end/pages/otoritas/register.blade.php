@extends('back-end.layouts.main')
@section('content')
    <div class="main-panel" style="margin-top: 10px;">
        <div class="content-wrapper">
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top: 20px;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Register Admin</h4>
                        <form action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputUsername1">Nama</label>
                                <input type="text" class="form-control" id="name" placeholder="Nama" name="name">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email address</label>
                                <input type="email" class="form-control" id="email" placeholder="Email" name="email">
                            </div>
                            <div class="form-group">
                                <label for="role_id" class="form-label">Role</label>
                                <select class="form-control @error('role_id') is-invalid @enderror" name="role_id" required>
                                    <option value="">-- Pilih Role --</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Password</label>
                                <input type="password" class="form-control" id="password" placeholder="Password" name="password">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputConfirmPassword1">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    placeholder="Password" name="password_confirmation">
                            </div>
                            <button type="submit" class="btn btn-primary mr-2">Submit</button>
                            <button class="btn btn-light">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
