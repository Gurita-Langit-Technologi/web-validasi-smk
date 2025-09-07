@extends('layouts.app')

@section('title', 'Detail Tugas')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/guru/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('page-kelas') }}">Kelas</a></li>
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
                    <p>Mata Pelajaran: <strong>{{ $mapel->nama_diklat }}</strong></p>
                    <p>Kelas: <strong>{{ $rekapKelas->kelas->nama_kelas }}</strong></p>
                    <form action="{{ route('update-status-tugas') }}" method="POST" id="form-tugas">
                        @csrf
                        <div class="table-responsive">
                            <table class="table" id="tabel-tugas">
                                <thead class="text-center">
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

                                                    // dd($rekap->id_rekap_kelas);

                                                @endphp
                                                <td>
                                                    <input type="hidden" name="tugas[{{ $student->id_siswa }}][new]"
                                                        value="Belum Selesai">
                                                    <input type="hidden" name="id_siswa[]"
                                                        value="{{ $student->id_siswa }}">
                                                    <input type="hidden" name="id_rekap_kelas"
                                                        value="{{ $rekap->id_rekap_kelas ?? '' }}">
                                                    <input type="hidden"
                                                        name="tugas[{{ $student->id_siswa }}][{{ $task->id_tugas }}]"
                                                        value="Belum Selesai">
                                                    <div
                                                        style="display: flex; justify-content: center; align-items: center;">
                                                        <input type="checkbox"
                                                            name="tugas[{{ $student->id_siswa }}][{{ $task->id_tugas }}]"
                                                            value="Selesai"
                                                            {{ $rekap && $rekap->status == 'Selesai' ? 'checked' : '' }}
                                                            onchange="toggleTanggal(this, '{{ $student->id_siswa }}', '{{ $task->id_tugas }}')">
                                                    </div>

                                                    <div class="mt-2">
                                                        <input type="date" class="form-control"
                                                            name="tanggal_pengumpulan[{{ $student->id_siswa }}][{{ $task->id_tugas }}]"
                                                            value="{{ $rekap->tanggal_pengumpulan ?? '' }}"
                                                            id="tanggal_{{ $student->id_siswa }}_{{ $task->id_tugas }}"
                                                            {{ $rekap && $rekap->status == 'Selesai' ? '' : 'disabled' }}>
                                                        <textarea class="form-control mt-2" name="keterangan[{{ $student->id_siswa }}][{{ $task->id_tugas }}]"
                                                            placeholder="Keterangan">{{ $rekap->keterangan ?? '' }}</textarea>
                                                    </div>
                                                    <input type="number" class="form-control mt-2"
                                                        name="nilai[{{ $student->id_siswa }}][{{ $task->id_tugas }}]"
                                                        value="{{ $rekap->nilai ?? '' }}" placeholder="Nilai"
                                                        min="0" max="100">
                                                </td>
                                            @endforeach
                                            <td class="text-center">
                                                {{ $siswaStatus[$student->id_siswa]['completed'] }}/{{ $siswaStatus[$student->id_siswa]['total'] }}
                                                Tugas
                                            </td>
                                            <td class="text-center">
                                                <div class="rounded-circle bg-{{ $siswaStatus[$student->id_siswa]['status'] }} mx-auto"
                                                    style="width: 20px; height: 20px;"></div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                        <button type="button" class="btn btn-secondary mt-3" onclick="tambahTugas()">Tambah 1
                            Tugas</button>
                        <a href="{{ route('export-tugas', ['id_mapel' => $mapel->id_mapel]) }}"
                            class="btn btn-success mt-3">
                            Export ke Excel
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function tambahTugas() {
            const table = document.getElementById('tabel-tugas');
            const headerRow = table.querySelector('thead tr');
            const bodyRows = table.querySelectorAll('tbody tr');

            const newHeaderCell = document.createElement('th');
            newHeaderCell.innerHTML = `
                <input type="hidden" name="id_mapel" value="{{ $mapel->id_mapel }}">
                <input type="text" class="form-control form-control-sm" name="nama_tugas_baru" placeholder="Nama Tugas Baru" id="nama-tugas-baru">
            `;
            headerRow.insertBefore(newHeaderCell, headerRow.querySelector('th:nth-last-child(2)'));

            bodyRows.forEach(row => {
                const newCell = document.createElement('td');
                newCell.innerHTML = `
                    <input type="checkbox" name="tugas[${row.querySelector('td').textContent.trim()}][new]" value="Belum Selesai">
                    <div class="mt-2">
                        <input type="date" class="form-control" name="tanggal_pengumpulan[${row.querySelector('td').textContent.trim()}][new]">
                        <textarea class="form-control mt-2" name="keterangan[${row.querySelector('td').textContent.trim()}][new]" placeholder="Keterangan"></textarea>
                    </div>
                `;
                row.insertBefore(newCell, row.querySelector('td:nth-last-child(2)'));
            });
        }

        function toggleTanggal(checkbox, siswaId, tugasId) {
            let tanggalInput = document.getElementById(`tanggal_${siswaId}_${tugasId}`);

            if (checkbox.checked) {
                tanggalInput.removeAttribute('disabled');
            } else {
                tanggalInput.setAttribute('disabled', 'true');
                tanggalInput.value = "";
            }
        }
    </script>
@endsection
