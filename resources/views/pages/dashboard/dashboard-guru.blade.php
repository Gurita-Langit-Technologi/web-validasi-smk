@extends('layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
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
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <div class="profile-photo-section">
                                @if ($guru->foto_guru)
                                    <img src="{{ asset('images/' . $guru->foto_guru) }}" alt="Foto Profil"
                                        class="profile-photo" width="200" height="200">
                                @else
                                    <div class="profile-photo-placeholder">
                                        <img src="{{ asset('images/user.jpg') }}" alt="Foto Profil" class="profile-photo"
                                            width="200" height="200">
                                    </div>
                                @endif
                                <p class="mt-2 text-muted">Foto Profil</p>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" value="{{ $guru->nama_guru }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Kode Guru</label>
                                        <input type="text" class="form-control" value="{{ $guru->kode_guru }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control"
                                            value="{{ $guru->email ?? 'guru@smk.com' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">No. Telepon</label>
                                        <input type="tel" class="form-control" value="+62 123 456 789" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Status</label>
                                        <input type="text" class="form-control" value="Guru Aktif" readonly>
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
                                        <input type="text" class="form-control" value="{{ now()->format('d/m/Y H:i') }}"
                                            readonly>
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
                        </div>
                    </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Preview foto sebelum upload
            const fileInput = document.getElementById('foto_guru');
            const uploadForm = document.getElementById('uploadForm');
            const btnEdit = document.querySelector('.btn-edit');
            const btnSave = document.querySelector('.btn-save');

            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Validasi ukuran file
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran file terlalu besar. Maksimal 2MB.');
                        this.value = '';
                        return;
                    }

                    // Validasi tipe file
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                    if (!allowedTypes.includes(file.type)) {
                        alert('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
                        this.value = '';
                        return;
                    }

                    // Preview gambar
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.querySelector('.profile-photo');
                        const placeholder = document.querySelector('.profile-photo-placeholder');

                        if (img) {
                            img.src = e.target.result;
                        } else if (placeholder) {
                            placeholder.innerHTML = '<img src="' + e.target.result +
                                '" class="profile-photo" alt="Preview">';
                        }
                    };
                    reader.readAsDataURL(file);

                    // Show save button
                    btnSave.style.display = 'flex';
                }
            });

            // Click pada foto untuk trigger file input
            const photoContainer = document.querySelector('.profile-photo-container');
            if (photoContainer) {
                photoContainer.addEventListener('click', function() {
                    fileInput.click();
                });
            }

            // Form submission dengan loading state
            uploadForm.addEventListener('submit', function() {
                btnSave.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                btnSave.disabled = true;
            });

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
        });
    </script>
@endsection
