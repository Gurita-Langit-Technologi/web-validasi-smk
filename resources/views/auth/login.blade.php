<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login | guru</title>
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

                                        <h5 class="text-muted font-weight-normal mb-4">
                                            Welcome back! Log in to your account.
                                        </h5>

                                        <!-- Tampilkan Error jika ada -->
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <!-- Tampilkan Pesan Sukses -->
                                        @if (session('success'))
                                            <div class="alert alert-success">
                                                {{ session('success') }}
                                            </div>
                                        @endif

                                        <form class="forms-sample" method="POST" action="{{ route('login.guru') }}">
                                            @csrf

                                            <div class="form-group">
                                                <label for="kode_guru">Kode Guru</label>
                                                <input type="text" class="form-control" id="kode_guru"
                                                    name="kode_guru" value="{{ old('kode_guru') }}" required autofocus
                                                    placeholder="Masukkan Kode Guru">
                                            </div>

                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="password"
                                                        name="password" required placeholder="Masukkan Password">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-light border"
                                                            id="togglePassword">
                                                            <i data-feather="eye"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-check form-check-flat form-check-primary">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="remember">
                                                    Remember me
                                                </label>
                                            </div>

                                            <div class="mt-3">
                                                <button type="submit"
                                                    class="btn btn-primary mr-2 mb-2 mb-md-0 text-white">
                                                    Login
                                                </button>
                                            </div>
                                            <a href="{{ route('login.wali.form') }}"
                                                class="d-block mt-3 text-muted text-primary">
                                                Login sebagai <span class="text-primary">Wali Kelas</span>
                                            </a>
                                            <a href="{{ route('login.koordinator.form') }}"
                                                class="d-block mt-1 text-muted text-danger">
                                                Login sebagai <span class="text-danger">Koordinator</span>
                                            </a>
                                            <a href="{{ route('guru.create') }}" class="d-block mt-3 text-muted">
                                                Belum punya password? Buat di sini
                                            </a>

                                            <!-- Tambahkan link forgot password -->
                                            <a href="{{ route('forgot-password-form') }}"
                                                class="d-block mt-2 text-muted">
                                                Lupa password?
                                            </a>

                                        </form>
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

    <!-- JavaScript untuk Toggle Password -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const passwordInput = document.getElementById("password");
            const togglePassword = document.getElementById("togglePassword");
            const eyeIcon = togglePassword.querySelector("i");

            togglePassword.addEventListener("click", function() {
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    eyeIcon.setAttribute("data-feather", "eye-off");
                } else {
                    passwordInput.type = "password";
                    eyeIcon.setAttribute("data-feather", "eye");
                }
                feather.replace(); // Update ikon setelah diubah
            });
        });
    </script>

</body>

</html>
