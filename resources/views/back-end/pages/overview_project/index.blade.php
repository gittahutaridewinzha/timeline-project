@extends('back-end.layouts.main')

@section('content')
    @php
        use Carbon\Carbon;
    @endphp

    <div class="main-panel" style="margin-top: 40px;">
        <div class="content-wrapper">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">Overview Project</h4>
                        </div>


                        <div class="table-responsive">
                             <table id="myTable" class="table table-striped display w-100">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nama Project</th>
                                        <th class="text-center">Progress</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($project as $project)
                                        @php
                                            $isLate = $project->deadline && Carbon::now()->gt(Carbon::parse($project->deadline)) && $project->persentase_pengerjaan < 100;
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $project->nama_project }}</td>
                                            <td>
                                                <div class="progress {{ $isLate ? 'border border-danger' : '' }}" style="height: 20px; position: relative;">
                                                    <div class="progress-bar
                                                        {{ $isLate ? 'bg-danger' : ($project->persentase_pengerjaan == 0 ? 'bg-secondary' : 'bg-primary') }}"
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
                                                @if ($isLate)
                                                    <span class="text-danger fw-bold d-block mt-1 text-center"></span>
                                                @endif
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
