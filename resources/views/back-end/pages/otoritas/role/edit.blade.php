@extends('back-end.layouts.main')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Role</h4>

                        <form action="{{ route('roles.update', $role->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="nama">Nama Role</label>
                                <input type="text" name="name" class="form-control" id="nama"
                                    value="{{ old('name', $role->name) }}" placeholder="Nama Role">
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Hak Akses</label>
                                        <div class="row">
                                            @foreach ($menus as $menu)
                                                <div class="col-md-3">
                                                    <div class="form-switch">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="menu-{{ $menu->id }}" name="menus[]"
                                                            value="{{ $menu->id }}"
                                                            {{ in_array($menu->id, $roleMenuIds) ? 'checked' : '' }}>
                                                        <label class="form-check-label" style="margin-top: 10px;"
                                                            for="menu-{{ $menu->id }}">{{ $menu->name }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mr-2">Update</button>
                            <a href="{{ route('roles.index') }}" class="btn btn-light">Cancel</a>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
