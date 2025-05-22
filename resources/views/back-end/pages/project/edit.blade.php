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
                                <label for="id_project_type">Pilih Tipe Project</label>
                                <select name="id_project_type" id="id_project_type" class="form-select"
                                    style="color: black;" required>
                                    <option value="">Pilih Tipe Project</option>
                                    @foreach ($projectType as $type)
                                        <option value="{{ $type->id }}"
                                            {{ $project->id_project_type == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="nama_project">Nama Project</label>
                                <input type="text" name="nama_project" id="nama_project" class="form-control"
                                    value="{{ $project->nama_project }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control" required>{{ $project->deskripsi }}</textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label for="deadline">Deadline</label>
                                <input type="date" name="deadline" id="deadline" class="form-control"
                                    value="{{ old('deadline', $project->deadline) }}">
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label">Pilih Kategori Project</label>
                                <select class="form-select" style="color: black;" id="category_id" name="category_id"
                                    required>
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
                                <select id="job_type_dropdown" class="form-select" style="color: black;">
                                    <option value="">Pilih Pekerjaan</option>
                                    @foreach ($jobTypes as $jobType)
                                        @if (!$project->jobTypes->contains('id', $jobType->id))
                                            <option value="{{ $jobType->id }}">{{ $jobType->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="value_project_display">Value Project</label>
                                <div class="input-group">
                                    <input type="text" id="value_project_display" class="form-control" placeholder="0,00"
                                        value="{{ $project->valueProject ? number_format($project->valueProject->value_project, 0, ',', '.') : '' }}"
                                        autocomplete="off" inputmode="numeric" readonly>
                                </div>
                                <input type="hidden" name="value_project" id="value_project"
                                    value="{{ $project->valueProject->value_project ?? '' }}">
                            </div>


                            <div class="form-group mb-3">
                                <label for="payment_category">Kategori Pembayaran</label>
                                <select name="payment_category" id="payment_category" class="form-select"
                                    style="color: black"
                                    {{ $project->valueProject->payment_category === 'full_payment' ? 'disabled' : '' }}>
                                    <option value="full_payment"
                                        {{ $project->valueProject->payment_category === 'full_payment' ? 'selected' : '' }}>
                                        Full Payment
                                    </option>
                                    <option value="dp"
                                        {{ $project->valueProject->payment_category === 'dp' ? 'selected' : '' }}>
                                        DP
                                    </option>
                                    <option value="pelunasan"
                                        {{ $project->valueProject->payment_category === 'pelunasan' ? 'selected' : '' }}>
                                        Pelunasan
                                    </option>
                                </select>
                            </div>

                            @php
                                $value = $project->valueProject->value_project ?? 0;
                                $amount = $project->valueProject->amount ?? 0;
                                $sisaPembayaran = $value - $amount;
                            @endphp

                            <div class="form-group mb-3" id="dp_info" style="display: none;">
                                <label for="dp_remaining">Sisa Pembayaran</label>
                                <input type="text" class="form-control" id="dp_remaining" readonly>
                            </div>

                            <input type="hidden" name="amount" id="amount" value="{{ $amount }}">

                            <div class="mb-3">
                                <label for="project_manager" class="form-label">Project Manager</label>
                                <select name="id_project_manager" id="project_manager" class="form-select"
                                    style="color: black" required>
                                    <option value="">Pilih Project Manager</option>
                                    @foreach ($projectManagers as $pm)
                                        <option value="{{ $pm->id }}"
                                            {{ (string) $pm->id === (string) $project->id_project_manager ? 'selected' : '' }}>
                                            {{ $pm->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-select" style="color: black"
                                    {{ $project->status === 'completed' ? 'disabled' : '' }}>
                                    <option value="on progress"
                                        {{ $project->status === 'on progress' ? 'selected' : '' }}>
                                        On Progress</option>
                                    <option value="completed" {{ $project->status === 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                </select>

                                @if ($project->status === 'completed')
                                    <input type="hidden" name="status" value="completed">
                                @endif
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
        const categoryDropdown = document.getElementById('category_id');
        const jobDropdown = document.getElementById('job_type_dropdown');
        const selectedJobTypesDiv = document.getElementById('selectedJobTypes');
        const projectId = {{ $project->id }};
        let currentJobTypes = [];

        // Saat halaman selesai dimuat, trigger event kategori
        document.addEventListener('DOMContentLoaded', () => {
            categoryDropdown.dispatchEvent(new Event('change'));
        });

        categoryDropdown.addEventListener('change', function() {
            const selectedCategoryId = this.value;

            // Kosongkan dropdown & simpan ulang currentJobTypes
            jobDropdown.innerHTML = '<option value="">Pilih Pekerjaan</option>';
            currentJobTypes = [];

            if (selectedCategoryId) {
                fetch(`/get-job-types-by-category-edit/${selectedCategoryId}/${projectId}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data); // Debug
                        if (data.jobTypes && data.jobTypes.length > 0) {
                            currentJobTypes = data.jobTypes;

                            // Tampilkan ulang pekerjaan yang belum dipilih
                            currentJobTypes.forEach(jobType => {
                                if (jobType.selected) {
                                    addJobTypeToSelected(jobType.id, jobType.name);
                                } else {
                                    appendJobOption(jobType.id, jobType.name);
                                }
                            });
                        } else {
                            const option = document.createElement('option');
                            option.disabled = true;
                            option.textContent = 'Tidak ada pekerjaan untuk kategori ini';
                            jobDropdown.appendChild(option);
                        }
                    })
                    .catch(error => {
                        console.error('Gagal mengambil data pekerjaan:', error);
                    });
            }
        });

        jobDropdown.addEventListener('change', function() {
            const selectedJobId = this.value;
            const selectedJobName = this.options[this.selectedIndex].text;

            if (selectedJobId) {
                addJobTypeToSelected(selectedJobId, selectedJobName);
                removeOptionByValue(selectedJobId);
                this.selectedIndex = 0;
            }
        });

        function addJobTypeToSelected(id, name) {
            if (!document.getElementById('job_type_input_' + id)) {
                const wrapper = document.createElement('div');
                wrapper.className = 'job-type-item';
                wrapper.id = 'job_type_wrapper_' + id;

                wrapper.innerHTML = `
                    <span class="badge bg-primary d-flex align-items-center me-2 mb-2">
                        ${name}
                        <button type="button" class="btn-close btn-close-white ms-2" aria-label="Remove" onclick="removeJobType(${id})"></button>
                    </span>
                    <input type="hidden" name="job_types[]" value="${id}" id="job_type_input_${id}">
                `;

                selectedJobTypesDiv.appendChild(wrapper);
            }
        }

        function removeJobType(id) {
            const wrapper = document.getElementById('job_type_wrapper_' + id);
            if (wrapper) {
                const name = wrapper.querySelector('span').childNodes[0].nodeValue.trim();
                appendJobOption(id, name);
                wrapper.remove();
            }
        }

        function appendJobOption(id, name) {
            // Hindari duplikasi saat menambahkan kembali ke dropdown
            if (!Array.from(jobDropdown.options).some(opt => opt.value === id.toString())) {
                const option = document.createElement('option');
                option.value = id;
                option.textContent = name;
                jobDropdown.appendChild(option);
            }
        }

        function removeOptionByValue(value) {
            const option = Array.from(jobDropdown.options).find(opt => opt.value === value);
            if (option) option.remove();
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentSelect = document.getElementById('payment_category');
            const valueDisplay = document.getElementById('value_project_display');
            const valueHidden = document.getElementById('value_project');
            const dpInfo = document.getElementById('dp_info');
            const dpRemaining = document.getElementById('dp_remaining');
            const amountField = document.getElementById('amount');

            const originalAmount = parseInt("{{ $amount }}") || 0;

            function formatRupiah(value) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(value);
            }

            function parseRupiah(str) {
                return parseInt(str.replace(/[^0-9]/g, '')) || 0;
            }

            function updateAmountLogic() {
                const value = parseRupiah(valueDisplay?.value || "0");
                valueHidden.value = value;

                const category = paymentSelect.value;

                if (category === 'full_payment') {
                    amountField.value = value;
                    dpInfo.style.display = 'none';
                } else if (category === 'pelunasan') {
                    const remaining = value - originalAmount;
                    dpRemaining.value = formatRupiah(remaining > 0 ? remaining : 0);
                    amountField.value = originalAmount;
                    dpInfo.style.display = 'block';
                } else {
                    dpInfo.style.display = 'none';
                    amountField.value = originalAmount;
                }
            }

            if (valueDisplay) {
                valueDisplay.addEventListener('input', () => {
                    const raw = valueDisplay.value;
                    const cleaned = raw.replace(/[^0-9]/g, '');
                    valueDisplay.value = formatRupiah(cleaned);
                    updateAmountLogic();
                });
            }

            paymentSelect.addEventListener('change', updateAmountLogic);
            updateAmountLogic();
        });
    </script>
@endsection
