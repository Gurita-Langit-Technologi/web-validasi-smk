@extends('layouts.app')

@section('title', 'Page Rekap Tugas')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item " aria-current="page">Dashboard</li>
            <li class="breadcrumb-item " aria-current="page">Page Kelas</li>
            <li class="breadcrumb-item active" aria-current="page">Page Tugas</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Halaman Rekap Tugas</h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <div class="input-group date datepicker dashboard-date mr-2 mb-2 mb-md-0 d-md-none d-xl-flex" id="dashboardDate">
                <span class="input-group-addon bg-transparent"><i data-feather="calendar" class=" text-primary"></i></span>
                <input type="text" class="form-control">
            </div>
            <button type="button" class="btn btn-outline-info btn-icon-text mr-2 d-none d-md-block">
                <i class="btn-icon-prepend" data-feather="download"></i>
                Import
            </button>
            <button type="button" class="btn btn-outline-primary btn-icon-text mr-2 mb-2 mb-md-0">
                <i class="btn-icon-prepend" data-feather="printer"></i>
                Print
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Data Table</h6>

                    <div class="table-responsive">
                        <table id="dataTableExample" class="table">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Total tugas</th>
                                    <th>Tugas selesai</th>
                                    <th>Tanggungan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Tiger Nixon</td>
                                    <td>
                                        <div class="input-group">
                                            <button class="btn btn-outline-secondary btn-sm" type="button"
                                                onclick="decrement('total-tugas')">-</button>
                                            <input id="total-tugas" type="number"
                                                class="form-control form-control-sm text-center" value="1"
                                                min="0">
                                            <button class="btn btn-outline-secondary btn-sm" type="button"
                                                onclick="increment('total-tugas')">+</button>
                                            <a href="{{ route('form-tugas') }}"
                                                class="btn btn-warning btn-sm ml-2">Detail</a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <button class="btn btn-outline-secondary btn-sm" type="button"
                                                onclick="decrement('tugas-selesai')">-</button>
                                            <input id="tugas-selesai" type="number"
                                                class="form-control form-control-sm text-center" value="1"
                                                min="0">
                                            <button class="btn btn-outline-secondary btn-sm" type="button"
                                                onclick="increment('tugas-selesai')">+</button>
                                        </div>
                                    </td>
                                    <td>0</td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
<script>
    function increment(inputId) {
        const input = document.getElementById(inputId);
        input.value = parseInt(input.value) + 1;
    }

    function decrement(inputId) {
        const input = document.getElementById(inputId);
        if (input.value > 0) {
            input.value = parseInt(input.value) - 1;
        }
    }
</script>
