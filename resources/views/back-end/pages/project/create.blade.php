@extends('back-end.layouts.main')

@section('content')
    <div class="main-panel" style="margin-top: 10px;">
        <div class="content-wrapper">
            <div class="col-lg-12 mx-auto grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center mb-4">Tambah Project</h4>

                        <form action="{{ route('project.store') }}" method="POST">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="nama_project">Nama Project</label>
                                <input type="text" name="nama_project" id="nama_project" class="form-control" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label">Pilih Kategori Project</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="">Pilih Kategori Project</option>
                                    @foreach ($categoryProject as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="job_type_dropdown" class="form-label">Pilih Pekerjaan</label>
                                <div id="selectedJobTypes" class="mb-2 d-flex flex-wrap gap-2"></div>
                                <select id="job_type_dropdown" class="form-select">
                                    <option value="">Pilih Pekerjaan</option>
                                    @foreach ($jobTypes as $jobType)
                                        <option value="{{ $jobType->id }}">{{ $jobType->name }}</option>
                                    @endforeach
                                </select>

                                <div id="hiddenInputs"></div>
                            </div>

                            <input type="hidden" name="id_project_manager" value="{{ Auth::user()->id }}">

                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('project.index') }}" class="btn btn-secondary">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/4.20.2/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('deskripsi');

        const dropdown = document.getElementById('job_type_dropdown');
        const selectedJobTypes = document.getElementById('selectedJobTypes');
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

        dropdown.addEventListener('change', function() {
            const selectedId = this.value;
            const selectedText = this.options[this.selectedIndex].text;

            if (selectedId && !selectedIds.has(selectedId)) {
                selectedIds.add(selectedId);

                const badge = document.createElement('span');
                badge.className = 'badge bg-primary d-inline-flex align-items-center';
                badge.innerHTML = `
                    ${selectedText}
                    <button type="button" class="btn-close btn-close-white btn-sm ms-2" aria-label="Close"></button>
                `;

                badge.querySelector('button').addEventListener('click', function() {
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
@endsection
