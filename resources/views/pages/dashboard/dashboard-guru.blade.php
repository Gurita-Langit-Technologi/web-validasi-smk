@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('guru.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard Guru</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Selamat Datang, {{ $guru->nama }}</h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <div class="input-group date datepicker dashboard-date mr-2 mb-2 mb-md-0 d-md-none d-xl-flex" id="dashboardDate">
                <span class="input-group-addon bg-transparent"><i data-feather="calendar" class=" text-primary"></i></span>
                <input type="text" class="form-control">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Dashboard Guru</h6>
                    <p>Anda login sebagai guru dengan kode: {{ $guru->kode_guru }}</p>

                    <!-- Tambahkan konten khusus guru di sini -->
                    <div class="mt-4">
                        <a href="{{ route('page-tugas') }}" class="btn btn-primary mr-2">
                            <i class="fas fa-tasks"></i> Lihat Tugas
                        </a>
                        <a href="{{ route('page-kelas') }}" class="btn btn-success">
                            <i class="fas fa-users"></i> Lihat Kelas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-weight: 600;
        }

        .btn {
            padding: 0.5rem 1.5rem;
            font-weight: 500;
        }
    </style>
@endsection
