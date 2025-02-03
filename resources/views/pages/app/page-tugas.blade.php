@extends('layouts.app')

@section('title', 'Page Rekap Tugas')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item " aria-current="page">Dashboard</li>
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
            <div class="input-group date datepicker dashboard-date mr-2 mb-2 mb-md-0 d-md-none d-xl-flex"
                id="dashboardDate">
                <span class="input-group-addon bg-transparent"><i data-feather="calendar" class=" text-primary"></i></span>
                <input type="text" class="form-control">
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Data Table</h6>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Kelas</th>
                                    <th>Mapel</th>
                                    <th>Total Tugas</th>
                                    <th>Nama-nama Tugas</th>
                                    <th>Tugas Selesai</th>
                                    <th>Tanggungan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rekapTugas as $rekap)
                                    <tr>
                                        <td style="vertical-align: top; padding: 20px 10px; align-items: center;">
                                            {{ $rekap->kelas->nama_kelas }}</td>
                                        <td style="vertical-align: top; padding: 20px 10px; align-items: center;">
                                            {{ $rekap->mapel->nama_mapel }}</td>
                                        <td style="display: flex; align-items: center; ">
                                            <button class="btn btn-outline-secondary btn-sm" type="button"
                                                onclick="decrement('total-tugas-{{ $rekap->id_rekap }}', 'tugas-names-container-{{ $rekap->id }}')">-</button>
                                            <input id="total-tugas-{{ $rekap->id_rekap }}" type="number"
                                                class="form-control form-control-sm text-center mx-1"
                                                value="{{ $rekap->mapel->total_tugas }}" readonly>
                                            <button class="btn btn-outline-secondary btn-sm" type="button"
                                                onclick="increment('total-tugas-{{ $rekap->id_rekap }}', 'tugas-names-container-{{ $rekap->id_rekap }}')">+</button>
                                            <a href="{{ route('detail-tugas', $rekap->mapel->id_mapel) }}"
                                                class="btn btn-warning btn-sm ml-2">Detail</a>
                                        </td>
                                        <td style="vertical-align: top; ">
                                            <div id="tugas-names-container-{{ $rekap->id_rekap }}">
                                                @foreach ($rekap->mapel->tugas as $tugas)
                                                    <input type="text" class="form-control form-control-sm mb-2"
                                                        value="{{ $tugas->nama_tugas }}" readonly>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td style="display: flex; align-items: center;">
                                            <button class="btn btn-outline-secondary btn-sm" type="button"
                                                onclick="decrement('tugas-selesai-{{ $rekap->id_rekap }}')">-</button>
                                            <input id="tugas-selesai-{{ $rekap->id_rekap }}" type="number"
                                                class="form-control form-control-sm text-center mx-1"
                                                value="{{ $rekap->mapel->Jumlah_selesai }}">
                                            <button class="btn btn-outline-secondary btn-sm" type="button"
                                                onclick="increment('tugas-selesai-{{ $rekap->id_rekap }}')">+</button>
                                        </td>
                                        <td style="vertical-align: top; padding: 20px 10px; align-items: center;">
                                            {{ $rekap->mapel->Jumlah_tanggungan }}</td>
                                        <td style="vertical-align: top; padding: 10px 0;">
                                            <a href="{{ route('rekap-tugas.edit', $rekap->id_rekap) }}"
                                                class="btn btn-primary btn-sm">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>


                        </table>
                    </div>

                    <button class="btn btn-primary mt-3" onclick="generateTasks()">Generate</button>

                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    function increment(inputId, containerId = null) {
        const input = document.getElementById(inputId);
        input.value = parseInt(input.value) + 1;

        if (containerId) {
            const container = document.getElementById(containerId);
            const newTaskInput = document.createElement('input');
            newTaskInput.type = 'text';
            newTaskInput.className = 'form-control form-control-sm mb-2';
            newTaskInput.placeholder = `Nama Tugas ${input.value}`;
            container.appendChild(newTaskInput);
        }
    }

    function decrement(inputId, containerId = null) {
        const input = document.getElementById(inputId);
        const currentValue = parseInt(input.value);
        if (currentValue > 1) {
            input.value = currentValue - 1;

            if (containerId) {
                const container = document.getElementById(containerId);
                if (container.lastChild) {
                    container.removeChild(container.lastChild);
                }
            }
        } else {
            alert('Nilai tidak dapat kurang dari 1.');
        }
    }

    function generateTasks() {
        const taskInputs = document.querySelectorAll('#tugas-names-container input');
        const tasks = Array.from(taskInputs).map(input => input.value);
        console.log('Generated Tasks:', tasks);
        alert('Tasks Generated Successfully!');
    }
</script>
