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
                                <label for="id_project_type">Tipe Project</label>
                                <select name="id_project_type" id="id_project_type" class="form-select"
                                    style="color: black">
                                    <option value="">Pilih Tipe Project</option>
                                    @foreach ($projectType as $type)
                                        <option value="{{ $type->id }}"
                                            {{ old('id_project_type', $project->id_project_type ?? '') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="nama_project">Nama Project</label>
                                <input type="text" name="nama_project" id="nama_project" class="form-control" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control" required></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label for="deadline">Deadline</label>
                                <input type="date" name="deadline" id="deadline" class="form-control">
                            </div>

                            <input type="hidden" name="status" value="on progress">

                            <div class="mb-3">
                                <label for="category_id" class="form-label">Pilih Kategori Project</label>
                                <select class="form-select" style="color: black;" id="category_id" name="category_id"
                                    required>
                                    <option value="">Pilih Kategori Project</option>
                                    @foreach ($categoryProject as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="job_type_dropdown" class="form-label">Pilih Pekerjaan</label>
                                <div id="selectedJobTypes" class="mb-2 d-flex flex-wrap gap-2"></div>
                                <select id="job_type_dropdown" class="form-select" style="color: black;">
                                    <option value="">Pilih Pekerjaan</option>
                                </select>
                                <div id="hiddenInputs"></div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="id_project_manager">Pilih Project Manager</label>
                                <select name="id_project_manager" id="id_project_manager" class="form-select"
                                    style="color: black;" required>
                                    <option value="">Pilih Project Manager</option>
                                    @foreach ($projectManagers as $manager)
                                        <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="value_project_display">Value Project</label>
                                <input type="text" id="value_project_display" class="form-control" style="color: black"
                                    placeholder="Rp 0,00" autocomplete="off" inputmode="numeric">
                                <input type="hidden" name="value_project" id="value_project">

                            </div>

                            <div class="form-group mb-3">
                                <label for="payment_category">Kategori Pembayaran</label>
                                <select name="payment_category" id="payment_category" class="form-select"
                                    style="color: black" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="full_payment"
                                        {{ old('payment_category') == 'full_payment' ? 'selected' : '' }}>Full Payment
                                    </option>
                                    <option value="dp" {{ old('payment_category') == 'dp' ? 'selected' : '' }}>DP (30%
                                        atau lebih)</option>
                                </select>
                            </div>

                            <div class="form-group mb-3" id="dp_amount_group" style="display: none;">
                                <label for="dp_amount_display">Jumlah DP yang Dibayarkan</label>
                                <input type="text" id="dp_amount_display" class="form-control" placeholder="Rp 0,00"
                                    autocomplete="off" inputmode="numeric" style="color: black">
                                <input type="hidden" name="dp_amount" id="dp_amount">
                            </div>

                            <input type="hidden" name="amount" id="amount" value="">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('project.index') }}" class="btn btn-secondary">Kembali</a>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/4.20.2/standard/ckeditor.js"></script>

    {{-- <script>
        const displayInput = document.getElementById('value_project_display');
        const hiddenInput = document.getElementById('value_project');

        function formatRupiah(value) {
            const cleanValue = value.replace(/[^0-9]/g, '');
            const number = parseInt(cleanValue, 10);
            if (isNaN(number)) return '';

            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(number);
        }

        displayInput.addEventListener('input', function(e) {
            const cursorPosition = e.target.selectionStart;
            const oldLength = e.target.value.length;

            // Format ulang ke Rupiah
            const formatted = formatRupiah(e.target.value);
            e.target.value = formatted;

            // Update hidden input (tanpa format)
            const numericValue = formatted.replace(/[^0-9]/g, '');
            hiddenInput.value = numericValue;

            // Coba pertahankan posisi kursor
            const newLength = formatted.length;
            const adjustment = newLength - oldLength;
            e.target.setSelectionRange(cursorPosition + adjustment, cursorPosition + adjustment);
        });
    </script> --}}

    <script>
        function formatRupiah(value) {
            const cleanValue = value.replace(/[^0-9]/g, '');
            const number = parseInt(cleanValue, 10);
            if (isNaN(number)) return '';
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(number);
        }

        function attachRupiahFormatter(displayId, hiddenId) {
            const displayInput = document.getElementById(displayId);
            const hiddenInput = document.getElementById(hiddenId);

            displayInput.addEventListener('input', function(e) {
                const cursorPosition = e.target.selectionStart;
                const oldLength = e.target.value.length;

                const formatted = formatRupiah(e.target.value);
                e.target.value = formatted;

                const numericValue = formatted.replace(/[^0-9]/g, '');
                hiddenInput.value = numericValue;

                const newLength = formatted.length;
                const adjustment = newLength - oldLength;
                e.target.setSelectionRange(cursorPosition + adjustment, cursorPosition + adjustment);
            });
        }

        // Panggil fungsi untuk masing-masing input
        document.addEventListener('DOMContentLoaded', function() {
            attachRupiahFormatter('value_project_display', 'value_project');
            attachRupiahFormatter('dp_amount_display', 'dp_amount');
        });
    </script>

    <script>
        const dropdown = document.getElementById('job_type_dropdown');
        const categoryDropdown = document.getElementById('category_id');
        const selectedJobTypesDiv = document.getElementById('selectedJobTypes');
        const hiddenInputsDiv = document.getElementById('hiddenInputs');

        let selectedJobTypes = [];

        categoryDropdown.addEventListener('change', function() {
            const selectedCategoryId = this.value;
            dropdown.innerHTML = '<option value="">Pilih Pekerjaan</option>';
            selectedJobTypes = [];
            selectedJobTypesDiv.innerHTML = '';
            hiddenInputsDiv.innerHTML = '';

            if (selectedCategoryId) {
                fetch(`/get-job-types-by-category/${selectedCategoryId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.jobTypes && data.jobTypes.length > 0) {
                            data.jobTypes.forEach(jobType => {
                                const option = document.createElement('option');
                                option.value = jobType.id;
                                option.textContent = jobType.name;
                                dropdown.appendChild(option);
                            });
                        } else {
                            const option = document.createElement('option');
                            option.disabled = true;
                            option.textContent = 'Tidak ada pekerjaan untuk kategori ini';
                            dropdown.appendChild(option);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching job types:', error);
                    });
            }
        });

        dropdown.addEventListener('change', function() {
            const selectedId = this.value;
            const selectedText = this.options[this.selectedIndex].text;

            // Cegah duplikasi
            if (selectedId && !selectedJobTypes.includes(selectedId)) {
                selectedJobTypes.push(selectedId);

                // Tambahkan tag visual
                const badge = document.createElement('span');
                badge.className = 'badge bg-primary text-white p-2 rounded-pill';
                badge.textContent = selectedText;
                badge.style.marginRight = '5px';

                // Tombol hapus
                const removeBtn = document.createElement('span');
                removeBtn.textContent = ' ×';
                removeBtn.style.cursor = 'pointer';
                removeBtn.style.marginLeft = '5px';
                removeBtn.addEventListener('click', () => {
                    selectedJobTypes = selectedJobTypes.filter(id => id !== selectedId);
                    badge.remove();
                    document.getElementById(`job-type-${selectedId}`).remove();

                    // Tambahkan kembali ke dropdown
                    const option = document.createElement('option');
                    option.value = selectedId;
                    option.textContent = selectedText;
                    dropdown.appendChild(option);
                });

                badge.appendChild(removeBtn);
                selectedJobTypesDiv.appendChild(badge);

                // Tambahkan input tersembunyi
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'job_type_ids[]';
                hiddenInput.value = selectedId;
                hiddenInput.id = `job-type-${selectedId}`;
                hiddenInputsDiv.appendChild(hiddenInput);

                // Hapus dari dropdown
                this.remove(this.selectedIndex);
            }

            // Reset dropdown
            this.selectedIndex = 0;
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const category = document.getElementById('payment_category');
            const dpGroup = document.getElementById('dp_amount_group');
            const dpInput = document.getElementById('dp_amount');
            const amountInput = document.getElementById('amount');
            const valueProject = parseFloat(document.getElementById('value_project').value);

            function updateForm() {
                const selected = category.value;
                if (selected === 'full_payment') {
                    dpGroup.style.display = 'none';
                    dpInput.value = '';
                    amountInput.value = valueProject;
                } else if (selected === 'dp') {
                    dpGroup.style.display = 'block';
                    amountInput.value = dpInput.value || 0;
                } else {
                    dpGroup.style.display = 'none';
                    dpInput.value = '';
                    amountInput.value = '';
                }
            }

            category.addEventListener('change', updateForm);
            dpInput.addEventListener('input', function() {
                amountInput.value = this.value;
            });

            updateForm(); // Initial call
        });
    </script>
@endsection
