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
        <!-- Stats Grid -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="glass-card p-4 h-100 position-relative overflow-hidden">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-uppercase text-xs fw-bold text-muted mb-1">Total Kegiatan</p>
                            <h2 class="display-6 fw-bold mb-0 text-primary">{{ $totalActivities }}</h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-list-check text-primary fa-lg"></i>
                        </div>
                    </div>
                    <div class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1">
                        <i class="fas fa-arrow-up me-1"></i> Data Terupdate
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="glass-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-uppercase text-xs fw-bold text-muted mb-1">Selesai</p>
                            <h2 class="display-6 fw-bold mb-0 text-success">{{ $completedTasks }}</h2>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-check-double text-success fa-lg"></i>
                        </div>
                    </div>
                    <small class="text-muted">Tugas terselesaikan</small>
                </div>
            </div>

            <div class="col-md-4">
                <div class="glass-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-uppercase text-xs fw-bold text-muted mb-1">Pending</p>
                            <h2 class="display-6 fw-bold mb-0 text-warning">{{ $pendingTasks }}</h2>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-clock text-warning fa-lg"></i>
                        </div>
                    </div>
                    <small class="text-muted">Perlu tindak lanjut</small>
                </div>
            </div>
        </div>

        <!-- Charts & Recent Activities -->
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="glass-card p-4 h-100">
                    <h5 class="fw-bold mb-4">Statistik Mingguan</h5>
                    <canvas id="activityChart" height="120"></canvas>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="glass-card p-4 h-100">
                    <h5 class="fw-bold mb-4">Aktivitas Terbaru</h5>
                    <div class="vstack gap-3">
                        @foreach($recentActivities as $activity)
                        <div class="d-flex align-items-center p-3 rounded-3 bg-light bg-opacity-50">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-white d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                                    @if($activity->status == 'Selesai')
                                        <i class="fas fa-check text-success small"></i>
                                    @else
                                        <i class="fas fa-clock text-warning small"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="ms-3 overflow-hidden">
                                <h6 class="mb-0 text-truncate">{{ $activity->nama }}</h6>
                                <small class="text-muted text-truncate d-block">{{ $activity->kegiatan }}</small>
                            </div>
                            <small class="ms-auto text-muted">{{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</small>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Script -->
    <script>
        const ctx = document.getElementById('activityChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Jumlah Kegiatan',
                    data: {!! json_encode($chartData) !!},
                    borderColor: '#4361ee',
                    backgroundColor: 'rgba(67, 97, 238, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#4361ee'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [5, 5] }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    </script>
@endsection
