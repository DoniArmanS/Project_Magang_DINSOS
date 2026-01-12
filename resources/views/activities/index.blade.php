@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Data Kegiatan</h2>
            <p class="text-white-50">Kelola semua laporan aktivitas pegawai.</p>
        </div>
        <div>
            <a href="{{ route('activities.create') }}" class="btn btn-primary btn-lg shadow-sm me-2 fw-bold">
                <i class="fas fa-plus me-2"></i> Tambah
            </a>
            <a href="{{ route('activities.export', request()->query()) }}" class="btn btn-success btn-lg shadow-sm fw-bold">
                <i class="fas fa-file-excel me-2"></i> Export
            </a>
        </div>
    </div>

    <!-- Filter Section using Bootstrap Cards -->
    <div class="card glass border-0 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('activities.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="kategori" class="form-label text-warning fw-bold text-uppercase" style="font-size: 0.8rem;">Kategori</label>
                    <select name="kategori" id="kategori" class="form-select bg-dark text-white border-secondary">
                        <option value="All" class="text-white">Semua Kategori</option>
                        <option value="TKSK" {{ request('kategori') == 'TKSK' ? 'selected' : '' }}>TKSK</option>
                        <option value="PSM" {{ request('kategori') == 'PSM' ? 'selected' : '' }}>PSM</option>
                        <option value="ODGJ" {{ request('kategori') == 'ODGJ' ? 'selected' : '' }}>ODGJ</option>
                        <option value="Disabilitas" {{ request('kategori') == 'Disabilitas' ? 'selected' : '' }}>Disabilitas</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="period" class="form-label text-warning fw-bold text-uppercase" style="font-size: 0.8rem;">Waktu</label>
                    <select name="period" id="period" class="form-select bg-dark text-white border-secondary">
                        <option value="All">Semua Waktu</option>
                        <option value="Hari Ini" {{ request('period') == 'Hari Ini' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="Minggu Ini" {{ request('period') == 'Minggu Ini' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="Bulan Ini" {{ request('period') == 'Bulan Ini' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="Tahun Ini" {{ request('period') == 'Tahun Ini' ? 'selected' : '' }}>Tahun Ini</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-light w-100 fw-bold">Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card glass-card border-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">Foto</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Nama Klien</th> <!-- CHANGED LABEL HERE -->
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3">Detail</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                    <tr>
                        <td class="px-4">
                            @if($activity->foto_path)
                                <img src="{{ Storage::url($activity->foto_path) }}" class="rounded-circle border border-2 border-primary" width="45" height="45" style="object-fit: cover; cursor: pointer;" onclick="showImage(this.src)">
                            @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white" style="width: 45px; height: 45px;">
                                    <i class="fas fa-image"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 fw-bold text-secondary">{{ \Carbon\Carbon::parse($activity->tanggal)->format('d M Y') }}</td>
                        <td class="px-4 fw-bold text-dark">{{ $activity->nama }}</td>
                        <td class="px-4">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">{{ $activity->kategori }}</span>
                        </td>
                        <td class="px-4 text-muted small" style="max-width: 200px;">
                            {{ Str::limit($activity->kegiatan, 50) }}
                        </td>
                        <td class="px-4">
                            @if($activity->status === 'Selesai')
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill"><i class="fas fa-check-circle me-1"></i> Selesai</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill"><i class="fas fa-clock me-1"></i> Pending</span>
                            @endif
                        </td>
                        <td class="px-4 text-end">
                            <button onclick="shareToWhatsapp({{ $activity->id }}, '{{ $activity->nama }}', '{{ Str::limit($activity->kegiatan, 100) }}', '{{ $activity->foto_path ? Storage::url($activity->foto_path) : '' }}')" class="btn btn-sm btn-outline-success rounded-pill">
                                <i class="fab fa-whatsapp"></i> Share
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 opacity-50"></i>
                                <h5>Belum ada data kegiatan</h5>
                                <p>Silakan tambah laporan baru.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

    <!-- Image Preview Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0 shadow-none">
                <div class="modal-body text-center position-relative">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                    <img id="modalImage" src="" class="img-fluid rounded-3 shadow-lg" style="max-height: 85vh;">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function shareToWhatsapp(id, nama, kegiatan, fotoPath) {
        const text = `*Laporan Kegiatan DINSOS*\n\nNama Klien: ${nama}\nKegiatan: ${kegiatan}\n\nLihat Foto: ${window.location.origin}${fotoPath}`;
        const encodedText = encodeURIComponent(text);
        window.open(`https://wa.me/?text=${encodedText}`, '_blank');
    }

    function showImage(src) {
        document.getElementById('modalImage').src = src;
        new bootstrap.Modal(document.getElementById('imageModal')).show();
    }
</script>
@endsection
