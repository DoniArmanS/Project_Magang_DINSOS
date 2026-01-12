@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Hero Section -->
    <div class="row align-items-center mb-5 pb-4 border-bottom border-secondary">
        <div class="col-md-8">
            <h5 class="text-warning text-uppercase ls-1">Selamat Datang 👋</h5>
            <h1 class="display-4 fw-bold">Dashboard <span style="background: -webkit-linear-gradient(45deg, #4facfe, #00f2fe); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Activity Tracker</span></h1>
            <p class="lead text-light opacity-75">Pantau kinerja dan aktivitas harian tim Dinas Sosial secara real-time.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('activities.create') }}" class="btn btn-light btn-lg shadow-lg fw-bold rounded-pill px-4">
                <i class="fas fa-plus me-2"></i> Report Baru
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <!-- Total Kegiatan -->
        <div class="col-md-6 col-lg-3">
            <div class="card glass text-white h-100 border-0">
                <div class="card-body p-4 position-relative overflow-hidden">
                    <div class="position-absolute top-0 end-0 p-3 opacity-25">
                        <i class="fas fa-tasks fa-3x"></i>
                    </div>
                    <h6 class="text-uppercase text-white-50 fw-bold">Total Kegiatan</h6>
                    <h2 class="display-5 fw-bold mb-0 mt-2">128</h2>
                    <small class="text-success fw-bold"><i class="fas fa-arrow-up"></i> 12% Increase</small>
                </div>
            </div>
        </div>

        <!-- Selesai -->
        <div class="col-md-6 col-lg-3">
             <div class="card glass text-white h-100 border-0">
                <div class="card-body p-4 position-relative overflow-hidden">
                    <div class="position-absolute top-0 end-0 p-3 opacity-25">
                        <i class="fas fa-check-circle fa-3x text-success"></i>
                    </div>
                    <h6 class="text-uppercase text-white-50 fw-bold">Selesai</h6>
                    <h2 class="display-5 fw-bold mb-0 mt-2">94</h2>
                    <small class="text-white-50">Completed Tasks</small>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="col-md-6 col-lg-3">
             <div class="card glass text-white h-100 border-0">
                <div class="card-body p-4 position-relative overflow-hidden">
                    <div class="position-absolute top-0 end-0 p-3 opacity-25">
                        <i class="fas fa-clock fa-3x text-warning"></i>
                    </div>
                    <h6 class="text-uppercase text-white-50 fw-bold">Pending</h6>
                    <h2 class="display-5 fw-bold mb-0 mt-2">34</h2>
                    <small class="text-warning">Needs Review</small>
                </div>
            </div>
        </div>

        <!-- Pegawai -->
        <div class="col-md-6 col-lg-3">
             <div class="card glass text-white h-100 border-0">
                <div class="card-body p-4 position-relative overflow-hidden">
                    <div class="position-absolute top-0 end-0 p-3 opacity-25">
                        <i class="fas fa-users fa-3x text-info"></i>
                    </div>
                    <h6 class="text-uppercase text-white-50 fw-bold">Pegawai Aktif</h6>
                    <h2 class="display-5 fw-bold mb-0 mt-2">24</h2>
                    <small class="text-info">Online Now</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Tables -->
    <div class="row g-4">
        <!-- Chart -->
        <div class="col-lg-8">
            <div class="card glass border-0 p-4 h-100">
                <h4 class="mb-4 fw-bold">Statistik Mingguan</h4>
                <div style="height: 300px;">
                    <canvas id="activityChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-lg-4">
            <div class="card glass border-0 p-4 h-100">
                <h4 class="mb-4 fw-bold">Aktivitas Terbaru</h4>
                <div class="list-group list-group-flush bg-transparent">
                    <div class="list-group-item bg-transparent text-white border-bottom border-secondary d-flex justify-content-between align-items-center px-0">
                        <div>
                            <i class="fas fa-circle text-success me-2" style="font-size: 8px;"></i>
                            <span class="fw-bold">Laporan TKSK</span>
                            <br>
                            <small class="text-white-50 ms-3">Input Data Lansia</small>
                        </div>
                        <span class="badge bg-secondary">2m</span>
                    </div>
                    <div class="list-group-item bg-transparent text-white border-bottom border-secondary d-flex justify-content-between align-items-center px-0">
                         <div>
                            <i class="fas fa-circle text-warning me-2" style="font-size: 8px;"></i>
                            <span class="fw-bold">Data ODGJ</span>
                             <br>
                            <small class="text-white-50 ms-3">Verifikasi Lapangan</small>
                        </div>
                        <span class="badge bg-secondary">1h</span>
                    </div>
                     <div class="list-group-item bg-transparent text-white border-bottom border-secondary d-flex justify-content-between align-items-center px-0">
                         <div>
                            <i class="fas fa-circle text-info me-2" style="font-size: 8px;"></i>
                            <span class="fw-bold">Bantuan Sosial</span>
                             <br>
                            <small class="text-white-50 ms-3">Distribusi Sembako</small>
                        </div>
                        <span class="badge bg-secondary">3h</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('activityChart').getContext('2d');
    
    var gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(80, 200, 255, 0.5)');
    gradient.addColorStop(1, 'rgba(80, 200, 255, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
            datasets: [{
                label: 'Jumlah Laporan',
                data: [12, 19, 3, 5, 2, 3, 7],
                borderColor: '#00f2fe',
                backgroundColor: gradient,
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    grid: { color: 'rgba(255, 255, 255, 0.1)' },
                    ticks: { color: '#fff' }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#fff' }
                }
            }
        }
    });
});
</script>
@endsection
