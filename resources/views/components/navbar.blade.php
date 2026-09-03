@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Storage;

    $guru = Auth::guard('guru')->user();
    $wali = Auth::guard('wali')->user();
    $admin = Auth::guard('web')->user();

    $user = $guru ?? $wali ?? $admin;

    $nama = 'Pengguna';
    $email = '-';
    $foto = asset('images/user.jpg');

    if ($guru) {
        $nama = $guru->nama_guru;
        $email = $guru->email ?? '-';
        if (!empty($guru->foto_guru)) {
            $foto = Storage::url($guru->foto_guru);
        }
    } elseif ($wali) {
        $nama = $wali->nama_wali;
        $email = $wali->email ?? '-';
        if (!empty($wali->guru?->foto_guru)) {
            $foto = Storage::url($wali->guru->foto_guru);
        }
    } elseif ($admin) {
        $nama = $admin->name ?? 'Admin';
        $email = $admin->email ?? '-';
    }
@endphp

<nav class="navbar">
    <a href="#" class="sidebar-toggler">
        <i data-feather="menu"></i>
    </a>
    <div class="navbar-content">
        <form class="search-form">
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <i data-feather="search"></i>
                    </div>
                </div>
                <input type="text" class="form-control" id="navbarForm" placeholder="Search here...">
            </div>
        </form>
        <ul class="navbar-nav">
            <li class="nav-item dropdown nav-profile">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown"
                    role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="{{ $foto }}" alt="profile" style="width:40px;height:40px;object-fit:cover;">
                    <span class="ml-2">{{ $nama }}</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="profileDropdown">
                    <div class="dropdown-header d-flex flex-column align-items-center">
                        <div class="figure mb-3">
                            <img src="{{ $foto }}" alt="profile"
                                style="width:50px;height:50px;object-fit:cover;">
                        </div>
                        <div class="info text-center">
                            <p class="name font-weight-bold mb-0">{{ $nama }}</p>
                            <p class="email text-muted mb-3">{{ $email }}</p>
                        </div>
                    </div>
                    <div class="dropdown-body">
                        <ul class="profile-nav p-0 pt-3">
                            <li class="nav-item">
                                <a href="pages/general/profile.html" class="nav-link">
                                    <i data-feather="user"></i>
                                    <span>Profile</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:;" class="nav-link">
                                    <i data-feather="edit"></i>
                                    <span>Edit Profile</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:;" class="nav-link">
                                    <i data-feather="repeat"></i>
                                    <span>Switch User</span>
                                </a>
                            </li>
                            @if ($user)
                                <li class="nav-item">
                                    <a href="#"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                        class="nav-link">
                                        <i class="link-icon" data-feather="log-out"></i>
                                        <span class="link-title">Logout</span>
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            @else
                                <li class="nav-item">
                                    {{-- <a href="#"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                        class="nav-link">
                                        <i class="link-icon" data-feather="log-out"></i>
                                        <span class="link-title">Logout</span>
                                    </a> --}}
                                    <a href="{{ route('login.wali.form') }}" class="nav-link">
                                        <i class="link-icon" data-feather="log-in"></i>
                                        <span class="link-title">Logout</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>
