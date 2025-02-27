@extends('layouts.app')

@section('title', 'Detail Tugas')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Tugas</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Detail Tugas untuk Mata Pelajaran: {{ $mapel->nama_mapel }}</h6>

                    <form id="update-tugas-form">
                        @csrf
                        <div class="table-responsive">
                            <table class="table text-center">
                                <thead>
                                    <tr>
                                        <th>Nama Siswa</th>
                                        @foreach ($tugas as $task)
                                            <th>{{ $task->nama_tugas }}</th>
                                        @endforeach
                                        <th>Status</th>
                                        <th>Indikator</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($siswa as $student)
                                        @php
                                            $totalTugas = count($tugas);
                                            $selesai = 0;
                                        @endphp
                                        <tr id="row-{{ $student->id_siswa }}">
                                            <td>{{ $student->nama_siswa }}</td>
                                            @foreach ($tugas as $task)
                                                @php
                                                    if ($task->status == 'Selesai') {
                                                        $selesai++;
                                                    }
                                                @endphp
                                                <td>
                                                    <input type="checkbox" class="tugas-checkbox"
                                                        data-siswa-id="{{ $student->id_siswa }}"
                                                        data-tugas-id="{{ $task->id_tugas }}"
                                                        {{ $task->status == 'Selesai' ? 'checked' : '' }}>
                                                    <div class="mt-2">
                                                        <input type="date" class="form-control tanggal-pengumpulan"
                                                            data-siswa-id="{{ $student->id_siswa }}"
                                                            data-tugas-id="{{ $task->id_tugas }}"
                                                            value="{{ $task->tanggal_pengumpulan }}">
                                                        <textarea class="form-control mt-2 keterangan" data-siswa-id="{{ $student->id_siswa }}"
                                                            data-tugas-id="{{ $task->id_tugas }}" placeholder="Keterangan">{{ $task->keterangan }}</textarea>
                                                    </div>
                                                </td>
                                            @endforeach
                                            <td id="status-{{ $student->id_siswa }}">
                                                {{ $selesai }}/{{ $totalTugas }} Tugas</td>
                                            <td>
                                                <div id="indikator-{{ $student->id_siswa }}"
                                                    class="rounded-circle
                                                    {{ $selesai == $totalTugas ? 'bg-success' : ($totalTugas - $selesai == 1 ? 'bg-warning' : 'bg-danger') }}"
                                                    style="width: 20px; height: 20px;">
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.tugas-checkbox, .tanggal-pengumpulan, .keterangan').on('change', function() {
                let siswaId = $(this).data('siswa-id');
                let tugasId = $(this).data('tugas-id');
                let status = $(`.tugas-checkbox[data-tugas-id="${tugasId}"][data-siswa-id="${siswaId}"]`)
                    .is(':checked') ? 'Selesai' : 'Belum Selesai';
                let tanggal = $(
                        `.tanggal-pengumpulan[data-tugas-id="${tugasId}"][data-siswa-id="${siswaId}"]`)
                    .val();
                let keterangan = $(`.keterangan[data-tugas-id="${tugasId}"][data-siswa-id="${siswaId}"]`)
                    .val();

                $.ajax({
                    url: "{{ url('/update-status-tugas') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        siswa_id: siswaId,
                        tugas_id: tugasId,
                        status: status,
                        tanggal_pengumpulan: tanggal,
                        keterangan: keterangan
                    },
                    success: function(response) {
                        updateStatusUI(siswaId);
                    }
                });
            });

            function updateStatusUI(siswaId) {
                let totalTugas = $(`#row-${siswaId} .tugas-checkbox`).length;
                let selesai = $(`#row-${siswaId} .tugas-checkbox:checked`).length;

                $(`#status-${siswaId}`).text(`${selesai}/${totalTugas} Tugas`);

                let indikator = $(`#indikator-${siswaId}`);
                indikator.removeClass('bg-success bg-warning bg-danger');

                if (selesai == totalTugas) {
                    indikator.addClass('bg-success');
                } else if (totalTugas - selesai == 1) {
                    indikator.addClass('bg-warning');
                } else {
                    indikator.addClass('bg-danger');
                }
            }
        });
    </script>
@endsection
