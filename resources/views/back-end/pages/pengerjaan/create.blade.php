@extends('back-end.layouts.main')

@section('content')
<div class="main-panel" style="margin-top: 10px;">
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
                        Input Progress Pengerjaan Project: {{ $project->nama_project ?? '-' }}
                    </h4>
                    {{-- Tampilkan Job Type --}}
                    @php
                        $jobTypes = $project->taskDistributions->where('user_id', Auth::id())->pluck('jobType.name')->unique();
                    @endphp

                    @if($jobTypes->isNotEmpty())
                        <p><strong>Job Type Anda:</strong> {{ $jobTypes->implode(', ') }}</p>
                    @else
                        <p><strong>Job Type Anda:</strong> Tidak ada</p>
                    @endif

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
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $fitur->fitur->name ?? '-' }}</td>
                                            <td>{{ $fitur->name }}</td>
                                            <td>
                                                <input type="hidden" name="detail_fiturs_id[]" value="{{ $fitur->id }}">
                                                <input type="number"
                                                name="pengerjaan[]"
                                                class="form-control"
                                                placeholder="%"
                                                min="0" max="100"
                                                required
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
    document.addEventListener('DOMContentLoaded', function () {
    const inputs = document.querySelectorAll('input[name="pengerjaan[]"]');
    const totalField = document.getElementById('totalPersentase');

    function updateTotal() {
        let total = 0;
        let count = 0;

        // Menghitung total dan jumlah elemen yang valid
        inputs.forEach(input => {
            const value = parseFloat(input.value);
            if (!isNaN(value)) {
                total += value;
                count++;
            }
        });

        // Menghitung rata-rata dari semua input
        if (count > 0) {
            total = total / count;
        }

        // Menampilkan total presentase di field
        totalField.value = total.toFixed(2) + ' %';
    }

    // Update saat halaman dimuat
    updateTotal();

    // Update setiap kali input berubah
    inputs.forEach(input => {
        input.addEventListener('input', updateTotal);
    });
});

</script>

@endsection
