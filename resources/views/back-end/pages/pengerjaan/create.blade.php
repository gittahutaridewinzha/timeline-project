@extends('back-end.layouts.main')

@section('content')
    <div class="main-panel" style="margin-top: 45px;">
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
                            Input Progress Pengerjaan Project: <span class="text-primary">{{ $project->nama_project ?? '-' }}</span>
                        </h4>

                        @php
                            $jobTypes = $project->taskDistributions
                                ->where('user_id', Auth::id())
                                ->pluck('jobType.name')
                                ->unique();
                        @endphp

                        <div class="d-flex flex-wrap align-items-center mb-3 gap-3">
                            <div class="d-flex align-items-center">
                                <i class="mdi mdi-tag-multiple text-success me-1" style="font-size: 1.2rem;"></i>
                                <span class="badge bg-success text-white">
                                    {{ $project->CategoryProject->name ?? 'Tidak ada kategori' }}
                                </span>
                            </div>

                            <div class="d-flex align-items-center">
                                <i class="mdi mdi-briefcase-account me-1" style="font-size: 1.2rem;"></i>
                                @if ($jobTypes->isNotEmpty())
                                    <span class="badge bg-info text-white">
                                        {{ $jobTypes->implode(', ') }}
                                    </span>
                                @else
                                    <span class="text-muted fst-italic">Job Type Anda: Tidak ada</span>
                                @endif
                            </div>
                        </div>

                        <!-- FORM DAN TABEL TETAP SAMA -->
                        <form action="{{ route('pengerjaan.store', $project->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="project_id" value="{{ $project->id }}">

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Fitur</th>
                                            <th>Detail Fitur</th>
                                            <th>Presentase (%)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; @endphp
                                        @foreach ($detailFiturs as $fitur)
                                            @php
                                                $hasRevisi =
                                                    isset($catatanRevisi[$fitur->id]) &&
                                                    $catatanRevisi[$fitur->id]->count() > 0;
                                            @endphp

                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $fitur->fitur->name ?? '-' }}</td>
                                                <td>
                                                    <div>{{ $fitur->name }}</div>

                                                    @if ($hasRevisi)
                                                        <div class="mt-2">

                                                            @foreach ($catatanRevisi[$fitur->id] as $revisi)
                                                                <div class="alert alert-warning py-1 px-2 mb-1 small">
                                                                    <div class="d-flex justify-content-between">
                                                                        <div>
                                                                            <i class="bi bi-chat-left-text-fill me-1"></i>
                                                                            {{ $revisi->note }}
                                                                        </div>
                                                                        <small
                                                                            class="text-muted ms-2">{{ $revisi->created_at->format('d M Y') }}</small>
                                                                    </div>

                                                                    @if ($revisi->projectJobType && $revisi->projectJobType->jobtype)
                                                                        <div class="text-muted small mt-1">
                                                                            <i class="bi bi-tools me-1"></i> Job Type:
                                                                            {{ $revisi->projectJobType->jobtype->name }}
                                                                        </div>
                                                                    @endif

                                                                    @if ($revisi->gambar)
                                                                        @php
                                                                            $ext = strtolower(
                                                                                pathinfo(
                                                                                    $revisi->gambar,
                                                                                    PATHINFO_EXTENSION,
                                                                                ),
                                                                            );
                                                                            $isImage = in_array($ext, [
                                                                                'jpg',
                                                                                'jpeg',
                                                                                'png',
                                                                            ]);
                                                                        @endphp

                                                                        <div class="mt-1">
                                                                            @if ($isImage)
                                                                                <a href="#"
                                                                                    class="text-decoration-none"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#gambarModal{{ $revisi->id }}">
                                                                                    <i class="bi bi-image-fill me-1"></i>
                                                                                    Lihat Gambar
                                                                                </a>

                                                                                <!-- Modal Gambar -->
                                                                                <div class="modal fade"
                                                                                    id="gambarModal{{ $revisi->id }}"
                                                                                    tabindex="-1"
                                                                                    aria-labelledby="gambarModalLabel{{ $revisi->id }}"
                                                                                    aria-hidden="true">
                                                                                    <div
                                                                                        class="modal-dialog modal-dialog-centered">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title"
                                                                                                    id="gambarModalLabel{{ $revisi->id }}">
                                                                                                    Gambar Revisi</h5>
                                                                                                <button type="button"
                                                                                                    class="btn-close"
                                                                                                    data-bs-dismiss="modal"
                                                                                                    aria-label="Tutup"></button>
                                                                                            </div>
                                                                                            <div
                                                                                                class="modal-body text-center">
                                                                                                <img src="{{ asset('images/revisi/' . $revisi->gambar) }}"
                                                                                                    alt="Gambar Revisi"
                                                                                                    style="max-width: 800px; width: 100%; height: auto;"
                                                                                                    class="rounded shadow">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @else
                                                                                <a href="{{ asset('images/revisi/' . $revisi->gambar) }}"
                                                                                    target="_blank"
                                                                                    class="text-decoration-none text-primary">
                                                                                    <i
                                                                                        class="bi bi-file-earmark-text me-1"></i>
                                                                                    Lihat Dokumen
                                                                                </a>
                                                                            @endif
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endforeach

                                                        </div>
                                                    @else
                                                        <div class="mt-2">
                                                            <small class="text-muted">Tidak ada catatan revisi.</small>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <input type="hidden" name="detail_fiturs_id[]"
                                                        value="{{ $fitur->id }}">
                                                    <input type="number" name="pengerjaan[]"
                                                        class="form-control {{ $hasRevisi ? 'bg-warning-subtle border-warning' : '' }}"
                                                        placeholder="%" min="0" max="100" required
                                                        value="{{ old('pengerjaan.' . $loop->index, $progress[$fitur->id]->pengerjaan ?? '') }}">
                                                </td>
                                            </tr>
                                        @endforeach


                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Total</strong></td>
                                            <td>
                                                <input type="text" id="totalPersentase" class="form-control" readonly>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">
                                Simpan Pengerjaan
                            </button>
                            <a href="{{ route('pengerjaan.index') }}" class="btn btn-secondary mt-3">
                                Kembali
                            </a>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input[name="pengerjaan[]"]');
            const totalField = document.getElementById('totalPersentase');

            function updateTotal() {
                let total = 0;
                let count = 0;

                inputs.forEach(input => {
                    const value = parseFloat(input.value);
                    if (!isNaN(value)) {
                        total += value;
                        count++;
                    }
                });

                if (count > 0) {
                    total = total / count;
                }

                totalField.value = total.toFixed(2) + ' %';
            }

            updateTotal();

            inputs.forEach(input => {
                input.addEventListener('input', updateTotal);
            });
        });
    </script>
@endsection
