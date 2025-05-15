@extends('back-end.layouts.main')

@section('content')
    <div class="main-panel" style="margin-top: 40px;">
        <div class="content-wrapper">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">Overview Project</h4>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nama Project</th>
                                        <th class="text-center">Progress</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($project as $project)
                                        <tr>
                                            <td>{{ $project->nama_project }}</td>
                                            <td>
                                                <div class="progress" style="height: 20px; position: relative;">
                                                    <div class="progress-bar {{ $project->persentase_pengerjaan == 0 ? 'bg-secondary' : 'bg-primary' }}"
                                                        role="progressbar"
                                                        style="width: {{ $project->persentase_pengerjaan ?? 0 }}%;"
                                                        aria-valuenow="{{ $project->persentase_pengerjaan ?? 0 }}"
                                                        aria-valuemin="0" aria-valuemax="100">
                                                        <span class="progress-text"
                                                            style="position: absolute; width: 100%; text-align: center; color: black; font-weight: bold; top: 50%; transform: translateY(-50%);">
                                                            {{ number_format($project->persentase_pengerjaan, 2) }}%
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('overview-project.show', $project->id) }}"
                                                    class="btn btn-sm btn-primary text-white">
                                                    Detail
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
