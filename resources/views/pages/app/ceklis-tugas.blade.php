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
                    <form action="{{ route('update-status-tugas') }}" method="POST">
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
                                        <tr>
                                            <td>{{ $student->nama_siswa }}</td>
                                            @foreach ($siswaTugas[$student->id_siswa] as $task)
                                                @php
                                                    $rekap = \App\Models\RekapPengumpulan::where(
                                                        'id_tugas',
                                                        $task->id_tugas,
                                                    )
                                                        ->where('id_siswa', $student->id_siswa)
                                                        ->first();
                                                @endphp
                                                <td>
                                                    <input type="checkbox"
                                                        name="tugas[{{ $student->id_siswa }}][{{ $task->id_tugas }}]"
                                                        value="Selesai"
                                                        {{ $rekap && $rekap->status == 'Selesai' ? 'checked' : '' }}>
                                                    <div class="mt-2">
                                                        <input type="date" class="form-control"
                                                            name="tanggal_pengumpulan[{{ $student->id_siswa }}][{{ $task->id_tugas }}]"
                                                            value="{{ $rekap->tanggal_pengumpulan ?? '' }}">
                                                        <textarea class="form-control mt-2" name="keterangan[{{ $student->id_siswa }}][{{ $task->id_tugas }}]"
                                                            placeholder="Keterangan">{{ $rekap->keterangan ?? '' }}</textarea>
                                                    </div>
                                                </td>
                                            @endforeach
                                            <td>
                                                {{ $siswaStatus[$student->id_siswa]['completed'] }}/{{ $siswaStatus[$student->id_siswa]['total'] }}
                                                Tugas
                                            </td>
                                            <td>
                                                <div class="rounded-circle bg-{{ $siswaStatus[$student->id_siswa]['status'] }}"
                                                    style="width: 20px; height: 20px;"></div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                        <a href="{{ route('export-tugas', ['id_mapel' => $mapel->id_mapel]) }}"
                            class="btn btn-success mt-3">
                            Export ke Excel
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
