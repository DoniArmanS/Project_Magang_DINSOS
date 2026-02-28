@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Hero Section -->
    <div class="row align-items-center mb-5 pb-4 border-bottom border-secondary">
        <div class="col-md-8">
            <h5 class="text-warning text-uppercase ls-1">Selamat Datang 👋</h5>
            <h1 class="display-4 fw-bold">Dashboard <span
                    style="background: -webkit-linear-gradient(45deg, #4facfe, #00f2fe); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Activity
                    Tracker</span></h1>
            <p class="lead text-light opacity-75">Pantau kinerja dan aktivitas harian tim Dinas Sosial secara real-time.
            </p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('activities.create') }}" class="btn btn-light btn-lg shadow-lg fw-bold rounded-pill px-4">
                <i class="fas fa-plus me-2"></i> Report Baru
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-2 g-md-4 mb-5">
        <div class="col-4">
            <div class="glass-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="text-uppercase small fw-bold text-muted mb-1">Total Kegiatan</p>
                        <h2 class="display-6 fw-bold mb-0 text-primary">{{ $totalActivities }}</h2>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-list-check text-primary fa-lg"></i>
                    </div>
                </div>
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1">
                    <i class="fas fa-arrow-up me-1"></i> Data Terupdate
                </span>
            </div>
        </div>
        <div class="col-4">
            <div class="glass-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="text-uppercase small fw-bold text-muted mb-1">Selesai</p>
                        <h2 class="display-6 fw-bold mb-0 text-success">{{ $completedTasks }}</h2>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-check-double text-success fa-lg"></i>
                    </div>
                </div>
                <small class="text-muted">Tugas terselesaikan</small>
            </div>
        </div>
        <div class="col-4">
            <div class="glass-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="text-uppercase small fw-bold text-muted mb-1">Sedang Proses</p>
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

    <!-- Chart + Recent -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="glass-card p-4 h-100">
                <!-- Chart Header -->
                <div
                    class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
                    <div>
                        <h5 class="fw-bold mb-0">Kegiatan per Pelayanan</h5>
                        <small class="text-muted" id="chartSubtitle">{{ $chartSubtitle }}</small>
                    </div>
                    <!-- Period Filter Buttons -->
                    <div class="d-flex gap-2 flex-wrap align-items-center">
                        <div class="d-flex flex-wrap gap-2" role="group">
                            <button type="button" class="btn btn-sm btn-primary period-btn active rounded-pill"
                                data-period="hari_ini">Hari
                                Ini</button>
                            <button type="button" class="btn btn-sm btn-outline-primary period-btn rounded-pill"
                                data-period="minggu_ini">Minggu Ini</button>
                            <button type="button" class="btn btn-sm btn-outline-primary period-btn rounded-pill"
                                data-period="bulan_ini">Bulan Ini</button>
                            <button type="button" class="btn btn-sm btn-outline-primary period-btn rounded-pill"
                                data-period="tahun_ini">Tahun Ini</button>
                        </div>
                        <!-- Custom Date Range -->
                        <div class="d-flex align-items-center gap-1">
                            <input type="text" id="chartDateRange"
                                class="form-control form-control-sm bg-dark text-white border-secondary"
                                placeholder="📅 Rentang tanggal..." style="min-width: 165px; font-size: 0.78rem;"
                                readonly>
                            <button class="btn btn-sm btn-outline-secondary" id="clearRange" title="Reset ke 7 Hari">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Spinner -->
                <div id="chartLoading" class="text-center py-5 d-none">
                    <div class="spinner-border text-primary" role="status"><span
                            class="visually-hidden">Loading...</span></div>
                </div>

                <canvas id="activityChart" height="150"></canvas>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="col-lg-4">
            <div class="glass-card p-4 h-100">
                <h5 class="fw-bold mb-4">Aktivitas Terbaru</h5>
                <div class="vstack gap-3" style="max-height: 420px; overflow-y: auto;">
                    @foreach($recentActivities as $activity)
                    <div class="d-flex align-items-center p-3 rounded-3 bg-light bg-opacity-50">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-white d-flex align-items-center justify-content-center shadow-sm"
                                style="width:40px;height:40px;">
                                @if($activity->status == 'Selesai')
                                <i class="fas fa-check text-success small"></i>
                                @else
                                <i class="fas fa-spinner text-warning small"></i>
                                @endif
                            </div>
                        </div>
                        <div class="ms-3 overflow-hidden">
                            <h6 class="mb-0 text-truncate">{{ $activity->nama }}</h6>
                            <small class="text-muted text-truncate d-block">
                                {{ $activity->user?->name ?? 'Unknown' }}
                                &middot; {{ \Carbon\Carbon::parse($activity->tanggal)->format('h:i A') }}
                            </small>
                        </div>
                        <small class="ms-auto text-muted text-nowrap">{{
                            \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</small>
                    </div>
                    @endforeach
                    @if($recentActivities->isEmpty())
                    <p class="text-muted text-center">Belum ada data.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Data awal dari server
    let chartLabels = @json($chartLabels);
    let chartDatasets = @json($chartDatasets);

    // Init Chart (non-stacked, per kategori)
    const ctx = document.getElementById('activityChart').getContext('2d');
    const activityChart = new Chart(ctx, {
        type: 'bar',
        data: { labels: chartLabels, datasets: chartDatasets },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }, // 1 dataset, tidak perlu legend
                tooltip: {
                    callbacks: {
                        label: ctx => ' ' + ctx.parsed.y + ' kegiatan'
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#666', font: { weight: '600' } } },
                y: { beginAtZero: true, ticks: { stepSize: 1, color: '#666' }, grid: { borderDash: [4, 4] } }
            }
        }
    });

    function fetchChartData(params) {
        document.getElementById('chartLoading').classList.remove('d-none');
        document.getElementById('activityChart').style.opacity = '0.3';

        fetch("{{ route('home') }}?" + new URLSearchParams(params).toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(r => r.json())
            .then(data => {
                activityChart.data.labels = data.chartLabels;
                activityChart.data.datasets = data.chartDatasets;
                activityChart.update();
                document.getElementById('chartSubtitle').textContent = data.chartSubtitle ?? '';
            })
            .catch(err => console.error('Chart error:', err))
            .finally(() => {
                document.getElementById('chartLoading').classList.add('d-none');
                document.getElementById('activityChart').style.opacity = '1';
            });
    }

    // Tombol periode
    document.querySelectorAll('.period-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            // Clear range picker text
            const fp = document.getElementById('chartDateRange')._flatpickr;
            if (fp) fp.clear();

            document.querySelectorAll('.period-btn').forEach(b => {
                b.classList.remove('btn-primary', 'active');
                b.classList.add('btn-outline-primary');
            });
            this.classList.remove('btn-outline-primary');
            this.classList.add('btn-primary', 'active');

            fetchChartData({ period: this.dataset.period });
        });
    });

    // Date range picker — init setelah DOM siap
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr('#chartDateRange', {
            mode: 'range',
            dateFormat: 'd/m/Y',
            allowInput: false,
            onClose: function (selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    document.querySelectorAll('.period-btn').forEach(b => {
                        b.classList.replace('btn-primary', 'btn-outline-primary');
                    });
                    const fmt = d => {
                        const p = n => String(n).padStart(2, '0');
                        return `${d.getFullYear()}-${p(d.getMonth() + 1)}-${p(d.getDate())}`;
                    };
                    fetchChartData({ period: 'custom', start: fmt(selectedDates[0]), end: fmt(selectedDates[1]) });
                }
            }
        });
    });

    document.getElementById('clearRange').addEventListener('click', function () {
        const fp = document.getElementById('chartDateRange')._flatpickr;
        if (fp) fp.clear();
        document.querySelector('[data-period="hari_ini"]').click();
    });
</script>
@endsection