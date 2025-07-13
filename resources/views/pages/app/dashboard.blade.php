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
            <!-- Tabel 1: Detail Tugas -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Detail Penyelesaian Tugas</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 20%">Nama Siswa</th>
                                    @foreach ($tugasList as $tugas)
                                        <th class="text-center">{{ $tugas }}</th>
                                    @endforeach
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tugasData as $siswaId => $data)
                                    <tr>
                                        <td>{{ $data['nama'] }}</td>
                                        @foreach ($tugasList as $tugas)
                                            <td class="text-center">
                                                {{ $data['tugas'][$tugas]['status'] == 'Selesai' ? 'Sudah Selesai' : 'Belum Selesai' }}
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
                                                $remaining = $total - $completed;
                                            @endphp
                                            @if ($remaining == 0)
                                                <i class="fas fa-check-circle text-success"></i>
                                            @elseif ($remaining == 1)
                                                <i class="fas fa-exclamation-circle text-warning"></i>
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

            <!-- Spasi antara tabel -->
            <div style="height: 30px;"></div>

            <!-- Tabel 2: Rekap Mapel -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Wali Kelas : {{ $waliKelas->nama_wali }}</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 20%">Nama Siswa</th>
                                    @foreach ($mapelList as $mapel)
                                        <th class="text-center">{{ $mapel->nama_mapel }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($siswaList as $siswa)
                                    <tr>
                                        <td>{{ $siswa->nama_siswa }}</td>
                                        @foreach ($mapelList as $mapel)
                                            <td class="text-center">
                                                @if ($mapelProgress[$mapel->id_mapel]['siswa'][$siswa->id_siswa] ?? false)
                                                    <input type="checkbox" checked class="form-check-input"
                                                        style="width: 20px; height: 20px; margin-top:-10px; accent-color: green; pointer-events: none;">
                                                @else
                                                    <input type="checkbox" disabled class="form-check-input"
                                                        style="width: 20px; height: 20px; margin-top:-10px;">
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
        @endisset
    @elseif(\Illuminate\Support\Facades\Auth::guard('guru')->check())
        <!-- Tampilan untuk guru biasa -->
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

    .fa-check,
    .fa-times {
        font-size: 1.2em;
    }

    .fa-check-circle,
    .fa-times-circle {
        font-size: 1.5em;
    }

    .form-check-input {
        cursor: default;
        opacity: 1;
    }

    .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
    }
</style>
