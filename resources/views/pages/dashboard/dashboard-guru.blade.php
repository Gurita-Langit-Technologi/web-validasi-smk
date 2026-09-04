@extends('layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\Schema;
@endphp

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
    <!-- Welcome Header -->
    <div class="welcome-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div class="welcome-content">
                <h4 class="welcome-title">Selamat Datang, {{ $guru->nama_guru }}</h1>

            </div>
            <div class="date-widget">
                <div class="date-container">
                    <i class="fas fa-calendar-alt"></i>
                    <span id="currentDate">{{ now()->format('d-F-Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
            <div class="alert-content">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
            <div class="alert-content">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $errors->first() }}</span>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Personal Information Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-user"></i>
                        Personal Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('guru.update-profile') }}" method="POST" enctype="multipart/form-data"
                        id="formEditProfile">
                        @csrf
                        <div class="row">
                            <!-- Di bagian foto profil (ganti bagian ini) -->
                            <div class="col-md-3 text-center">
                                <div class="profile-photo-section">
                                    <div id="previewFotoContainer">
                                        @if (!empty($guru?->foto_guru))
                                            <img src="{{ Storage::url($guru->foto_guru) }}" alt="Foto Profil"
                                                class="profile-photo" width="200" height="200" id="previewFoto"
                                                style="width: 200px; height: 200px; border-radius: 50%; object-fit: cover; border: 4px solid #e3e6f0;"
                                                onerror="this.onerror=null; this.src='{{ asset('images/user.jpg') }}'; this.style.borderRadius='50%'; this.style.objectFit='cover'; this.style.border='4px solid #e3e6f0';">
                                        @else
                                            <div id="photoPlaceholder" class="profile-photo-placeholder">
                                                <img src="{{ asset('images/user.jpg') }}" alt="Foto Profil"
                                                    class="profile-photo" width="200" height="200" id="previewFoto">
                                            </div>
                                        @endif
                                    </div>

                                    <input type="file" name="foto_guru" id="foto_guru"
                                        accept="image/jpeg,image/png,image/gif,image/jpg" style="display: none;">

                                    <p class="mt-2 text-muted">Foto Profil</p>

                                    <!-- File info display -->
                                    <div id="fileInfo" class="mt-2" style="display: none;">
                                        <div class="alert alert-info p-2 mb-2">
                                            <small>
                                                <i class="fas fa-info-circle"></i>
                                                <span id="fileName">File dipilih</span>
                                            </small>
                                            <br>
                                            <small class="text-muted">
                                                Klik "Simpan Perubahan" untuk mengupload
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Progress indicator -->
                                    <div id="uploadProgress" class="mt-2" style="display: none;">
                                        <div class="mb-2">
                                            <small class="text-info">
                                                <i class="fas fa-spinner fa-spin"></i> Mengupload foto...
                                            </small>
                                        </div>
                                        <div class="progress">
                                            <div id="uploadProgressBar"
                                                class="progress-bar progress-bar-striped progress-bar-animated"
                                                role="progressbar" style="width: 0%">0%</div>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-sm btn-primary mt-2" id="btnChangePhoto">
                                        <i class="fas fa-camera"></i> Ubah Foto
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nama_guru" class="form-label">Nama Lengkap</label>
                                            <input type="text" class="form-control" name="nama_guru" id="nama_guru"
                                                value="{{ $guru->nama_guru }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="kode_guru" class="form-label">Kode Guru</label>
                                            <input type="text" class="form-control" id="kode_guru"
                                                value="{{ $guru->kode_guru }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" name="email" id="email"
                                                value="{{ $email ?? ($guru->userGuru->email ?? '') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="no_telepon" class="form-label">No. Telepon</label>
                                            <input type="tel" class="form-control" name="no_telepon" id="no_telepon"
                                                value="{{ $guru->no_telepon ?? '' }}" placeholder="Contoh: 081234567890">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status" class="form-label">Status</label>
                                            <input type="text" class="form-control text-success" id="status"
                                                value="Guru Aktif" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="total_kelas" class="form-label">Total Kelas</label>
                                            <input type="text" class="form-control" id="total_kelas"
                                                value="{{ $guru->tugasMengajar->count() }} Kelas" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="terakhir_login" class="form-label">Terakhir Login</label>
                                            <input type="text" class="form-control" id="terakhir_login"
                                                value="{{ now()->format('d/m/Y H:i') }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tanggal_bergabung" class="form-label">Tanggal Bergabung</label>
                                            <input type="text" class="form-control" id="tanggal_bergabung"
                                                value="{{ $guru->created_at->format('d/m/Y') }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Simpan Perubahan
                                    </button>
                                    <a href="{{ route('guru.dashboard') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Batal
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card dashboard-card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-tachometer-alt"></i>
                        DASHBOARD GURU
                    </h5>
                </div>
                <div class="card-body">
                    <div class="dashboard-content">
                        <div class="welcome-message">
                            <p class="mb-4">Anda login sebagai guru dengan kode: <strong>{{ $guru->kode_guru }}</strong>
                            </p>
                        </div>

                        <div class="action-buttons">
                            <a href="{{ route('page-tugas') }}" class="btn btn-action btn-primary">
                                <div class="btn-icon">
                                    <i class="fas fa-tasks"></i>
                                </div>
                                <div class="btn-content">
                                    <span class="btn-title">Lihat Tugas</span>
                                    <span class="btn-subtitle">Kelola dan validasi tugas siswa</span>
                                </div>
                            </a>

                            <a href="{{ route('page-kelas') }}" class="btn btn-action btn-success">
                                <div class="btn-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="btn-content">
                                    <span class="btn-title">Lihat Kelas</span>
                                    <span class="btn-subtitle">Akses detail kelas dan siswa</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        /* Welcome Header */
        .welcome-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .welcome-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #007bff;
            text-shadow: none;
        }

        .welcome-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 0;
        }

        .date-widget {
            background: rgba(255, 255, 255, 0.2);
            padding: 1rem 1.5rem;
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }

        .date-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
        }

        .date-container i {
            font-size: 1.2rem;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid #dee2e6;
            padding: 1.25rem 1.5rem;
        }

        .card-title {
            font-weight: 700;
            font-size: 1.1rem;
            color: #495057;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-title i {
            color: #6c757d;
        }



        /* Main Content */
        .main-content {
            padding: 2rem;
        }

        .content-layout {
            display: block;
        }

        /* Personal Information Card */
        .card {
            border: 1px solid #e3e6f0;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            padding: 0.75rem 1.25rem;
        }

        .card-title {
            color: #5a5c69;
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
        }

        .card-title i {
            margin-right: 0.5rem;
            color: #5a5c69;
        }

        .card-body {
            padding: 1.25rem;
        }

        .form-label {
            font-weight: 600;
            color: #5a5c69;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-control {
            border: 1px solid #d1d3e2;
            border-radius: 0.35rem;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            background-color: #f8f9fc;
        }

        .form-control:focus {
            border-color: #bac8f3;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
            background-color: #fff;
        }

        .profile-photo-section {
            margin-bottom: 1rem;
        }

        .profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            /* Ini yang membuat circular */
            object-fit: cover;
            /* Agar gambar tidak terdistorsi */
            border: 4px solid #e3e6f0;
            display: block;
            /* Pastikan display block */
            margin: 0 auto;
            /* Center alignment */
        }

        .profile-photo-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: #f8f9fc;
            border: 4px solid #e3e6f0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .profile-photo-placeholder i {
            font-size: 3rem;
            color: #5a5c69;
        }

        #btnChangePhoto {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border: none;
            color: white;
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 123, 255, 0.3);
            cursor: pointer;
            pointer-events: auto;
            z-index: 10;
            position: relative;
        }

        #btnChangePhoto:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.4);
            background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
        }

        #btnChangePhoto:active {
            transform: translateY(0);
        }

        #btnChangePhoto i {
            margin-right: 0.5rem;
        }

        #fileNameDisplay {
            margin-top: 0.5rem;
            padding: 0.5rem;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 3px solid #28a745;
        }

        #fileNameDisplay small {
            display: block;
            word-break: break-word;
        }

        #fileNameDisplay i {
            margin-right: 0.25rem;
        }



        /* File Input */
        .file-input-container {
            margin-bottom: 1.5rem;
        }

        .file-input {
            display: none;
        }

        .file-input-label {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            text-decoration: none;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        }

        .file-input-label:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
        }

        .file-input-label i {
            margin-right: 0.5rem;
        }

        .file-info {
            margin-top: 0.5rem;
            color: #6c757d;
            font-size: 0.875rem;
        }

        .btn-upload {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .btn-upload:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
            color: white;
        }

        /* Info Card */
        .info-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .info-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .info-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            display: block;
            font-size: 0.875rem;
            color: #6c757d;
            margin-bottom: 0.25rem;
            font-weight: 600;
        }

        .info-value {
            font-size: 1rem;
            color: #495057;
            font-weight: 500;
        }

        .status-badge {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        /* Dashboard Card */
        .dashboard-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        }

        .welcome-message {
            text-align: center;
            margin-bottom: 2rem;
        }

        .welcome-message p {
            font-size: 1.1rem;
            color: #495057;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .btn-action {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            border-radius: 15px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-action:hover {
            transform: translateY(-5px);
            text-decoration: none;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .btn-action.btn-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
        }

        .btn-action.btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .btn-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .btn-content {
            flex: 1;
        }

        .btn-title {
            display: block;
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .btn-subtitle {
            display: block;
            font-size: 0.875rem;
            opacity: 0.9;
        }

        /* Alerts */
        .custom-alert {
            border: none;
            border-radius: 10px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .alert-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-content i {
            font-size: 1.25rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .welcome-title {
                font-size: 2rem;
            }

            .welcome-header {
                padding: 1.5rem;
            }

            .profile-photo,
            .profile-photo-placeholder {
                width: 120px;
                height: 120px;
            }

            .profile-name {
                font-size: 1.5rem;
            }

            .profile-actions {
                flex-direction: column;
                align-items: center;
            }

            .content-layout {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .sidebar {
                order: 2;
            }

            .content-area {
                order: 1;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .biodata-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
                padding: 1rem;
            }

            .action-buttons {
                grid-template-columns: 1fr;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .cover-section {
                height: 250px;
            }

            .profile-photo-section {
                margin-top: -60px;
            }

            .profile-photo,
            .profile-photo-placeholder {
                width: 100px;
                height: 100px;
            }

            .main-content {
                padding: 1rem;
            }

            .content-area {
                padding: 1.5rem;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        (function() {
            'use strict';
            console.log('📜 Script dashboard-guru dimuat');

            // Fungsi utama untuk mengatur upload foto
            function initializePhotoUpload() {
                console.log('🚀 Initializing photo upload...');

                const fileInput = document.getElementById('foto_guru');
                const btnChangePhoto = document.getElementById('btnChangePhoto');
                const fileInfo = document.getElementById('fileInfo');
                const fileName = document.getElementById('fileName');
                const uploadProgress = document.getElementById('uploadProgress');
                const uploadProgressBar = document.getElementById('uploadProgressBar');
                const formEditProfile = document.getElementById('formEditProfile');

                // Pastikan semua elemen ditemukan
                if (!fileInput || !btnChangePhoto) {
                    console.error('❌ Elemen penting tidak ditemukan');
                    return;
                }

                console.log('✅ Semua elemen ditemukan');

                // 1. Setup tombol untuk memilih file
                btnChangePhoto.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('🖱️ Tombol "Ubah Foto" diklik');
                    fileInput.click();
                });

                // 2. Setup perubahan file input
                fileInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (!file) {
                        console.log('⚠️ Tidak ada file yang dipilih');
                        return;
                    }

                    console.log('📁 File dipilih:', {
                        name: file.name,
                        type: file.type,
                        size: file.size
                    });

                    // Validasi file
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                    const maxSize = 2 * 1024 * 1024; // 2MB

                    if (!allowedTypes.includes(file.type.toLowerCase())) {
                        if (typeof showToast === 'function') { showToast('warning', 'Format file tidak didukung. Gunakan JPG, PNG, atau GIF.', 'Peringatan'); } else { alert('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.'); }
                        this.value = '';
                        return;
                    }

                    if (file.size > maxSize) {
                        if (typeof showToast === 'function') { showToast('warning', 'Ukuran file terlalu besar. Maksimal 2MB.', 'Peringatan'); } else { alert('Ukuran file terlalu besar. Maksimal 2MB.'); }
                        this.value = '';
                        return;
                    }

                    // Tampilkan info file
                    if (fileName) {
                        fileName.textContent = file.name;
                    }
                    if (fileInfo) {
                        fileInfo.style.display = 'block';
                    }

                    // Tampilkan preview
                    displayImagePreview(file);
                });

                // 3. Fungsi untuk menampilkan preview gambar
                function displayImagePreview(file) {
                    console.log('🖼️ Menampilkan preview untuk file:', file.name);

                    const reader = new FileReader();

                    reader.onload = function(e) {
                        console.log('✅ File berhasil dibaca untuk preview');

                        // Cari atau buat elemen preview
                        let previewImg = document.getElementById('previewFoto');
                        const previewContainer = document.getElementById('previewFotoContainer');
                        const placeholder = document.getElementById('photoPlaceholder');

                        if (!previewImg) {
                            console.log('🆕 Membuat elemen gambar baru untuk preview');
                            previewImg = document.createElement('img');
                            previewImg.id = 'previewFoto';
                            previewImg.className = 'profile-photo';
                            previewImg.alt = 'Foto Profil Preview';
                            previewImg.width = 200;
                            previewImg.height = 200;
                        }

                        // Hapus placeholder jika ada
                        if (placeholder) {
                            placeholder.remove();
                        }

                        // Set atribut gambar
                        previewImg.src = e.target.result;
                        previewImg.style.display = 'block';
                        previewImg.style.borderRadius = '50%';
                        previewImg.style.objectFit = 'cover';
                        previewImg.style.border = '4px solid #28a745';
                        previewImg.style.boxShadow = '0 0 15px rgba(40, 167, 69, 0.5)';

                        // Pastikan gambar ada di container
                        if (previewContainer && !previewContainer.contains(previewImg)) {
                            previewContainer.innerHTML = '';
                            previewContainer.appendChild(previewImg);
                        }

                        console.log('✅ Preview berhasil ditampilkan');

                        // Simpan file untuk diupload nanti
                        window.selectedPhotoFile = file;
                    };

                    reader.onerror = function() {
                        console.error('❌ Gagal membaca file untuk preview');
                        if (typeof showToast === 'function') { showToast('error', 'Gagal membaca file. Silakan coba file lain.', 'Gagal'); } else { alert('Gagal membaca file. Silakan coba file lain.'); }
                    };

                    reader.readAsDataURL(file);
                }

                // 4. Setup form submission
                if (formEditProfile) {
                    formEditProfile.addEventListener('submit', async function(e) {
                        e.preventDefault();

                        console.log('📤 Form submission dimulai');

                        // Cek apakah ada foto baru
                        if (window.selectedPhotoFile) {
                            try {
                                // Upload foto terlebih dahulu
                                console.log('📤 Mengupload foto baru...');
                                const uploadedUrl = await uploadPhotoToServer(window.selectedPhotoFile);

                                // Set nilai file input dengan file yang sudah diupload
                                const dataTransfer = new DataTransfer();
                                dataTransfer.items.add(window.selectedPhotoFile);
                                fileInput.files = dataTransfer.files;

                                // Lanjutkan submit form
                                console.log('✅ Foto berhasil diupload, melanjutkan submit form...');
                                this.submit();

                            } catch (error) {
                                console.error('❌ Gagal mengupload foto:', error);
                                if (typeof showToast === 'function') { showToast('error', 'Gagal mengupload foto. Silakan coba lagi.', 'Gagal'); } else { alert('Gagal mengupload foto. Silakan coba lagi.'); }
                            }
                        } else {
                            console.log('ℹ️ Tidak ada foto baru, langsung submit form');
                            this.submit();
                        }
                    });
                }

                // 5. Fungsi untuk upload ke server
                async function uploadPhotoToServer(file) {
                    return new Promise((resolve, reject) => {
                        console.log('📤 Mulai upload ke server...');

                        // Tampilkan progress
                        if (uploadProgress) {
                            uploadProgress.style.display = 'block';
                        }

                        const formData = new FormData();
                        formData.append('foto_guru', file);
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]')
                            .content);

                        const xhr = new XMLHttpRequest();

                        // Track progress
                        xhr.upload.addEventListener('progress', function(e) {
                            if (e.lengthComputable && uploadProgressBar) {
                                const percent = Math.round((e.loaded / e.total) * 100);
                                uploadProgressBar.style.width = percent + '%';
                                uploadProgressBar.textContent = percent + '%';
                                console.log(`📊 Upload progress: ${percent}%`);
                            }
                        });

                        xhr.onload = function() {
                            if (uploadProgress) {
                                uploadProgress.style.display = 'none';
                            }

                            if (xhr.status === 200) {
                                try {
                                    const response = JSON.parse(xhr.responseText);
                                    if (response.success) {
                                        console.log('✅ Upload berhasil:', response.message);
                                        resolve(response.photo_url);
                                    } else {
                                        console.error('❌ Upload gagal:', response.message);
                                        reject(new Error(response.message));
                                    }
                                } catch (error) {
                                    console.error('❌ Error parsing response:', error);
                                    reject(error);
                                }
                            } else {
                                console.error('❌ Upload gagal dengan status:', xhr.status);
                                reject(new Error(`HTTP ${xhr.status}`));
                            }
                        };

                        xhr.onerror = function() {
                            if (uploadProgress) {
                                uploadProgress.style.display = 'none';
                            }
                            console.error('❌ Network error saat upload');
                            reject(new Error('Network error'));
                        };

                        xhr.open('POST', '{{ route('guru.update-photo') }}');
                        xhr.send(formData);
                    });
                }
            }

            // Jalankan inisialisasi saat DOM siap
            document.addEventListener('DOMContentLoaded', function() {
                console.log('✅ DOM siap, initializing photo upload...');
                initializePhotoUpload();
            });

            // Fallback jika DOMContentLoaded sudah terlewati
            if (document.readyState === 'complete' || document.readyState === 'interactive') {
                console.log('⚠️ DOMContentLoaded mungkin terlewat, jalankan langsung...');
                setTimeout(initializePhotoUpload, 100);
            }

        })();
    </script>
@endsection
