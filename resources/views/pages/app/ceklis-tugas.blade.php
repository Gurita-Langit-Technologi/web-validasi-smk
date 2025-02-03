@extends('layouts.app')

@section('title', 'Detail Tugas')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item" aria-current="page">Page Tugas</li>
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
                    <form action="{{ url('/save-detail') }}" method="POST">
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($siswa as $student)
                                        <tr>
                                            <td>{{ $student->nama_siswa }}</td>
                                            @foreach ($tugas as $task)
                                                <td>
                                                    <input type="checkbox" name="tugas_{{ $task->id_tugas }}[]"
                                                        value="1" {{ $task->status == 'Selesai' ? 'checked' : '' }}>
                                                    <div class="mt-2">
                                                        <input type="date" name="tanggal_{{ $task->id_tugas }}[]"
                                                            class="form-control datepicker"
                                                            value="{{ $task->tanggal_pengumpulan }}">
                                                        <textarea name="keterangan_{{ $task->id_tugas }}[]" class="form-control mt-2" placeholder="Keterangan">{{ $task->keterangan }}</textarea>
                                                    </div>
                                                </td>
                                            @endforeach
                                            <td>
                                                <!-- Menampilkan Badge Status -->
                                                @if ($siswaStatus[$student->id_siswa] == 'success')
                                                    <span class="badge bg-success">Selesai Semua</span>
                                                @elseif($siswaStatus[$student->id_siswa] == 'warning')
                                                    <span class="badge bg-warning">Tugas Kurang 1</span>
                                                @else
                                                    <span class="badge bg-danger">Tugas Belum Selesai</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('page-tugas') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-outline-success btn-icon-text ">
                                <i class="btn-icon-prepend" data-feather="download"></i>
                                Import
                            </button>
                            <button type="submit" class="btn btn-outline-primary btn-icon-text ">
                                <i class="btn-icon-prepend" data-feather="printer"></i>
                                Print
                            </button>
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
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });
        });
    </script>
@endsection
