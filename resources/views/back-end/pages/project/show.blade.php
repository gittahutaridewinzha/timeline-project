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
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">Detail Pengerjaan Project:
                                <strong>{{ $project->nama_project }}</strong></h4>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th rowspan="2">Fitur</th>
                                        <th rowspan="2">Detail Fitur</th>
                                        @foreach ($groupedUsers as $users)
                                            @foreach ($users as $user)
                                                <th>{{ $user['name'] }}</th>
                                            @endforeach
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($fiturWithProgress as $fitur)
                                        @foreach ($fitur->detailFiturs as $detail)
                                            <tr>
                                                <td>{{ $fitur->name }}</td>
                                                <td>{{ $detail->name }}</td>
                                                @foreach ($groupedUsers as $users)
                                                    @foreach ($users as $user)
                                                        @php
                                                            $progress =
                                                                $detail->progress_by_user[
                                                                    $user['id'] . '-' . $user['job_type']
                                                                ] ?? null;
                                                        @endphp
                                                        <td>
                                                            @if ($progress !== null)
                                                                <div class="progress" style="height: 20px;">
                                                                    <div class="progress-bar bg-primary text-dark fw-bold"
                                                                        style="width: {{ $progress }}%;">
                                                                        <span class="progress-text"
                                                                            style="width: 100%; text-align: center; color: white; font-weight: bold;">
                                                                            {{ $progress }}%
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
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
