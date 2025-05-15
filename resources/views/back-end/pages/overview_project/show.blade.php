@extends('back-end.layouts.main')

@section('content')
    <div class="main-panel" style="margin-top: 40px;">
        <div class="content-wrapper">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-4">Detail Project: <strong>{{ $project->nama_project }}</strong></h4>
                        <div class="mb-4">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 200px;">Nama Project</th>
                                    <td>{{ $project->nama_project }}</td>
                                </tr>
                                <tr>
                                    <th>Deskripsi</th>
                                    <td>{!! $project->deskripsi !!}</td>
                                </tr>
                                <tr>
                                    <th>Project Manager</th>
                                    <td>{{ $project->ProjectManager->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Value</th>
                                    <td>
                                        @if ($project->valueProject)
                                            Rp {{ number_format($project->valueProject->value_project, 0, ',', '.') }}
                                        @else
                                            Tidak tersedia
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span
                                            class="badge {{ $project->status == 'completed' ? 'bg-success' : 'bg-warning text-dark' }}">
                                            {{ ucfirst($project->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Deadline</th>
                                    <td>{{ \Carbon\Carbon::parse($project->deadline)->format('d M Y') }}</td>
                                </tr>
                            </table>
                        </div>

                        <h5 class="mb-3">Progress Fitur dan Detail Fitur</h5>
                        <div class="accordion" id="accordionFitur">
                            @foreach ($fiturWithProgress as $index => $fitur)
                                <div class="accordion-item mb-3">
                                    <h2 class="accordion-header" id="heading{{ $index }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $index }}" aria-expanded="false"
                                            aria-controls="collapse{{ $index }}">
                                            {{ $fitur->name }}
                                            <span class="badge bg-success ms-auto">
                                                {{ number_format($fitur->totalProgress, 2) }}%
                                            </span>
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $index }}" class="accordion-collapse collapse"
                                        aria-labelledby="heading{{ $index }}" data-bs-parent="#accordionFitur">
                                        <div class="accordion-body">
                                            @foreach ($fitur->detailFiturs as $detail)
                                                <div class="mb-3">
                                                    <h6 class="fw-bold">{{ $detail->name }}</h6>
                                                    <div class="progress mb-2" style="height: 20px;">
                                                        <div class="progress-bar bg-info text-dark fw-bold"
                                                            style="width: {{ $detail->rata_rata_progress ?? 0 }}%;">
                                                            {{ number_format($detail->rata_rata_progress ?? 0, 2) }}%
                                                        </div>
                                                    </div>

                                                    <ul class="list-group list-group-flush">
                                                        @forelse ($detail->pengerjaans as $pengerjaan)
                                                            <li
                                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <i class="bi bi-person-circle"></i>
                                                                    <strong>{{ $pengerjaan->user->name }}</strong>
                                                                </div>
                                                                <span class="badge bg-primary">
                                                                    {{ $pengerjaan->pengerjaan }}%
                                                                </span>
                                                            </li>
                                                        @empty
                                                            <li class="list-group-item text-muted">Belum ada pengerjaan</li>
                                                        @endforelse
                                                    </ul>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-5">
                            <h5>Total Progress Project:</h5>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-success fw-bold" style="width: {{ $totalAll }}%;">
                                    {{ number_format($totalAll, 2) }}%
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
