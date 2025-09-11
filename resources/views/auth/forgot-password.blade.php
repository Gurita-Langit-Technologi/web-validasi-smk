<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SMK | Forgot Password</title>
    <link rel="stylesheet" href="{{ asset('vendors/core/core.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/feather-font/css/iconfont.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/demo_1/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('images/fic.png') }}" />
</head>

<body>
    <div class="main-wrapper">
        <div class="page-wrapper full-page">
            <div class="page-content d-flex align-items-center justify-content-center">
                <div class="row w-100 mx-0 auth-page">
                    <div class="col-md-8 col-xl-6 mx-auto">
                        <div class="card">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="auth-left-wrapper"></div>
                                </div>
                                <div class="col-md-10 pl-md-0">
                                    <div class="auth-form-wrapper px-4 py-5">
                                        <a href="#" class="noble-ui-logo d-block mb-2">
                                            <img src="{{ asset('images/fic.png') }}" alt="Logo"
                                                style="height: 50px; margin-right: 5px;">
                                            SMK PGRI<span> BANYUWANGI</span>
                                        </a>

                                        <h4 class="font-weight-normal mt-4">Forgot Password</h4>
                                        @if (session('status'))
                                            <div class="alert alert-success">{{ session('status') }}</div>
                                        @endif
                                        <form action="{{ route('forgot-password') }}" method="POST">
                                            @csrf
                                            <div class="form-group mt-3">
                                                <label>Email</label>
                                                <input type="email" name="email" class="form-control" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Kirim Link Reset</button>
                                        </form>
                                        <a href="{{ route('login.guru.form') }}"
                                            class="d-block mt-4 text-dark text-success">
                                            Login sebagai <span class="text-success">Guru</span>
                                        </a>
                                        <a href="{{ route('login.wali.form') }}" class="d-block text-dark text-primary">
                                            Login sebagai <span class="text-primary">Wali Kelas</span>
                                        </a>
                                        <a href="{{ route('guru.create') }}" class="d-block mt-2 text-dark">
                                            Belum punya password? Buat di sini
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- core:js -->
    <script src="{{ asset('vendors/core/core.js') }}"></script>
    <!-- endinject -->
    <!-- inject:js -->
    <script src="{{ asset('vendors/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('js/template.js') }}"></script>
    <!-- endinject -->

</body>

</html>
