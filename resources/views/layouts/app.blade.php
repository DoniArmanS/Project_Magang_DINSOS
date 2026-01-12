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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
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
            background: rgba(255, 255, 255, 0.95); /* White glass for content readability */
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
            background: rgba(255,255,255,0.1);
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
        }        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -280px; /* Hidden by default */
                width: 280px; 
                box-shadow: 5px 0 15px rgba(0,0,0,0.3);
            }
            .sidebar.active {
                margin-left: 0; /* Slide in */
            }
            .sidebar.collapsed {
                width: 280px; /* Reset collapse on mobile */
            }
            .main-content {
                margin-left: 0;
                padding: 1rem; /* Less padding on mobile */
            }
            .main-content.collapsed {
                margin-left: 0;
            }
            
            /* Hide Desktop Elements on Mobile Sidebar */
            .sidebar #sidebarToggle {
                display: none !important;
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
            border-left: 4px solid #ffca28; /* Gold Accent */
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
                    <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
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
            <div class="alert alert-success alert-dismissible fade show glass border-0 text-white" role="alert" style="background: rgba(25, 135, 84, 0.4);">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
        
        <footer class="mt-5 pt-4 border-top border-secondary text-center text-white-50">
            <small>&copy; {{ date('Y') }} Project Magang DINSOS. Dibuat dengan <i class="fas fa-heart text-danger"></i> oleh DoniArmanS.</small>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update Clock (Force Asia/Jakarta)
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
            if(clockEl) clockEl.innerText = timeString + ' WIB';
        }
        setInterval(updateClock, 1000);
        updateClock();

        document.addEventListener('DOMContentLoaded', function() {
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
            if(mobileToggle){
                mobileToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    sidebar.classList.add('active');
                    overlay.classList.add('active');
                });
            }

            // Mobile Close Click
            if(mobileClose){
                mobileClose.addEventListener('click', function(e) {
                    e.preventDefault();
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
            }

            // Overlay Click (Close Sidebar)
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });

            // Desktop Toggle
            if(desktopToggle && sidebar && mainContent){
                desktopToggle.addEventListener('click', function(e) {
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
                            if(title) title.style.display = 'none';
                        } else {
                            icon.classList.remove('fa-arrow-right');
                            icon.classList.add('fa-bars');
                            if(title) title.style.display = 'block';
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

