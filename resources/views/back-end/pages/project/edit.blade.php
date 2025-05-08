@extends('back-end.layouts.main')

@section('content')
    <div class="main-panel" style="margin-top: 10px;">
        <div class="content-wrapper">
            <div class="col-lg-12 mx-auto grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center mb-4">Edit Project</h4>

                        <form action="{{ route('project.update', $project->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group mb-3">
                                <label for="nama_project">Nama Project</label>
                                <input type="text" name="nama_project" id="nama_project" class="form-control"
                                    value="{{ $project->nama_project }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control" required>{{ $project->deskripsi }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label">Pilih Kategori Project</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="">Pilih Kategori Project</option>
                                    @foreach ($categoryProject as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $project->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="job_type_dropdown" class="form-label">Pilih Pekerjaan</label>
                                <div id="selectedJobTypes" class="mb-2 d-flex flex-wrap gap-2">
                                    @foreach ($project->jobTypes as $jobType)
                                        <div class="job-type-item" id="job_type_wrapper_{{ $jobType->id }}">
                                            <span class="badge bg-primary d-flex align-items-center me-2 mb-2">
                                                {{ $jobType->name }}
                                                <button type="button" class="btn-close btn-close-white ms-2"
                                                    aria-label="Remove"
                                                    onclick="removeJobType({{ $jobType->id }})"></button>
                                            </span>
                                            <input type="hidden" name="job_types[]" value="{{ $jobType->id }}"
                                                id="job_type_input_{{ $jobType->id }}">
                                        </div>
                                    @endforeach
                                </div>

                                <select id="job_type_dropdown" class="form-select">
                                    <option value="">Pilih Pekerjaan</option>
                                    @foreach ($jobTypes as $jobType)
                                        @if (!$project->jobTypes->contains('id', $jobType->id))
                                            <option value="{{ $jobType->id }}">{{ $jobType->name }}</option>
                                        @endif
                                    @endforeach
                                </select>

                            </div>

                            <button type="submit" class="btn btn-primary">Update</button>
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
        const selectedContainer = document.getElementById('selectedJobTypes');

        dropdown.addEventListener('change', function() {
            const selectedId = this.value;
            const selectedText = this.options[this.selectedIndex].text;

            if (!selectedId || document.getElementById('job_type_input_' + selectedId)) {
                return;
            }

            const wrapper = document.createElement('div');
            wrapper.className = 'job-type-item';
            wrapper.id = 'job_type_wrapper_' + selectedId;
            wrapper.innerHTML = `
            <span class="badge bg-primary d-flex align-items-center me-2 mb-2">
                ${selectedText}
                <button type="button" class="btn-close btn-close-white ms-2" aria-label="Remove" onclick="removeJobType(${selectedId})"></button>
            </span>
            <input type="hidden" name="job_types[]" value="${selectedId}" id="job_type_input_${selectedId}">`;

            selectedContainer.appendChild(wrapper);

            const option = dropdown.querySelector(`option[value="${selectedId}"]`);
            if (option) {
                option.remove();
            }

            dropdown.value = '';
        });

        function removeJobType(id) {
            const wrapper = document.getElementById('job_type_wrapper_' + id);
            if (wrapper) {
                wrapper.remove();

                const option = document.createElement('option');
                option.value = id;
                option.textContent = wrapper.querySelector('span').textContent.trim();
                dropdown.appendChild(option);
            }
        }

        document.querySelectorAll('[id^="job_type_input_"]').forEach(function(input) {
            const selectedId = input.value;
            const selectedText = dropdown.querySelector(`option[value="${selectedId}"]`).text;

            const wrapper = document.createElement('div');
            wrapper.className = 'job-type-item';
            wrapper.id = 'job_type_wrapper_' + selectedId;
            wrapper.innerHTML = `
            <span class="badge bg-primary d-flex align-items-center me-2 mb-2">
                ${selectedText}
                <button type="button" class="btn-close btn-close-white ms-2" aria-label="Remove" onclick="removeJobType(${selectedId})"></button>
            </span>
            <input type="hidden" name="job_types[]" value="${selectedId}" id="job_type_input_${selectedId}">`;
            selectedContainer.appendChild(wrapper);

            const option = dropdown.querySelector(`option[value="${selectedId}"]`);
            if (option) {
                option.remove();
            }
        });
    </script>
@endsection
