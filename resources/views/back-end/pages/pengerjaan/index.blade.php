@extends('back-end.layouts.main')

@section('content')
    <div class="main-panel" style="margin-top: 35px;">
        <div class="content-wrapper">
            <h2 class="text-center mb-4">Proyek Yang Anda Ikuti</h2>

                @if($projects->isEmpty())
                    <div class="alert alert-warning text-center" role="alert">
                        <i class="mdi mdi-alert-circle-outline"></i> Anda belum terlibat dalam proyek manapun.
                    </div>
                @else
                <div class="row">
                    @foreach ($projects as $project)
                        <div class="col-lg-6 col-md-12 mb-4" style="margin-top: 20px;">
                            <div class="card border-left-primary shadow-sm rounded-lg h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-dark font-weight-bold">
                                        <i class="mdi mdi-briefcase-outline text-primary"></i>
                                        {{ $project->nama_project }}
                                    </h5>

                                    <p class="mb-2">
                                        <div class="text-muted" style="font-size: 14px;">
                                            <i class="mdi mdi-tag-multiple text-success"></i>
                                            Kategori:
                                            {{ $project->CategoryProject->name ?? 'Tidak ada kategori' }}
                                        </div>
                                    </p>

                                    <hr>

                                    <p class="mb-2">
                                        <strong class="text-secondary">Job Type:</strong>
                                        @php
                                            $userJobTypes = $project->taskDistributions->where('user_id', Auth::id())->pluck('jobType.name')->unique();
                                        @endphp

                                        @if($userJobTypes->isEmpty())
                                            <span class="badge badge-secondary">Tidak ada</span>
                                        @else
                                            @foreach($userJobTypes as $jobType)
                                                <span class="badge badge-info">{{ $jobType }}</span>
                                            @endforeach
                                        @endif
                                    </p>

                                    <div class="d-flex align-items-center mt-2">
                                        <a href="{{ route('pengerjaan.tambah', $project->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="mdi mdi-eye-outline"></i> Lihat Detail
                                        </a>
                                    </div>


                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
