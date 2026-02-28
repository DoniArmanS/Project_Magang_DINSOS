<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIM-PPKS - Activity Tracker</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome (for icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: radial-gradient(circle at top right, #283593, #1a237e);
            background-attachment: fixed;
            min-height: 100vh;
            color: #fff;
            overflow-x: hidden;
        }

        /* Glassmorphism Utilities */
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            /* White glass for content readability */
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            border-radius: 15px;
            color: #333;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 280px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(15px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s;
            overflow: hidden;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        /* Hide text elements in collapsed mode */
        .sidebar.collapsed .sidebar-title,
        .sidebar.collapsed .sidebar-text,
        .sidebar.collapsed .nav-link span {
            display: none !important;
        }

        /* Center content horizontally */
        .sidebar.collapsed .d-flex {
            justify-content: center !important;
        }

        /* Stack header elements (Logo and Toggle) */
        .sidebar.collapsed .sidebar-header {
            flex-direction: column;
            gap: 15px;
            padding-bottom: 10px;
            align-items: center;
        }

        .sidebar.collapsed .logo-icon {
            margin-right: 0 !important;
        }

        /* Center Toggle Button in Header (reset absolute pos) */
        .sidebar.collapsed #sidebarToggle {
            position: static;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Center Nav Links */
        .sidebar.collapsed .nav-link {
            text-align: center;
            padding: 15px 0;
            display: flex;
            justify-content: center;
        }

        .sidebar.collapsed .nav-link i {
            margin-right: 0 !important;
            font-size: 1.4rem;
        }

        /* Profile Section Collapsed */
        .sidebar.collapsed .dropdown-toggle {
            display: flex;
            justify-content: center;
            padding: 0;
        }

        .sidebar.collapsed .dropdown-toggle img {
            margin-right: 0 !important;
            width: 40px;
            height: 40px;
        }

        /* Hide dropdown arrow if any (Bootstrap usually adds it via after pseudo-element) */
        .sidebar.collapsed .dropdown-toggle::after {
            display: none;
        }

        .main-content {
            margin-left: 280px;
            padding: 2rem;
            transition: all 0.3s;
        }

        .main-content.collapsed {
            margin-left: 80px;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {

            /* === SIDEBAR === */
            .sidebar {
                margin-left: -280px;
                width: 280px;
                box-shadow: 5px 0 15px rgba(0, 0, 0, 0.3);
            }

            .sidebar.active {
                margin-left: 0;
            }

            .sidebar.collapsed {
                width: 280px;
            }

            /* === MAIN CONTENT === */
            .main-content {
                margin-left: 0;
                padding: 1.25rem;
            }

            .main-content.collapsed {
                margin-left: 0;
            }

            .sidebar #sidebarToggle {
                display: none !important;
            }

            /* === TYPOGRAPHY — Proportional sizing === */
            h1,
            .h1 {
                font-size: 1.6rem !important;
            }

            h2,
            .h2 {
                font-size: 1.35rem !important;
            }

            h3,
            .h3 {
                font-size: 1.15rem !important;
            }

            h4,
            .h4 {
                font-size: 1rem !important;
            }

            /* === HEADER AREA === */
            .main-content>.mb-4:first-child,
            .main-content>div:first-child {
                margin-bottom: 1.5rem !important;
            }

            /* === STAT CARDS — Full width, spacious === */
            .row>.col-md-4,
            .row>.col-lg-4 {
                margin-bottom: 0.75rem;
            }

            .card {
                border-radius: 16px !important;
            }

            .card-body {
                padding: 1.25rem !important;
            }

            /* === CHART SECTION === */
            .btn-group-sm>.btn,
            .btn-sm {
                font-size: 0.8rem;
                padding: 0.4rem 0.75rem;
                border-radius: 20px !important;
            }

            .btn-group {
                flex-wrap: wrap;
                gap: 0.35rem;
            }

            .btn-group>.btn {
                border-radius: 20px !important;
            }

            /* === DATA TABLE — Comfortable scrolling === */
            .table-responsive {
                border-radius: 16px;
                -webkit-overflow-scrolling: touch;
                margin: 0 -0.5rem;
                padding: 0 0.5rem;
            }

            .table {
                font-size: 0.85rem;
            }

            .table th {
                font-size: 0.75rem;
                padding: 0.75rem 0.6rem !important;
                white-space: nowrap;
            }

            .table td {
                padding: 0.75rem 0.6rem !important;
                vertical-align: middle;
            }

            /* Activity action buttons — bigger touch targets */
            .table .btn-sm,
            .table .btn-group-sm>.btn {
                font-size: 0.72rem;
                padding: 0.35rem 0.6rem;
                min-height: 36px;
                display: inline-flex;
                align-items: center;
            }

            /* === FILTERS — Stacked vertically === */
            .row>.col-md-3,
            .row>.col-md-4,
            .row>.col-md-6 {
                margin-bottom: 0.75rem;
            }

            .form-control,
            .form-select {
                font-size: 0.95rem;
                padding: 0.65rem 0.85rem;
                border-radius: 12px !important;
                min-height: 44px;
            }

            .form-control-lg {
                font-size: 1rem;
                padding: 0.75rem 1rem;
                min-height: 48px;
            }

            /* === BUTTONS — Touch friendly === */
            .btn {
                min-height: 44px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 12px;
            }

            .btn-lg {
                padding: 0.75rem 1.5rem;
                font-size: 1rem;
            }

            /* === HEADER TIME/DATE === */
            .text-end.text-white {
                font-size: 0.85rem;
            }

            /* === GENERAL SPACING === */
            .mb-4 {
                margin-bottom: 1.25rem !important;
            }

            .mb-5 {
                margin-bottom: 2rem !important;
            }

            .py-3 {
                padding-top: 1rem !important;
                padding-bottom: 1rem !important;
            }

            .p-4 {
                padding: 1.25rem !important;
            }

            .gap-2 {
                gap: 0.5rem !important;
            }

            /* === FOOTER === */
            footer {
                font-size: 0.8rem;
                padding: 1rem !important;
            }

            /* === STATS CARDS 3-in-a-row — compact === */
            .glass-card {
                padding: 0.85rem !important;
            }

            .glass-card .display-6 {
                font-size: 1.5rem !important;
            }

            .glass-card .small {
                font-size: 0.65rem !important;
            }

            .glass-card .bg-opacity-10.p-3 {
                padding: 0.5rem !important;
            }

            .glass-card .fa-lg {
                font-size: 0.9em !important;
            }

            .glass-card .badge {
                font-size: 0.6rem !important;
                padding: 0.25rem 0.5rem !important;
            }

            .glass-card small.text-muted {
                font-size: 0.65rem !important;
            }

            /* === SHARE BUTTON — bigger, prominent === */
            .share-btn {
                font-size: 0.85rem !important;
                padding: 0.5rem 1rem !important;
                min-height: 40px;
                font-weight: 600;
                white-space: nowrap;
            }

            /* === PERIOD BUTTONS — active state === */
            .period-btn.active {
                background-color: #0d6efd !important;
                border-color: #0d6efd !important;
                color: #fff !important;
            }
        }

        /* Sidebar Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
            backdrop-filter: blur(3px);
            transition: opacity 0.3s;
            opacity: 0;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

        /* Nav Pills Customization */
        .nav-pills .nav-link {
            color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            margin-bottom: 5px;
            padding: 12px 20px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .nav-pills .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            transform: translateX(5px);
        }

        .nav-pills .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            border-left: 4px solid #ffca28;
            /* Gold Accent */
        }

        .btn-glass {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s;
        }

        .btn-glass:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Flex Header for Mobile Toggle and Clock -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div class="d-flex align-items-center">
                <!-- Mobile Toggle Only (Burger in content for mobile) -->
                <button class="btn btn-glass me-3 text-white d-md-none" id="mobileSidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- BRANDING LOGO (Mobile) -->
                <div class="d-flex align-items-center d-md-none">
                    <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-2"
                        style="width: 32px; height: 32px;">
                        <i class="fas fa-chart-line text-dark small"></i>
                    </div>
                    <h4 class="mb-0 fw-bold text-white">SIM-PPKS</h4>
                </div>
            </div>

            <!-- Real-time Clock (Desktop Only) -->
            <div class="d-none d-md-block text-end w-100">
                <h5 class="mb-0 fw-bold text-white" id="digitalClock">00:00:00</h5>
                <small class="text-white-50">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</small>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show glass border-0 text-white" role="alert"
            style="background: rgba(25, 135, 84, 0.4);">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @yield('content')

        <footer class="mt-5 pt-4 border-top border-secondary text-center text-white-50">
            <small>&copy; {{ date('Y') }} Project Magang DINSOS. Dibuat dengan <i class="fas fa-heart text-danger"></i>
                oleh DoniArmanS.</small>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Flatpickr JS & Locale -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Update Clock (Force Asia/Jakarta) - Format 24 jam
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                timeZone: 'Asia/Jakarta',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            }).replace(/\./g, ':');

            const clockEl = document.getElementById('digitalClock');
            if (clockEl) clockEl.innerText = timeString + ' WIB';
        }
        setInterval(updateClock, 1000);
        updateClock();

        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.getElementById('mainContent');
            const mobileToggle = document.getElementById('mobileSidebarToggle');
            const mobileClose = document.getElementById('mobileCloseToggle');
            const desktopToggle = document.getElementById('sidebarToggle');

            // Create Overlay for Mobile
            const overlay = document.createElement('div');
            overlay.className = 'sidebar-overlay';
            document.body.appendChild(overlay);

            // Mobile Toggle Click
            if (mobileToggle) {
                mobileToggle.addEventListener('click', function (e) {
                    e.preventDefault();
                    sidebar.classList.add('active');
                    overlay.classList.add('active');
                });
            }

            // Mobile Close Click
            if (mobileClose) {
                mobileClose.addEventListener('click', function (e) {
                    e.preventDefault();
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
            }

            // Overlay Click (Close Sidebar)
            overlay.addEventListener('click', function () {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });

            // Desktop Toggle
            if (desktopToggle && sidebar && mainContent) {
                desktopToggle.addEventListener('click', function (e) {
                    e.preventDefault();

                    if (window.innerWidth >= 768) {
                        // Desktop Behavior
                        sidebar.classList.toggle('collapsed');
                        mainContent.classList.toggle('collapsed');

                        const icon = this.querySelector('i');
                        const title = document.querySelector('.sidebar-title');

                        if (sidebar.classList.contains('collapsed')) {
                            icon.classList.remove('fa-bars');
                            icon.classList.add('fa-arrow-right');
                            if (title) title.style.display = 'none';
                        } else {
                            icon.classList.remove('fa-arrow-right');
                            icon.classList.add('fa-bars');
                            if (title) title.style.display = 'block';
                        }
                    } else {
                        // Mobile Behavior (Just in case button is visible)
                        sidebar.classList.toggle('active');
                        overlay.classList.toggle('active');
                    }
                });
            }
        });
    </script>
</body>

</html>