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
                                            {{ $rekap->mapel->nama_diklat }}</td>
                                        <td style="display: flex; align-items: center; ">
                                            <input id="total-tugas-{{ $rekap->id_rekap_kelas }}" type="number"
                                                class="form-control form-control-sm text-center"
                                                value="{{ $rekap->total_tugas }}" min="1"
                                                oninput="generateTaskNames('{{ $rekap->id_rekap_kelas }}')">
                                        </td>
                                        <td>
                                            <div id="tugas-names-container-{{ $rekap->id_rekap_kelas }}">
                                                @foreach ($rekap->tugas->unique('nama_tugas') as $index => $tugas)
                                                    <input type="text" class="form-control form-control-sm mb-2"
                                                        value="{{ $tugas->nama_tugas }}"
                                                        id="task-{{ $rekap->id_rekap_kelas }}-{{ $index }}"
                                                        data-old-name="{{ $tugas->nama_tugas }}">
                                                @endforeach
                                            </div>
                                            <!-- Tombol Plus untuk Menambah 1 Form Input -->
                                            <button type="button" class="btn btn-sm btn-outline-primary mt-2"
                                                onclick="addSingleInput('{{ $rekap->id_rekap_kelas }}')">
                                                <i class="fas fa-plus"></i> Tambah Tugas
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button class="btn btn-primary mt-2"
                            onclick="generateTasksPerClass('{{ $rekap->id_rekap_kelas }}')">Simpan</button>
                        <button class="btn btn-secondary mt-2"
                            onclick="addSingleTask('{{ $rekap->id_rekap_kelas }}')">Simpan 1 Tugas</button>
                    </div>
                </div>
            </div>

            <!-- Card Report -->
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Report Progress {{ $rekap->mapel->nama_diklat }}</h6>
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
                                            <input id="tugas-selesai-{{ $rekap->id_rekap_kelas }}" type="number"
                                                class="form-control form-control-sm text-center"
                                                value="{{ $rekap->jumlah_selesai }}" min="0">
                                        </td>
                                        <td>{{ $rekap->jumlah_tanggungan }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <button class="btn btn-success mt-3" onclick="generateAllTasks()">generate All Kelas</button>
@endsection

<script>
    function generateTaskNames(classId) {
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
                if (taskInput && !taskInput.dataset.oldName) {
                    container.removeChild(taskInput);
                }
            }
        }
    }

    function addSingleInput(classId) {
        const container = document.getElementById(`tugas-names-container-${classId}`);
        const taskInputs = container.getElementsByTagName("input");
        const newIndex = taskInputs.length;

        const newTaskInput = document.createElement('input');
        newTaskInput.type = 'text';
        newTaskInput.className = 'form-control form-control-sm mb-2';
        newTaskInput.placeholder = `Nama Tugas ${newIndex + 1}`;
        newTaskInput.id = `task-${classId}-${newIndex}`;
        container.appendChild(newTaskInput);

        // generate total tugas input
        const totalTasksInput = document.getElementById(`total-tugas-${classId}`);
        totalTasksInput.value = newIndex + 1;
    }

    function addSingleTask(classId) {
        const container = document.getElementById(`tugas-names-container-${classId}`);
        const taskInputs = container.getElementsByTagName("input");
        const lastTaskInput = taskInputs[taskInputs.length - 1]; // Ambil input terakhir

        if (!lastTaskInput || !lastTaskInput.value.trim()) {
            alert("Isi nama tugas terlebih dahulu!");
            return;
        }

        fetch(`/guru/add-single-task/${classId}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: JSON.stringify({
                    task: lastTaskInput.value.trim()
                })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    // Tambahkan data-old-name ke input yang baru dibuat
                    lastTaskInput.dataset.oldName = lastTaskInput.value.trim();
                }
            })
            .catch(error => console.error("Error:", error));
    }

    function generateTasksPerClass(classId) {
        const container = document.getElementById(`tugas-names-container-${classId}`);
        const taskInputs = container.querySelectorAll("input");

        const tasks = Array.from(taskInputs).map(input => ({
            new_name: input.value.trim(),
            old_name: input.dataset.oldName || null
        }));

        fetch(`/guru/generate-tasks-per-class/${classId}`, { // Pastikan route ini benar
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: JSON.stringify({
                    tasks
                })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    // Update data-old-name untuk semua input yang berhasil diupdate
                    taskInputs.forEach((input, index) => {
                        if (tasks[index].new_name) {
                            input.dataset.oldName = tasks[index].new_name;
                        }
                    });
                }
            })
            .catch(error => console.error("Error:", error));
    }

    function generateAllTasks() {
        const rekapContainers = document.querySelectorAll('[id^="tugas-names-container-"]');
        let allTasks = {};

        rekapContainers.forEach(container => {
            const classId = container.id.split('-').pop();
            const taskInputs = container.querySelectorAll("input");

            allTasks[classId] = Array.from(taskInputs).map(input => ({
                new_name: input.value.trim(),
                old_name: input.dataset.oldName || null
            }));
        });

        fetch("/guru/generate-all-tasks", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: JSON.stringify({
                    tasks: allTasks
                })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    // generate data-old-name untuk semua input yang berhasil digenerate
                    rekapContainers.forEach(container => {
                        const classId = container.id.split('-').pop();
                        const taskInputs = container.querySelectorAll("input");
                        const tasks = allTasks[classId];

                        taskInputs.forEach((input, index) => {
                            if (tasks[index] && tasks[index].new_name) {
                                input.dataset.oldName = tasks[index].new_name;
                            }
                        });
                    });
                }
            })
            .catch(error => console.error("Error:", error));
    }
</script>
