@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Welcome to Dashboard</h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <div class="input-group date datepicker dashboard-date mr-2 mb-2 mb-md-0 d-md-none d-xl-flex" id="dashboardDate">
                <span class="input-group-addon bg-transparent"><i data-feather="calendar" class=" text-primary"></i></span>
                <input type="text" class="form-control">
            </div>
        </div>
    </div>

    @if (\Illuminate\Support\Facades\Auth::guard('wali')->check())
        @isset($error)
            <div class="alert alert-danger">{{ $error }}</div>
        @else
            <div class="row">
                <div class="col-12">
                    <h4 class="mb-4">Progress Kelas {{ $kelas->nama_kelas }}</h4>

                    <!-- Tabel 1: Detail Tugas per Siswa -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5>Detail Tugas Siswa</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Nama Siswa</th>
                                            @foreach ($tugasColumns as $namaTugas)
                                                <th class="text-center">{{ $namaTugas }}</th>
                                            @endforeach
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tugasData as $siswaId => $data)
                                            <tr>
                                                <td>{{ $data['nama'] }}</td>
                                                @foreach ($tugasColumns as $namaTugas)
                                                    <td class="text-center">
                                                        @if (isset($data['tugas'][$namaTugas]))
                                                            {{ $data['tugas'][$namaTugas]['status'] == 'Selesai' ? 'Sudah' : 'Belum' }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                @endforeach
                                                <td class="text-center">
                                                    @php
                                                        $completed = count(
                                                            array_filter($data['tugas'], function ($t) {
                                                                return $t['status'] == 'Selesai';
                                                            }),
                                                        );
                                                        $total = count($data['tugas']);
                                                    @endphp
                                                    @if ($total > 0 && $completed == $total)
                                                        <i class="fas fa-check-circle text-success"></i>
                                                    @else
                                                        <i class="fas fa-times-circle text-danger"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel 2: Rekap Semua Mapel -->
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5>Rekap Kelas per Mapel</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Nama Siswa</th>
                                            @foreach ($mapelList as $mapel)
                                                <th class="text-center">{{ $mapel->nama_mapel }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($siswaList as $siswa)
                                            <tr>
                                                <td>{{ $siswa->nama }}</td>
                                                @foreach ($mapelList as $mapel)
                                                    <td class="text-center">
                                                        @if (isset($mapelProgress[$mapel->id_mapel]['siswa'][$siswa->id_siswa]))
                                                            @php
                                                                $progress =
                                                                    $mapelProgress[$mapel->id_mapel]['siswa'][
                                                                        $siswa->id_siswa
                                                                    ];
                                                            @endphp
                                                            @if ($progress['completed_all'])
                                                                <i class="fas fa-check text-success"></i>
                                                            @else
                                                                <i class="fas fa-times text-danger"></i>
                                                            @endif
                                                        @else
                                                            <i class="fas fa-times text-danger"></i>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endisset
    @elseif(\Illuminate\Support\Facades\Auth::guard('guru')->check())
        <!-- Existing guru dashboard content -->
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Welcome, {{ $guru->nama }}</h6>
                        <p>You're logged in as a teacher.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
