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
                    <!-- Alert Messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-3" role="alert"
                            style="box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15); border-left: 4px solid #10b981; border-radius: 6px;">
                            <i class="fas fa-check-circle me-2 mr-2 text-success" style="font-size: 1.25rem;"></i>
                            <div class="flex-grow-1">
                                <strong>Berhasil!</strong> {{ session('success') }}
                            </div>
                            <button type="button" class="close ms-auto ml-auto" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-3" role="alert"
                            style="box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15); border-left: 4px solid #ef4444; border-radius: 6px;">
                            <i class="fas fa-times-circle me-2 mr-2 text-danger" style="font-size: 1.25rem;"></i>
                            <div class="flex-grow-1">
                                <strong>Gagal!</strong> {{ session('error') }}
                            </div>
                            <button type="button" class="close ms-auto ml-auto" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

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
                                            <th style="min-width: 300px;">{{ $task->nama_tugas }}</th>
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
                                                <td style="min-width: 300px;">
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

                                                    <div class="mt-2 d-flex align-items-center">
                                                        <label class="form-label mb-0 me-2" style="min-width: 30px;">Tgl</label>
                                                        <input type="date" class="form-control ml-2"
                                                            name="tanggal_pengumpulan[{{ $student->id_siswa }}][{{ $task->id_tugas }}]"
                                                            value="{{ $rekap->tanggal_pengumpulan ?? '' }}"
                                                            id="tanggal_{{ $student->id_siswa }}_{{ $task->id_tugas }}"
                                                            {{ $rekap && $rekap->status == 'Selesai' ? '' : 'disabled' }}>
                                                    </div>
                                                    <div class="mt-2 d-flex align-items-start">
                                                        <label class="form-label mb-0 me-2" style="min-width: 30px;">Ket.</label>
                                                        <textarea class="form-control ml-2" name="keterangan[{{ $student->id_siswa }}][{{ $task->id_tugas }}]"
                                                            placeholder="Keterangan">{{ $rekap->keterangan ?? '' }}</textarea>
                                                    </div>
                                                    <div class="mt-2 d-flex align-items-center">
                                                        <label class="form-label mb-0 me-2" style="min-width: 30px;">Nilai</label>
                                                        <input type="number" class="form-control ml-2"
                                                            name="nilai[{{ $student->id_siswa }}][{{ $task->id_tugas }}]"
                                                            value="{{ $rekap->nilai ?? '' }}" placeholder="Nilai"
                                                            min="0" max="100">
                                                    </div>
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
                        <div class="mt-3 d-flex align-items-center flex-wrap" style="gap: 8px;">
                            <button type="submit" class="btn btn-primary" id="btn-simpan-ceklis">
                                <i class="fas fa-save mr-1"></i> Simpan
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="tambahTugas()">
                                <i class="fas fa-plus mr-1"></i> Tambah 1 Tugas
                            </button>
                            <a href="{{ route('export-tugas', ['id_mapel' => $mapel->id_mapel, 'id_kelas' => $rekapKelas->id_kelas]) }}"
                                class="btn btn-success btn-icon-text d-inline-flex align-items-center">
                                <i class="btn-icon-prepend" data-feather="file-text" style="width: 16px; height: 16px; margin-right: 6px;"></i> Export ke Excel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        function tambahTugas() {
            const table = document.getElementById('tabel-tugas');
            const headerRow = table.querySelector('thead tr');
            const bodyRows = table.querySelectorAll('tbody tr');

            const newHeaderCell = document.createElement('th');
            newHeaderCell.innerHTML = `
                <input type="hidden" name="id_mapel" value="{{ $mapel->id_mapel }}">
                <input type="text" class="form-control form-control-sm" name="nama_tugas_baru" placeholder="Nama Tugas Baru" id="nama-tugas-baru" autofocus>
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

            if (typeof showToast === 'function') {
                showToast('info', 'Kolom tugas baru telah ditambahkan. Silakan isi nama tugas lalu klik Simpan.', 'Info');
            }
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

        document.addEventListener('DOMContentLoaded', function() {
            const formTugas = document.getElementById('form-tugas');
            if (formTugas) {
                formTugas.addEventListener('submit', function() {
                    const btnSimpan = document.getElementById('btn-simpan-ceklis');
                    if (btnSimpan) {
                        btnSimpan.disabled = true;
                        btnSimpan.innerHTML = '<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Menyimpan...';
                    }
                });
            }
        });
    </script>
@endsection
