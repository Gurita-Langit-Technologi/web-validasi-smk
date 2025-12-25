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
                            <div class="col-md-3 text-center">
                                <div class="profile-photo-section">
                                    @if ($guru->foto_guru)
                                        <img src="{{ Storage::url($guru->foto_guru) }}" alt="Foto Profil"
                                            class="profile-photo" width="200" height="200" id="previewFoto"
                                            onerror="this.onerror=null;">
                                    @else
                                        <div class="profile-photo-placeholder">
                                            <img src="{{ asset('images/user.jpg') }}" alt="Foto Profil"
                                                class="profile-photo" width="200" height="200" id="previewFoto">
                                        </div>
                                    @endif
                                    <input type="file" name="foto_guru" id="foto_guru"
                                        accept="image/jpeg,image/png,image/gif" style="display: none;">
                                    <p class="mt-2 text-muted">Foto Profil (Klik untuk ubah)</p>
                                    <div id="fileNameDisplay" class="mt-2" style="display: none;">
                                        <small class="text-success">
                                            <i class="fas fa-check-circle"></i> <span id="fileNameText"></span>
                                        </small>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary mt-2" id="btnChangePhoto">
                                        <i class="fas fa-camera"></i> Ubah Foto
                                    </button>
                                    <script>
                                        // Inline script untuk memastikan tombol berfungsi - HANYA sebagai fallback
                                        (function() {
                                            console.log('🔧 Inline script untuk tombol foto dimuat');

                                            // Tunggu sebentar untuk memastikan setupPhotoUpload tidak akan dipanggil
                                            setTimeout(function() {
                                                const btn = document.getElementById('btnChangePhoto');
                                                const fileInput = document.getElementById('foto_guru');

                                                // Cek apakah button sudah punya event listener
                                                // Jika belum ada handler, baru tambahkan
                                                if (btn && fileInput && !btn.hasAttribute('data-handler-attached')) {
                                                    console.log('🔧 Menambahkan fallback handler untuk tombol');
                                                    btn.setAttribute('data-handler-attached', 'true');

                                                    btn.addEventListener('click', function(e) {
                                                        e.preventDefault();
                                                        e.stopPropagation();
                                                        console.log('🖱️ Tombol diklik (fallback inline)');
                                                        fileInput.click();
                                                    });
                                                    console.log('✅ Fallback event listener tombol terpasang');
                                                } else {
                                                    console.log('ℹ️ Button sudah punya handler, skip inline script');
                                                }
                                            }, 500); // Tunggu 500ms untuk memastikan setupPhotoUpload selesai
                                        })();
                                    </script>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Nama Lengkap</label>
                                            <input type="text" class="form-control" name="nama_guru"
                                                value="{{ $guru->nama_guru }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Kode Guru</label>
                                            <input type="text" class="form-control" value="{{ $guru->kode_guru }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" name="email"
                                                value="{{ $email ?? ($guru->userGuru->email ?? '') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">No. Telepon</label>
                                            <input type="tel" class="form-control" name="no_telepon"
                                                value="{{ $guru->no_telepon ?? '' }}" placeholder="Contoh: 081234567890">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Status</label>
                                            <input type="text" class="form-control text-success" value="Guru Aktif"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Total Kelas</label>
                                            <input type="text" class="form-control"
                                                value="{{ $guru->tugasMengajar->count() }} Kelas" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Terakhir Login</label>
                                            <input type="text" class="form-control"
                                                value="{{ now()->format('d/m/Y H:i') }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Tanggal Bergabung</label>
                                            <input type="text" class="form-control"
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
            object-fit: cover;
            border: 4px solid #e3e6f0;
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
        // Log untuk memastikan script dimuat - HARUS muncul pertama
        // Script ini HARUS dieksekusi langsung tanpa menunggu DOMContentLoaded
        (function() {
                'use strict';
                console.log('📜 ========================================');
                console.log('📜 Script dashboard-guru.blade.php DIMUAT');
                console.log('📜 ========================================');
                console.log('📜 Timestamp:', new Date().toISOString());
                console.log('📜 Document ready state:', document.readyState);
                console.log('📜 Location:', window.location.href);

                // Fungsi utama untuk setup upload foto
                function setupPhotoUpload() {
                    // Cek apakah sudah diinisialisasi untuk mencegah duplikasi
                    if (window.photoUploadInitialized) {
                        console.log('⚠️ Photo upload sudah diinisialisasi, skip setupPhotoUpload');
                        return;
                    }
                    window.photoUploadInitialized = true;

                    console.log('🚀 ========================================');
                    console.log('🚀 Setup photo upload DIMULAI');
                    console.log('🚀 ========================================');

                    const fileInput = document.getElementById('foto_guru');
                    let previewFoto = document.getElementById('previewFoto');
                    const btnChangePhoto = document.getElementById('btnChangePhoto');
                    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
                    const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : null;

                    // Debug: Cek elemen yang ditemukan
                    console.log('File Input:', fileInput ? '✅ Ditemukan' : '❌ Tidak ditemukan');
                    console.log('Preview Foto:', previewFoto ? '✅ Ditemukan' : '❌ Tidak ditemukan');
                    console.log('Button Change Photo:', btnChangePhoto ? '✅ Ditemukan' : '❌ Tidak ditemukan');
                    console.log('CSRF Token:', csrfToken ? '✅ Ditemukan' : '❌ Tidak ditemukan');

                    if (!fileInput) {
                        console.error('❌ File input dengan ID "foto_guru" tidak ditemukan!');
                        return;
                    }

                    // Cari preview foto (bisa di dalam placeholder atau langsung)
                    if (!previewFoto) {
                        // Coba cari di dalam placeholder
                        const placeholder = document.querySelector('.profile-photo-placeholder');
                        if (placeholder) {
                            previewFoto = placeholder.querySelector('img');
                            console.log('📍 Preview ditemukan di dalam placeholder');
                        }
                    }

                    if (!previewFoto) {
                        console.error('❌ Preview foto dengan ID "previewFoto" tidak ditemukan!');
                        console.error('📍 Mencoba mencari semua img di halaman...');
                        const allImages = document.querySelectorAll('img');
                        console.log('📍 Total gambar ditemukan:', allImages.length);
                        allImages.forEach((img, index) => {
                            console.log(`📍 Image ${index}:`, img.id, img.src);
                        });
                        // Jangan return, tetap lanjutkan karena mungkin akan dibuat nanti
                    } else {
                        // Simpan URL foto asli untuk fallback
                        previewFoto.dataset.originalSrc = previewFoto.src;
                        console.log('📸 URL foto asli disimpan:', previewFoto.src);
                        console.log('📍 Preview element:', previewFoto);
                    }

                    // Click pada button untuk trigger file input
                    // Hapus event listener lama jika ada untuk mencegah duplikasi
                    if (btnChangePhoto) {
                        // Hapus onclick dari HTML jika ada
                        if (btnChangePhoto.hasAttribute('onclick')) {
                            btnChangePhoto.removeAttribute('onclick');
                            console.log('🗑️ Onclick attribute dihapus dari HTML');
                        }

                        // Clone button untuk menghapus semua event listener lama
                        const newBtn = btnChangePhoto.cloneNode(true);
                        btnChangePhoto.parentNode.replaceChild(newBtn, btnChangePhoto);
                        const btnChangePhotoNew = document.getElementById('btnChangePhoto');

                        // Pasang event listener baru (hanya sekali)
                        btnChangePhotoNew.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            console.log('🖱️ Tombol "Ubah Foto" diklik (setupPhotoUpload)');

                            const currentFileInput = document.getElementById('foto_guru');
                            if (currentFileInput) {
                                console.log('📂 Membuka file picker...');
                                currentFileInput.click();
                            } else {
                                console.error('❌ File input tidak ditemukan saat tombol diklik!');
                            }
                        }, {
                            once: false
                        });

                        // Tandai bahwa handler sudah terpasang
                        btnChangePhotoNew.setAttribute('data-handler-attached', 'true');
                        console.log('✅ Event listener untuk tombol terpasang (setupPhotoUpload)');
                    } else {
                        console.error('❌ Tombol btnChangePhoto tidak ditemukan!');
                    }

                    // Upload foto saat file dipilih
                    // Hapus event listener lama jika ada untuk mencegah duplikasi
                    // Clone file input untuk menghapus semua event listener lama
                    const newFileInput = fileInput.cloneNode(true);
                    fileInput.parentNode.replaceChild(newFileInput, fileInput);
                    const fileInputNew = document.getElementById('foto_guru');

                    // Tandai bahwa handler sudah terpasang
                    if (fileInputNew) {
                        fileInputNew.setAttribute('data-handler-attached', 'true');
                    }

                    fileInputNew.addEventListener('change', function(e) {
                        console.log('📁 File dipilih, memproses...');

                        const file = e.target.files[0];
                        if (!file) {
                            console.warn('⚠️ Tidak ada file yang dipilih');
                            return;
                        }

                        console.log('📄 File info:', {
                            name: file.name,
                            size: file.size,
                            type: file.type
                        });

                        // Validasi ukuran file
                        if (file.size > 2 * 1024 * 1024) {
                            alert('Ukuran file terlalu besar. Maksimal 2MB.');
                            console.error('❌ File terlalu besar:', file.size, 'bytes');
                            this.value = '';
                            hideFileName();
                            return;
                        }

                        // Validasi tipe file
                        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                        if (!allowedTypes.includes(file.type)) {
                            alert('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
                            console.error('❌ Format file tidak didukung:', file.type);
                            this.value = '';
                            hideFileName();
                            return;
                        }

                        console.log('✅ Validasi file berhasil');

                        // Tampilkan nama file
                        showFileName(file.name);
                        console.log('📝 Nama file ditampilkan:', file.name);

                        // Preview gambar sementara - HARUS langsung update
                        let previewImg = document.getElementById('previewFoto');

                        // Jika tidak ditemukan, coba cari di dalam placeholder
                        if (!previewImg) {
                            const placeholder = document.querySelector('.profile-photo-placeholder');
                            if (placeholder) {
                                previewImg = placeholder.querySelector('img');
                            }
                        }

                        if (!previewImg) {
                            console.error('❌ Preview image tidak ditemukan!');
                            hideFileName();
                            return;
                        }

                        console.log('🖼️ Membaca file untuk preview...');
                        console.log('📍 Preview element ditemukan:', previewImg);
                        console.log('📍 Current src:', previewImg.src);

                        const reader = new FileReader();

                        reader.onload = function(e) {
                            console.log('✅ File berhasil dibaca, mengupdate preview...');
                            console.log('📸 Data URL length:', e.target.result.length);

                            // Hapus placeholder wrapper jika ada (SEBELUM update src)
                            const placeholder = previewImg.closest('.profile-photo-placeholder');
                            if (placeholder && placeholder.parentElement) {
                                console.log('🗑️ Menghapus placeholder wrapper...');
                                // Pindahkan img keluar dari placeholder
                                const imgClone = previewImg.cloneNode(true);
                                placeholder.parentElement.insertBefore(imgClone, placeholder);
                                placeholder.remove();
                                // Update reference ke img yang baru
                                previewImg = imgClone;
                                // Update ID untuk memastikan bisa ditemukan lagi
                                previewImg.id = 'previewFoto';
                            }

                            // Update src gambar - INI YANG PENTING
                            const newSrc = e.target.result;
                            previewImg.src = newSrc;
                            previewImg.setAttribute('src', newSrc); // Force update
                            console.log('🖼️ Preview gambar diupdate dengan src baru');
                            console.log('📍 New src length:', newSrc.length);

                            // Pastikan gambar terlihat dan styling benar
                            previewImg.style.display = 'block';
                            previewImg.style.visibility = 'visible';
                            previewImg.style.width = '200px';
                            previewImg.style.height = '200px';
                            previewImg.style.borderRadius = '50%';
                            previewImg.style.objectFit = 'cover';
                            previewImg.style.border = '4px solid #e3e6f0';

                            // Force browser untuk reload gambar
                            previewImg.onload = function() {
                                console.log('✅ Gambar berhasil dimuat di preview');
                                console.log('📍 Final src:', previewImg.src);
                            };

                            // Trigger load event jika belum ter-trigger
                            previewImg.onerror = function() {
                                console.error('❌ Error memuat gambar di preview');
                            };

                            console.log('✅ Preview gambar berhasil diupdate');
                        };

                        reader.onerror = function(error) {
                            console.error('❌ Error membaca file:', error);
                            hideFileName();
                            alert('Terjadi kesalahan saat membaca file. Silakan coba lagi.');
                        };

                        // Baca file sebagai data URL
                        console.log('📖 Membaca file sebagai data URL...');
                        reader.readAsDataURL(file);

                        // Upload ke server
                        console.log('☁️ Memulai upload ke server...');
                        uploadPhoto(file);
                    });

                    console.log('✅ Event listener untuk file input terpasang (setupPhotoUpload)');

                    // Fungsi untuk menampilkan nama file
                    function showFileName(fileName) {
                        const fileNameDisplay = document.getElementById('fileNameDisplay');
                        const fileNameText = document.getElementById('fileNameText');
                        if (fileNameDisplay && fileNameText) {
                            fileNameText.textContent = fileName;
                            fileNameDisplay.style.display = 'block';
                        }
                    }

                    // Fungsi untuk menyembunyikan nama file
                    function hideFileName() {
                        const fileNameDisplay = document.getElementById('fileNameDisplay');
                        if (fileNameDisplay) {
                            fileNameDisplay.style.display = 'none';
                        }
                    }

                    // Fungsi untuk upload foto ke server
                    function uploadPhoto(file) {
                        console.log('📤 Memulai upload foto:', file.name);

                        if (!csrfToken) {
                            console.error('❌ CSRF token tidak ditemukan!');
                            alert('Terjadi kesalahan. Silakan refresh halaman dan coba lagi.');
                            return;
                        }

                        // Disable button saat upload
                        const currentBtn = document.getElementById('btnChangePhoto');
                        if (currentBtn) {
                            currentBtn.disabled = true;
                            currentBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengupload...';
                            console.log('🔄 Button disabled, menampilkan loading...');
                        }

                        // Buat FormData
                        const formData = new FormData();
                        formData.append('foto_guru', file);
                        formData.append('_token', csrfToken);

                        console.log('📦 FormData dibuat, mengirim ke server...');

                        // Kirim request ke server
                        const uploadUrl = '{{ route('guru.update-photo') }}';
                        console.log('🌐 Mengirim request ke:', uploadUrl);

                        fetch(uploadUrl, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            })
                            .then(response => {
                                console.log('📡 Response diterima, status:', response.status);
                                if (!response.ok) {
                                    throw new Error(`HTTP error! status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                console.log('📦 Data diterima:', data);

                                // Cari preview image (untuk digunakan di semua blok)
                                let previewImg = document.getElementById('previewFoto');
                                if (!previewImg) {
                                    const placeholder = document.querySelector('.profile-photo-placeholder');
                                    if (placeholder) {
                                        previewImg = placeholder.querySelector('img');
                                    }
                                }

                                if (data.success) {
                                    console.log('✅ Upload berhasil!');

                                    if (previewImg) {
                                        const newUrl = data.photo_url + '?t=' + new Date().getTime();
                                        console.log('🖼️ Mengupdate preview dengan URL dari server:', newUrl);

                                        // Hapus placeholder wrapper jika masih ada
                                        const placeholder = previewImg.closest('.profile-photo-placeholder');
                                        if (placeholder && placeholder.parentElement) {
                                            const imgClone = previewImg.cloneNode(true);
                                            placeholder.parentElement.insertBefore(imgClone, placeholder);
                                            placeholder.remove();
                                            previewImg = imgClone;
                                            previewImg.id = 'previewFoto';
                                        }

                                        // Update src dengan URL dari server
                                        previewImg.src = newUrl;
                                        previewImg.setAttribute('src', newUrl);

                                        // Pastikan styling benar
                                        previewImg.style.display = 'block';
                                        previewImg.style.visibility = 'visible';
                                        previewImg.style.width = '200px';
                                        previewImg.style.height = '200px';
                                        previewImg.style.borderRadius = '50%';
                                        previewImg.style.objectFit = 'cover';
                                        previewImg.style.border = '4px solid #e3e6f0';

                                        // Update originalSrc untuk fallback
                                        previewImg.dataset.originalSrc = data.photo_url;

                                        console.log('✅ Preview diupdate dengan URL server');
                                        console.log('✅ Preview berhasil diupdate');
                                    }

                                    // Tampilkan pesan sukses
                                    showAlert('success', data.message);
                                    console.log('✅ Upload selesai dengan sukses');
                                    // Nama file tetap ditampilkan (sudah ditampilkan sebelumnya)
                                } else {
                                    console.error('❌ Upload gagal:', data.message);
                                    // Tampilkan pesan error
                                    showAlert('danger', data.message);
                                    // Reset preview ke foto sebelumnya
                                    if (previewImg) {
                                        const originalSrc = previewImg.dataset.originalSrc ||
                                            '{{ $guru->foto_guru ? Storage::url($guru->foto_guru) : asset('images/user.jpg') }}';
                                        previewImg.src = originalSrc;
                                    }
                                    // Sembunyikan nama file jika error
                                    hideFileName();
                                }
                            })
                            .catch(error => {
                                console.error('❌ Error saat upload:', error);
                                console.error('Error details:', {
                                    message: error.message,
                                    stack: error.stack
                                });
                                showAlert('danger', 'Terjadi kesalahan saat mengupload foto. Silakan coba lagi.');
                                // Reset preview
                                const previewImg = document.getElementById('previewFoto');
                                if (previewImg) {
                                    const originalSrc = previewImg.dataset.originalSrc ||
                                        '{{ $guru->foto_guru ? Storage::url($guru->foto_guru) : asset('images/user.jpg') }}';
                                    previewImg.src = originalSrc;
                                }
                                // Sembunyikan nama file jika error
                                hideFileName();
                            })
                            .finally(() => {
                                console.log('🏁 Upload process selesai, resetting UI...');
                                // Enable button kembali
                                const currentBtn = document.getElementById('btnChangePhoto');
                                if (currentBtn) {
                                    currentBtn.disabled = false;
                                    currentBtn.innerHTML = '<i class="fas fa-camera"></i> Ubah Foto';
                                    console.log('✅ Button di-enable kembali');
                                }
                                // Reset file input
                                const currentFileInput = document.getElementById('foto_guru');
                                if (currentFileInput) {
                                    currentFileInput.value = '';
                                    console.log('🗑️ File input di-reset');
                                }
                            });
                    }

                    // Fungsi untuk menampilkan alert
                    function showAlert(type, message) {
                        // Hapus alert yang sudah ada
                        const existingAlert = document.querySelector('.custom-alert');
                        if (existingAlert) {
                            existingAlert.remove();
                        }

                        // Buat alert baru
                        const alertDiv = document.createElement('div');
                        alertDiv.className = `alert alert-${type} alert-dismissible fade show custom-alert`;
                        alertDiv.setAttribute('role', 'alert');
                        alertDiv.innerHTML = `
                    <div class="alert-content">
                        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                        <span>${message}</span>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                `;

                        // Sisipkan alert di atas card
                        const card = document.querySelector('.card');
                        if (card && card.parentElement) {
                            card.parentElement.insertBefore(alertDiv, card);
                        }

                        // Auto-hide setelah 5 detik
                        setTimeout(() => {
                            if (alertDiv.parentElement) {
                                alertDiv.remove();
                            }
                        }, 5000);
                    }

                    // Form submission dengan loading state
                    const formEditProfile = document.getElementById('formEditProfile');
                    if (formEditProfile) {
                        formEditProfile.addEventListener('submit', function(e) {
                            // Pastikan file input kosong saat submit form (karena foto sudah diupload terpisah)
                            const currentFileInput = document.getElementById('foto_guru');
                            if (currentFileInput) {
                                currentFileInput.value = '';
                            }

                            const btnSave = document.querySelector('button[type="submit"]');
                            if (btnSave) {
                                btnSave.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                                btnSave.disabled = true;
                            }
                        });
                    }

                    // Sidebar navigation
                    const navItems = document.querySelectorAll('.nav-item');
                    navItems.forEach(item => {
                        item.addEventListener('click', function(e) {
                            e.preventDefault();

                            // Remove active class from all items
                            navItems.forEach(nav => nav.classList.remove('active'));

                            // Add active class to clicked item
                            this.classList.add('active');
                        });
                    });

                    // Auto-hide alerts after 5 seconds
                    setTimeout(function() {
                        const alerts = document.querySelectorAll('.alert');
                        alerts.forEach(function(alert) {
                            if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                                const bsAlert = new bootstrap.Alert(alert);
                                bsAlert.close();
                            } else {
                                alert.style.display = 'none';
                            }
                        });
                    }, 5000);

                    // Update tanggal real-time
                    function updateDate() {
                        const now = new Date();
                        const options = {
                            day: '2-digit',
                            month: 'long',
                            year: 'numeric'
                        };
                        const dateString = now.toLocaleDateString('id-ID', options);
                        const dateElement = document.getElementById('currentDate');
                        if (dateElement) {
                            dateElement.textContent = dateString;
                        }
                    }

                    // Update tanggal setiap menit
                    updateDate();
                    setInterval(updateDate, 60000);

                    // Add hover effects to cards
                    const cards = document.querySelectorAll('.card');
                    cards.forEach(card => {
                        card.addEventListener('mouseenter', function() {
                            this.style.transform = 'translateY(-5px)';
                        });

                        card.addEventListener('mouseleave', function() {
                            this.style.transform = 'translateY(0)';
                        });
                    });

                    // Profile actions
                    const btnMessage = document.querySelector('.btn-message');
                    const btnConnect = document.querySelector('.btn-connect');

                    if (btnMessage) {
                        btnMessage.addEventListener('click', function() {
                            alert('Fitur Message akan segera tersedia!');
                        });
                    }

                    if (btnConnect) {
                        btnConnect.addEventListener('click', function() {
                            alert('Fitur Connect akan segera tersedia!');
                        });
                    }

                    // Edit cover button
                    const editCoverBtn = document.querySelector('.edit-cover-btn');
                    if (editCoverBtn) {
                        editCoverBtn.addEventListener('click', function() {
                            alert('Fitur Edit Cover akan segera tersedia!');
                        });
                    }

                    // Setup photo upload
                    setupPhotoUpload();
                });

            // Fallback jika DOMContentLoaded sudah terlewat
            console.log('🔍 Memeriksa status DOM...');
            if (document.readyState === 'loading') {
                console.log('⏳ DOM masih loading, menunggu DOMContentLoaded...');
                // Setup akan dipanggil di DOMContentLoaded
            } else {
                console.log('✅ DOM sudah siap, menjalankan setupPhotoUpload langsung...');
                console.log('⏰ Ready state:', document.readyState);
                try {
                    setupPhotoUpload();
                } catch (error) {
                    console.error('❌ Error saat setup photo upload:', error);
                }
            }

            // Juga coba setup setelah window load (fallback tambahan)
            window.addEventListener('load', function() {
                console.log('🌐 Window load event fired');
                // Cek apakah sudah di-setup
                const fileInput = document.getElementById('foto_guru');
                if (fileInput && !fileInput.hasAttribute('data-setup-done')) {
                    console.log('🔄 Setup belum dilakukan, menjalankan sekarang...');
                    try {
                        setupPhotoUpload();
                        fileInput.setAttribute('data-setup-done', 'true');
                    } catch (error) {
                        console.error('❌ Error saat setup photo upload (window load):', error);
                    }
                }
            });
        })(); // Tutup IIFE
    </script>
@endsection
