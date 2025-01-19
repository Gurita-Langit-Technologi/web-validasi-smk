@extends('layouts.app')

@section('title', 'Detail Tugas')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item " aria-current="page">Page Kelas</li>
            <li class="breadcrumb-item " aria-current="page">Page Tugas</li>
            <li class="breadcrumb-item active" aria-current="page">Detail Tugas</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Detail Tugas</h6>
                    <form action="{{ url('/save-detail') }}" method="POST">
                        @csrf
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tugas</th>
                                        <th>Selesai</th>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Menulis Laporan</td>
                                        <td>
                                            <input type="checkbox" name="ceklis" value="1">
                                        </td>
                                        <td>
                                            <input type="date" name="tanggal" class="form-control datepicker">
                                        </td>
                                        <td>
                                            <textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Membuat Presentasi</td>
                                        <td>
                                            <input type="checkbox" name="ceklis" value="1">
                                        </td>
                                        <td>
                                            <input type="date" name="tanggal" class="form-control datepicker">
                                        </td>
                                        <td>
                                            <textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('page-tugas') }}" class="btn btn-secondary">Kembali</a>
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
