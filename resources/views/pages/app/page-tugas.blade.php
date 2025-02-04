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
    </div>

    @foreach ($rekapTugas as $rekap)
        <div class="row">
            <!-- Card Input Rekap -->
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Input Rekap</h6>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Kelas</th>
                                        <th>Mapel</th>
                                        <th>Total Tugas</th>
                                        <th>Nama-nama Tugas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="vertical-align: top; padding: 20px 10px; align-items: center;">
                                            {{ $rekap->kelas->nama_kelas }}</td>
                                        <td style="vertical-align: top; padding: 20px 10px; align-items: center;">
                                            {{ $rekap->mapel->nama_mapel }}</td>
                                        <td style="display: flex; align-items: center; ">
                                            <input id="total-tugas-{{ $rekap->id_rekap }}" type="number"
                                                class="form-control form-control-sm text-center"
                                                value="{{ $rekap->mapel->total_tugas }}" min="1"
                                                oninput="updateTaskNames('{{ $rekap->id_rekap }}')">
                                        </td>
                                        <td>
                                            <div id="tugas-names-container-{{ $rekap->id_rekap }}">
                                                @foreach ($rekap->mapel->tugas as $index => $tugas)
                                                    <input type="text" class="form-control form-control-sm mb-2"
                                                        value="{{ $tugas->nama_tugas }}"
                                                        id="task-{{ $rekap->id_rekap }}-{{ $index }}">
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button class="btn btn-primary mt-2"
                            onclick="generateTasksPerClass('{{ $rekap->id_rekap }}')">Add</button>
                    </div>
                </div>
            </div>

            <!-- Card Report -->
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Report Progress {{ $rekap->mapel->nama_mapel }}</h6>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>

                                        <th>Tugas Selesai</th>
                                        <th>Tanggungan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input id="tugas-selesai-{{ $rekap->id_rekap }}" type="number"
                                                class="form-control form-control-sm text-center"
                                                value="{{ $rekap->mapel->jumlah_selesai }}" min="0">
                                        </td>
                                        <td>{{ $rekap->mapel->jumlah_tanggungan }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <button class="btn btn-success mt-3" onclick="generateAllTasks()">Generate All Kelas</button>
@endsection

<script>
    function updateTaskNames(classId) {
        const totalTasksInput = document.getElementById(`total-tugas-${classId}`);
        const container = document.getElementById(`tugas-names-container-${classId}`);
        const totalTasks = parseInt(totalTasksInput.value) || 1;
        const currentTasks = container.getElementsByTagName("input").length;

        if (totalTasks > currentTasks) {
            for (let i = currentTasks; i < totalTasks; i++) {
                const newTaskInput = document.createElement('input');
                newTaskInput.type = 'text';
                newTaskInput.className = 'form-control form-control-sm mb-2';
                newTaskInput.placeholder = `Nama Tugas ${i + 1}`;
                newTaskInput.id = `task-${classId}-${i}`;
                container.appendChild(newTaskInput);
            }
        } else if (totalTasks < currentTasks) {
            for (let i = currentTasks - 1; i >= totalTasks; i--) {
                const taskInput = document.getElementById(`task-${classId}-${i}`);
                if (taskInput) {
                    container.removeChild(taskInput);
                }
            }
        }
    }

    function generateTasksPerClass(classId) {
        const taskInputs = document.querySelectorAll(`#tugas-names-container-${classId} input`);
        const tasks = Array.from(taskInputs).map(input => input.value);
        console.log(`Generated Tasks for Class ${classId}:`, tasks);
        alert(`Tasks for Class ${classId} Generated Successfully!`);
    }

    function generateAllTasks() {
        console.log("Generating tasks for all classes...");
        alert("All tasks generated successfully!");
    }
</script>
