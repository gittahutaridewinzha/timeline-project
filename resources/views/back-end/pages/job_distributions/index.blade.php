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
            <div class="col-lg-12 mx-auto grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-3">
                            Pembagian Pekerjaan untuk Project: {{ $project->nama_project }}
                        </h4>

                        <form action="{{ route('penugasan.store', $project->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="project_id" value="{{ $project->id }}">

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Jenis Pekerjaan</th>
                                            <th>Pilih Penanggung Jawab</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; @endphp
                                        @foreach ($project->jobTypes as $jobType)
                                            @php
                                                $selectedUserId = optional(
                                                    optional(
                                                        $project->jobTypeAssignments->firstWhere('id', $jobType->id),
                                                    )->pivot,
                                                )->user_id;
                                            @endphp
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $jobType->name }}</td>
                                                <td>
                                                    <select name="assignments[{{ $jobType->id }}]"
                                                        class="form-select select-user" required>
                                                        <option value="">Pilih Penanggung Jawab</option>
                                                        @foreach ($users as $user)
                                                            <option value="{{ $user->id }}"
                                                                {{ $user->id == $selectedUserId ? 'selected' : '' }}>
                                                                {{ $user->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">
                                Simpan Penugasan
                            </button>
                            <a href="{{ route('project.index') }}" class="btn btn-secondary mt-3">
                                Kembali
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select-user').select2({
                placeholder: "Pilih Penanggung Jawab...",
                width: '100%'
            });
        });
    </script>
    <style>
        .select2-container .select2-selection--single {
            height: 38px;
            padding: 5px;
        }
    </style>
@endpush
