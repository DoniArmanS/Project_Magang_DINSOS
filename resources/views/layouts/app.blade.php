<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Activity Tracker') }}</title>
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

        .sidebar.collapsed .sidebar-title,
        .sidebar.collapsed .sidebar-text,
        .sidebar.collapsed .nav-link span {
            display: none !important;
        }
        
        .sidebar.collapsed .d-flex.align-items-center {
            justify-content: center;
        }

        .sidebar.collapsed .logo-icon {
            margin-right: 0 !important;
        }

        .sidebar.collapsed #sidebarToggle {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
        }

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
                margin-left: -280px;
                width: 280px; /* Always full width on mobile active */
            }
            .sidebar.active {
                margin-left: 0;
            }
            .sidebar.collapsed {
                width: 280px; /* Reset collapse on mobile */
            }
            .main-content {
                margin-left: 0;
            }
            .main-content.collapsed {
                margin-left: 0;
            }
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
                <h4 class="mb-0 fw-bold d-md-none text-white">SIM-PPKS</h4>
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
            
            document.getElementById('digitalClock').innerText = timeString + ' WIB';
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Mobile Toggle (In Content)
        const mobileToggle = document.getElementById('mobileSidebarToggle');
        if(mobileToggle){
            mobileToggle.addEventListener('click', function() {
                sidebar.classList.add('active');
            });
        }

        // Mobile Close (In Sidebar)
        const mobileClose = document.getElementById('mobileCloseToggle');
        if(mobileClose){
            mobileClose.addEventListener('click', function() {
                sidebar.classList.remove('active');
            });
        }

        // Desktop Toggle (In Sidebar)
        const desktopToggle = document.getElementById('sidebarToggle');
        if(desktopToggle){
            desktopToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('collapsed');
                
                // Toggle Icon Logic
                const icon = this.querySelector('i');
                if (sidebar.classList.contains('collapsed')) {
                    // When collapsed, show burger? or arrow? 
                    // User said: "kelo dipencet mengecil dan jadi burger itu aja"
                    // If collapsed, sidebar is small. If we hide text, the header is just logos.
                    
                    // Adjust styles for collapsed header
                    document.querySelector('.sidebar-title').style.display = 'none';
                    // Center the toggle?
                    
                } else {
                    document.querySelector('.sidebar-title').style.display = 'block';
                }
            });
        }
    </script>
</body>
</html>

