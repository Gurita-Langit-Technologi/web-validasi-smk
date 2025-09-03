<nav class="sidebar">
    <div class="sidebar-header">
        <a href="#" class="sidebar-brand">

        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item nav-category">Main</li>

            @if (\Illuminate\Support\Facades\Auth::guard('wali')->check())
                <li class="nav-item">
                    <a href="{{ route('wali.dashboard') }}"
                        class="nav-link {{ request()->routeIs('wali.dashboard') ? 'active' : '' }}">
                        <i class="link-icon" data-feather="box"></i>
                        <span class="link-title">Dashboard</span>
                    </a>
                </li>
            @else
                <li class="nav-item">
                    <a href="{{ route('guru.dashboard') }}"
                        class="nav-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                        <i class="link-icon" data-feather="box"></i>
                        <span class="link-title">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('page-tugas') }}"
                        class="nav-link {{ request()->routeIs('page-tugas') ? 'active' : '' }}">
                        <i class="link-icon" data-feather="message-square"></i>
                        <span class="link-title">Input rekap tugas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('page-kelas') }}"
                        class="nav-link {{ request()->routeIs('guru.page-kelas') || request()->routeIs('guru.detail-tugas') ? 'active' : '' }}">
                        <i class="link-icon" data-feather="message-square"></i>
                        <span class="link-title">Detail tugas</span>
                    </a>

                </li>

                <li class="nav-item">
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="nav-link">
                        <i class="link-icon" data-feather="log-out"></i>
                        <span class="link-title">Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            @endif
        </ul>
    </div>
</nav>
