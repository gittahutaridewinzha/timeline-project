@extends('back-end.layouts.main')

@section('content')
    <div class="main-panel" style="margin-top: 20px;">
        <div class="content-wrapper">
            <h4 class="card-title mb-4">Proyek yang Diikuti</h4>

            @if($projects->isEmpty())
                <div class="alert alert-warning" role="alert">
                    Anda belum terlibat dalam proyek manapun.
                </div>
            @else
                <div class="row">
                    @foreach ($projects as $project)
                        <div class="col-md-6 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <p class="card-text"><strong>Nama Proyek:</strong> {{ $project->nama_project }}</p>
                                    <p class="card-text">
                                        <strong>Job Type:</strong>
                                        @php
                                            $userJobTypes = $project->taskDistributions->where('user_id', Auth::id())->pluck('jobType.name')->unique();
                                        @endphp

                                        @if($userJobTypes->isEmpty())
                                            <span class="text-muted">Tidak ada</span>
                                        @else
                                            {{ $userJobTypes->implode(', ') }}
                                        @endif
                                    </p>

                                    <a href="{{ route('pengerjaan.create', $project->id) }}" class="btn btn-primary btn-sm">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
