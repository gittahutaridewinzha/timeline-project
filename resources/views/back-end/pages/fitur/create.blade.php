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

            <div class="row">
                {{-- Form Tambah Fitur --}}
                <div class="col-lg-12" style="margin-top: 15px;">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title text-center mb-4">Tambah Fitur</h4>
                            <form action="{{ route('fitur.store', ['project' => $project->id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="project_id" value="{{ $project->id }}">

                                <div class="form-group mb-3">
                                    <label>Project</label>
                                    <input type="text" class="form-control" value="{{ $project->nama_project }}"
                                        readonly>
                                </div>

                                <div class="form-group mb-3">
                                    <label>Nama Fitur</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>

                                <hr>
                                <h5>Detail Fitur</h5>
                                <div id="detailFiturContainer">
                                    <div class="form-group mb-3 detail-item position-relative">
                                        <input type="text" name="detail_fiturs[]" class="form-control"
                                            placeholder="Nama detail fitur" required>
                                        <button type="button"
                                            class="btn btn-sm btn-link text-danger position-absolute end-0 top-0 mt-2 me-2 remove-detail"
                                            title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="button" id="addDetailFitur" class="btn btn-outline-primary btn-sm mb-3">
                                    <i class="bi bi-plus-circle"></i> Tambah Detail
                                </button>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                    <a href="{{ route('project.index') }}" class="btn btn-secondary">Kembali</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Daftar Fitur --}}
                <div class="col-lg-12 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-3">Daftar Fitur</h4>
                            <div class="accordion" id="fiturAccordion">
                                @forelse ($project->fiturs as $fitur)
                                    <div class="accordion-item mb-2">
                                        <h2 class="accordion-header" id="heading{{ $fitur->id }}">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $fitur->id }}">
                                                {{ $fitur->name }}
                                            </button>
                                        </h2>
                                        <div id="collapse{{ $fitur->id }}" class="accordion-collapse collapse"
                                            data-bs-parent="#fiturAccordion">
                                            <div class="accordion-body">

                                                {{-- Form Update Fitur --}}
                                                <form action="{{ route('fitur.update', $fitur->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Fitur</label>
                                                        <input type="text" name="name" value="{{ $fitur->name }}"
                                                            class="form-control">
                                                    </div>

                                                    <div class="mb-3" id="detailContainer-{{ $fitur->id }}">
                                                        @foreach ($fitur->detailFiturs as $detail)
                                                            @php
                                                                $revisiList = $detail
                                                                    ->revisiProjects()
                                                                    ->latest()
                                                                    ->get();
                                                            @endphp

                                                            <div
                                                                class="form-group mb-3 detail-item d-flex align-items-center">
                                                                <input type="text" name="details[{{ $detail->id }}]"
                                                                    value="{{ $detail->name }}"
                                                                    class="form-control form-control-sm {{ $revisiList->isNotEmpty() ? 'bg-warning-subtle border-warning' : '' }}"
                                                                    placeholder="Detail fitur">

                                                                <div class="ms-2 d-flex align-items-center">
                                                                    <input type="checkbox" name="deleted_details[]"
                                                                        value="{{ $detail->id }}"
                                                                        class="form-check-input me-1">
                                                                    <label class="form-check-label mt-2"><i
                                                                            class="bi bi-trash"></i></label>
                                                                </div>

                                                                <button type="button"
                                                                    class="btn btn-sm btn-outline-warning ms-2"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#revisiModal{{ $detail->id }}">
                                                                    <i class="bi bi-chat-left-text"></i>
                                                                </button>
                                                            </div>

                                                            @if ($revisiList->isNotEmpty())
                                                                <div class="ms-4 mb-3">
                                                                    <h6 class="text-muted mb-1">Catatan Revisi:</h6>
                                                                    @foreach ($revisiList as $rev)
                                                                        <div
                                                                            class="alert alert-warning py-1 px-2 mb-1 small">
                                                                            <div class="d-flex justify-content-between">
                                                                                <div><i
                                                                                        class="bi bi-chat-left-text-fill me-1"></i>
                                                                                    {{ $rev->note }}</div>
                                                                                <small
                                                                                    class="text-muted ms-2">{{ $rev->created_at->format('d M Y') }}</small>
                                                                            </div>
                                                                            @if ($rev->projectJobType && $rev->projectJobType->jobtype)
                                                                                <div class="text-muted small mt-1">
                                                                                    <i class="bi bi-tools me-1"></i>
                                                                                    Job Type:
                                                                                    {{ $rev->projectJobType->jobtype->name }}
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>

                                                    <div class="form-group mb-2">
                                                        <input type="text" name="new_details[]"
                                                            class="form-control form-control-sm"
                                                            placeholder="Detail fitur baru">
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <input type="submit" name="add_detail" value="Tambah Detail"
                                                            class="btn btn-outline-primary btn-sm">
                                                    </div>

                                                    <div class="d-flex justify-content-end gap-2">
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="bi bi-save"></i> Update Detail Fitur
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#confirmDeleteModal{{ $fitur->id }}">
                                                            <i class="bi bi-trash"></i> Hapus Fitur
                                                        </button>
                                                    </div>
                                                </form>

                                                {{-- Modal Konfirmasi Hapus Fitur --}}
                                                <div class="modal fade" id="confirmDeleteModal{{ $fitur->id }}"
                                                    tabindex="-1"
                                                    aria-labelledby="confirmDeleteModalLabel{{ $fitur->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <form action="{{ route('fitur.destroy', $fitur->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')

                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="confirmDeleteModalLabel{{ $fitur->id }}">
                                                                        Konfirmasi Hapus Fitur
                                                                    </h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Yakin ingin menghapus fitur
                                                                    <strong>{{ $fitur->name }}</strong> beserta semua
                                                                    detailnya?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button"
                                                                        class="btn btn-secondary btn-sm"
                                                                        data-bs-dismiss="modal">Batal</button>
                                                                    <button type="submit"
                                                                        class="btn btn-danger btn-sm">Ya, Hapus</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    {{-- Modal Revisi (diletakkan di luar form utama) --}}
                                    @foreach ($fitur->detailFiturs as $detail)
                                        <div class="modal fade" id="revisiModal{{ $detail->id }}" tabindex="-1"
                                            aria-labelledby="revisiModalLabel{{ $detail->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form action="{{ route('fitur.revisi') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="detailfitur_id"
                                                        value="{{ $detail->id }}">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="revisiModalLabel{{ $detail->id }}">
                                                                Catatan Revisi - {{ $detail->name }}
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">

                                                            @if (session('error_' . $detail->id))
                                                                <div class="alert alert-danger">
                                                                    {{ session('error_' . $detail->id) }}
                                                                </div>
                                                            @endif
                                                            @if ($errors->any() && old('detailfitur_id') == $detail->id)
                                                                <div class="alert alert-danger">
                                                                    <ul class="mb-0">
                                                                        @foreach ($errors->all() as $error)
                                                                            <li>{{ $error }}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            @endif

                                                            <div class="mb-3">
                                                                <label class="form-label">Job Type</label>
                                                                <select name="project_job_type_id" class="form-select"
                                                                    required>
                                                                    <option value="">-- Pilih Job Type --</option>
                                                                    @foreach ($jobTypes as $jt)
                                                                        <option value="{{ $jt->id }}"
                                                                            {{ old('project_job_type_id') == $jt->id ? 'selected' : '' }}>
                                                                            {{ $jt->jobtype->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Catatan Revisi</label>
                                                                <textarea name="note" class="form-control" rows="4" placeholder="Tulis catatan revisi di sini..." required>{{ old('note') }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary btn-sm"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-warning btn-sm">Simpan
                                                                Catatan</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach

                                @empty
                                    <p class="text-muted">Belum ada fitur ditambahkan.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Script untuk buka modal revisi jika ada error validasi --}}
                @if ($errors->any() && old('detailfitur_id'))
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            var modalId = '{{ old('detailfitur_id') }}';
                            var modalElement = document.getElementById('revisiModal' + modalId);
                            if (modalElement) {
                                var modal = new bootstrap.Modal(modalElement);
                                modal.show();
                            }
                        });
                    </script>
                @endif

            </div>
        </div>
    </div>

    {{-- Script --}}
    <script>
        // Tambah dan hapus detail fitur di form tambah fitur
        const container = document.getElementById('detailFiturContainer');
        const addBtn = document.getElementById('addDetailFitur');

        addBtn.addEventListener('click', () => {
            const div = document.createElement('div');
            div.className = 'form-group mb-3 detail-item position-relative';
            div.innerHTML = `
                <input type="text" name="detail_fiturs[]" class="form-control" placeholder="Nama detail fitur" required>
                <button type="button" class="btn btn-sm btn-link text-danger position-absolute end-0 top-0 mt-2 me-2 remove-detail" title="Hapus">
                    <i class="bi bi-trash"></i>
                </button>
            `;
            container.appendChild(div);
        });

        container.addEventListener('click', function(e) {
            if (e.target.closest('.remove-detail')) {
                e.target.closest('.detail-item').remove();
            }
        });

        // Tambah dan hapus detail di daftar fitur (accordion)
        document.querySelectorAll('.addDetailBtn').forEach(button => {
            button.addEventListener('click', function() {
                const fiturId = this.dataset.fiturId;
                const targetContainer = document.getElementById(`detailContainer-${fiturId}`);
                const div = document.createElement('div');
                div.className = 'form-group mb-3 detail-item position-relative';
                div.innerHTML = `
                    <input type="text" name="details_baru[]" class="form-control form-control-sm mb-2" placeholder="Detail fitur baru" required>
                    <button type="button" class="btn btn-sm btn-link text-danger position-absolute end-0 top-0 mt-2 me-2 remove-detail" title="Hapus">
                        <i class="bi bi-trash"></i>
                    </button>
                `;
                targetContainer.appendChild(div);
            });
        });

        // Hapus detail (global handler)
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-detail')) {
                e.target.closest('.detail-item').remove();
            }
        });
    </script>



@endsection
