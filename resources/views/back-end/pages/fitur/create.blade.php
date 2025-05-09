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
                                                {{-- Form Update --}}
                                                <form action="{{ route('fitur.update', $fitur->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Fitur</label>
                                                        <input type="text" name="name" value="{{ $fitur->name }}" class="form-control">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Detail Fitur</label>
                                                        <div id="detailContainer-{{ $fitur->id }}">
                                                            @foreach ($fitur->detailFiturs as $detail)
                                                                <div class="form-group mb-2 detail-item">
                                                                    <div class="d-flex align-items-center">
                                                                        <input type="text"
                                                                            name="details[{{ $detail->id }}]"
                                                                            value="{{ $detail->name }}"
                                                                            class="form-control form-control-sm"
                                                                            placeholder="Detail fitur">

                                                                        <!-- Checkbox dan ikon di kanan -->
                                                                        <div class="ms-2 d-flex align-items-center">
                                                                            <input type="checkbox" name="deleted_details[]"
                                                                                value="{{ $detail->id }}"
                                                                                class="form-check-input me-1">
                                                                            <label class="form-check-label mt-2">
                                                                                <i class="bi bi-trash"></i>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>

                                                        @if (!isset($fitur->new_details))
                                                            <div class="form-group mb-2">
                                                                <input type="text" name="new_details[]"
                                                                    class="form-control form-control-sm"
                                                                    placeholder="Detail fitur baru">
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <input type="submit" name="add_detail" value="Tambah Detail"
                                                            class="btn btn-outline-primary btn-sm">
                                                    </div>

                                                    <div class="d-flex justify-content-end gap-2">
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="bi bi-save"></i> Update
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#confirmDeleteModal{{ $fitur->id }}">
                                                            <i class="bi bi-trash"></i> Hapus
                                                        </button>
                                                    </div>
                                                </form>




                                                <!-- Modal Konfirmasi -->
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
                                                                    <h5 class="modal-title">Konfirmasi Hapus Fitur</h5>
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


                                                <!-- Modal Konfirmasi Hapus -->
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
                                @empty
                                    <p class="text-muted">Belum ada fitur ditambahkan.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
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
