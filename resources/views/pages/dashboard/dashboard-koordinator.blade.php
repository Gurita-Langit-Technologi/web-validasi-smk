@extends('layouts.app')

@section('title', 'Dashboard Koordinator Ujian')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('koordinator.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard Koordinator</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Selamat Datang, Koordinator Ujian</h4>
        </div>
    </div>

    <!-- Form Pencarian Siswa -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Pencarian Siswa</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('koordinator.dashboard') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="no_induk">No Induk Siswa (NISN)</label>
                            <input type="text" class="form-control" id="no_induk" name="no_induk"
                                value="{{ request('no_induk') }}" placeholder="Masukkan NISN">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nama_siswa">Nama Siswa</label>
                            <input type="text" class="form-control" id="nama_siswa" name="nama_siswa"
                                value="{{ request('nama_siswa') }}" placeholder="Masukkan Nama Siswa">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 mt-4">Cari</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if (isset($siswa))
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Informasi Siswa</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>NISN :</strong> {{ $siswa->no_induk }}</p>
                        <p><strong>Nama :</strong> {{ $siswa->nama_siswa }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Kelas :</strong> {{ $siswa->kelas->nama_kelas ?? '-' }}</p>
                        <p><strong>Kompetensi Keahlian :</strong> {{ $siswa->kelas->kompetensi_keahlian ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Rekap Tugas Siswa</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>Mata Pelajaran</th>
                                <th>Nama Tugas</th>
                                <th>Status</th>
                                <th>Tanggal Pengumpulan</th>
                                <th>Nilai</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tugasSiswa as $tugas)
                                <tr>
                                    <td>{{ $tugas->mapel->nama_diklat ?? '-' }}</td>
                                    <td>{{ $tugas->nama_tugas }}</td>
                                    <td class="text-center">
                                        @if ($tugas->status == 'Selesai')
                                            <span class="badge badge-success">Selesai</span>
                                        @else
                                            <span class="badge badge-danger">Belum Selesai</span>
                                        @endif
                                    </td>
                                    <td>{{ $tugas->tanggal_pengumpulan ? \Carbon\Carbon::parse($tugas->tanggal_pengumpulan)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="text-center">
                                        @if ($tugas->nilai)
                                            {{ $tugas->nilai }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $tugas->keterangan ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data tugas ditemukan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif(request()->has('no_induk') || request()->has('nama_siswa'))
        <div class="alert alert-warning">Siswa tidak ditemukan. Silakan coba dengan kriteria pencarian lain.</div>
    @endif
@endsection

@section('styles')
    <style>
        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: middle;
        }

        .card-header {
            padding: 1rem 1.25rem;
        }

        .table {
            margin-bottom: 0;
        }

        .badge {
            font-size: 0.9em;
            padding: 0.4em 0.6em;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-danger {
            background-color: #dc3545;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection
