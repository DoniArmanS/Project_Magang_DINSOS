<div class="sidebar d-flex flex-column flex-shrink-0 p-3 text-white">
    <div class="sidebar-header d-flex align-items-center justify-content-between mb-3 mb-md-0 me-md-auto text-white text-decoration-none w-100">
        <a href="{{ route('home') }}" class="d-flex align-items-center text-white text-decoration-none">
            <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-2 logo-icon" style="width: 32px; height: 32px;">
                <i class="fas fa-chart-line text-dark"></i>
            </div>
            <span class="fs-4 fw-bold sidebar-title">SIM-<span style="color: #ffca28;">PPKS</span></span>
        </a>
        <button class="btn btn-sm btn-glass d-none d-md-block" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <button class="btn btn-sm btn-glass d-md-none" id="mobileCloseToggle">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <hr style="border-color: rgba(255,255,255,0.2);">
    
    <!-- Menu Items -->
    <ul class="nav nav-pills flex-column mb-auto mt-4">
        <li class="nav-item">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" aria-current="page" title="Dashboard">
                <i class="fas fa-home me-2" style="width: 20px; text-align: center;"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('activities.create') }}" class="nav-link {{ request()->routeIs('activities.create') ? 'active' : '' }}" title="Input Laporan">
                <i class="fas fa-plus-circle me-2" style="width: 20px; text-align: center;"></i>
                <span class="sidebar-text">Input Laporan</span>
            </a>
        </li>
        <li>
            <a href="{{ route('activities.index') }}" class="nav-link {{ request()->routeIs('activities.index') ? 'active' : '' }}" title="Data Kegiatan">
                <i class="fas fa-list me-2" style="width: 20px; text-align: center;"></i>
                <span class="sidebar-text">Data Kegiatan</span>
            </a>
        </li>
    </ul>
    
    <hr style="border-color: rgba(255,255,255,0.2);">
    
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=random" alt="" width="32" height="32" class="rounded-circle me-2 border border-light">
            <strong class="sidebar-text">{{ Auth::user()->name ?? 'Guest' }}</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item">Sign out</button>
                </form>
            </li>
        </ul>
    </div>
</div>
