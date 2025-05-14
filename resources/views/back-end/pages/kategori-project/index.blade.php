@extends('back-end.layouts.main')

@section('content')
<style>
    #selectedJobTypes .badge {
        padding: 0.5em 0.75em;
        font-size: 0.9em;
        border-radius: 1em;
    }

    #selectedJobTypes .btn-close {
        font-size: 0.65em;
        margin-left: 0.5em;
        line-height: 1;
    }
</style>
    <div class="main-panel" style="margin-top: 10px;">

        <div class="content-wrapper">
            <div class="mt-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <h2 class="text-center mb-4">Daftar Category Project</h2>

                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahPekerjaanModal">
                    <i class="bi bi-plus"></i> Tambah Category
                </button>

                <div class="modal fade" id="tambahPekerjaanModal" tabindex="-1" aria-labelledby="tambahPekerjaanModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('category-project.store') }}" method="POST" class="modal-content">
                            @csrf

                            <div class="modal-header">
                                <h5 class="modal-title" id="tambahPekerjaanModalLabel">Tambah Category</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Category</label>
                                    <input type="text" class="form-control" id="name" name="name" required placeholder="Masukkan Nama Category">
                                </div>

                                <div class="mb-3">
                                    <label for="job_type_dropdown" class="form-label">Pilih Pekerjaan</label>
                                    <select id="job_type_dropdown" name="job_type_id" class="form-select w-100"> {{-- GANTI class ke form-control --}}
                                        <option value="">Pilih Pekerjaan</option>
                                        @foreach ($jobTypes as $jobType)
                                            <option value="{{ $jobType->id }}">{{ $jobType->name }}</option>
                                        @endforeach
                                    </select>
                                    <div id="hiddenInputs"></div>
                                </div>
                            </div>


                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Nama</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kategori as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-1">
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $item->id }}" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </button>

                                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $item->id }}" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Modal Edit -->
                                        <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1"
                                            aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form action="{{ route('category-project.update', $item->id) }}" method="POST" class="modal-content">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editModalLabel{{ $item->id }}">Edit Category</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="name{{ $item->id }}" class="form-label">Nama Category</label>
                                                            <input type="text" class="form-control" id="name{{ $item->id }}" name="name" value="{{ $item->name }}" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="job_type_dropdown_edit_{{ $item->id }}" class="form-label">Pilih Pekerjaan</label>
                                                            <select id="job_type_dropdown_edit_{{ $item->id }}" class="form-select job-type-dropdown">
                                                                <option value="">Pilih Pekerjaan</option>
                                                                @foreach ($jobTypes as $jobType)
                                                                    <option value="{{ $jobType->id }}">{{ $jobType->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div id="selectedJobTypes_edit_{{ $item->id }}" class="mt-2 d-flex flex-wrap">
                                                                @foreach ($item->jobTypes as $jt)
                                                                    <span class="badge bg-primary me-2 d-inline-flex align-items-center mb-2">
                                                                        {{ $jt->name }}
                                                                        <button type="button" class="btn-close btn-close-white btn-sm ms-2" aria-label="Close" onclick="removeSelectedJobType('{{ $item->id }}', '{{ $jt->id }}')"></button>
                                                                        <input type="hidden" name="job_types[]" value="{{ $jt->id }}">
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                            <div id="hiddenInputs_edit_{{ $item->id }}"></div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Modal konfirmasi hapus -->
                                        <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1"
                                            aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah Anda yakin ingin menghapus Kategori Project ini?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form action="{{ route('category-project.destroy', $item->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </form>
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Modal -->
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        const dropdown = document.getElementById('job_type_dropdown');
        const selectedJobTypes = document.createElement('div');
        selectedJobTypes.id = 'selectedJobTypes';
        dropdown.parentNode.insertBefore(selectedJobTypes, dropdown);

        const hiddenInputs = document.getElementById('hiddenInputs');
        const selectedIds = new Set();

        function updateDropdownOptions() {
            const options = dropdown.querySelectorAll('option');
            options.forEach(option => {
                if (selectedIds.has(option.value)) {
                    option.style.display = 'none';
                } else {
                    option.style.display = 'block';
                }
            });
        }

        dropdown.addEventListener('change', function () {
            const selectedId = this.value;
            const selectedText = this.options[this.selectedIndex].text;

            if (selectedId && !selectedIds.has(selectedId)) {
                selectedIds.add(selectedId);

                const badge = document.createElement('span');
                badge.className = 'badge bg-primary me-2 d-inline-flex align-items-center mb-2';
                badge.innerHTML = `
                    ${selectedText}
                    <button type="button" class="btn-close btn-close-white btn-sm ms-2" aria-label="Close"></button>
                `;

                badge.querySelector('button').addEventListener('click', function () {
                    selectedJobTypes.removeChild(badge);
                    hiddenInputs.querySelector(`input[value="${selectedId}"]`).remove();
                    selectedIds.delete(selectedId);
                    updateDropdownOptions();
                });

                selectedJobTypes.appendChild(badge);

                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'job_types[]';
                hiddenInput.value = selectedId;
                hiddenInputs.appendChild(hiddenInput);

                updateDropdownOptions();
            }

            this.value = '';
        });

        updateDropdownOptions();
    </script>
<script>
    document.querySelectorAll('.job-type-dropdown').forEach(function (dropdown) {
    const modalId = dropdown.id.replace('job_type_dropdown_edit_', '');
    const selectedJobTypes = document.getElementById('selectedJobTypes_edit_' + modalId);
    const hiddenInputs = document.getElementById('hiddenInputs_edit_' + modalId);

    const selectedIds = new Set(
        Array.from(selectedJobTypes.querySelectorAll('input')).map(input => input.value)
    );

    function updateDropdownOptions() {
        dropdown.querySelectorAll('option').forEach(option => {
            if (selectedIds.has(option.value)) {
                option.style.display = 'none';
            } else {
                option.style.display = 'block';
            }
        });
    }

    dropdown.addEventListener('change', function () {
        const selectedId = this.value;
        const selectedText = this.options[this.selectedIndex].text;

        if (selectedId && !selectedIds.has(selectedId)) {
            selectedIds.add(selectedId);

            const badge = document.createElement('span');
            badge.className = 'badge bg-primary me-2 d-inline-flex align-items-center mb-2';
            badge.innerHTML = `
                ${selectedText}
                <button type="button" class="btn-close btn-close-white btn-sm ms-2" aria-label="Close"></button>
                <input type="hidden" name="job_types[]" value="${selectedId}">
            `;

            badge.querySelector('button').addEventListener('click', function () {
                selectedJobTypes.removeChild(badge);
                selectedIds.delete(selectedId);
                updateDropdownOptions();
            });

            selectedJobTypes.appendChild(badge);
            updateDropdownOptions();
        }

        this.value = '';
    });

    updateDropdownOptions();
});

function removeSelectedJobType(modalId, id) {
    const container = document.getElementById('selectedJobTypes_edit_' + modalId);
    const badges = container.querySelectorAll('span');
    badges.forEach(badge => {
        const input = badge.querySelector('input');
        if (input && input.value == id) {
            badge.remove();
        }
    });
}

</script>

@endsection
