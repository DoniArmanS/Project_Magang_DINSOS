@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header & Actions -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <div class="text-center text-md-start">
            <h2 class="fw-bold mb-0">Data Kegiatan</h2>
            <p class="text-white-50 mb-0">Kelola semua laporan aktivitas pegawai.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('activities.create') }}" class="btn btn-primary shadow-sm fw-bold px-4 rounded-pill">
                <i class="fas fa-plus me-2"></i> Tambah
            </a>
            <a href="{{ route('activities.export') }}" id="btnExport"
                class="btn btn-success shadow-sm fw-bold px-4 rounded-pill">
                <i class="fas fa-file-excel me-2"></i> Export
            </a>
        </div>
    </div>

    <!-- Filter Section (AJAX) -->
    <div class="card glass border-0 mb-4">
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label text-warning fw-bold text-uppercase small">Kategori</label>
                    <select id="filterKategori" class="form-select bg-dark text-white border-secondary"
                        onchange="filterActivities()">
                        <option value="All">Semua Kategori</option>
                        <option value="Terlantar">Terlantar</option>
                        <option value="Tuna Susila">Tuna Susila</option>
                        <option value="ODGJ">ODGJ</option>
                        <option value="Anak">Anak</option>
                        <option value="Lansia">Lansia</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-warning fw-bold text-uppercase small">Waktu (Pilih Rentang &
                        Jam)</label>
                    <input type="text" id="filterDate" class="form-control bg-dark text-white border-secondary"
                        placeholder="Tentukan Tanggal & Waktu" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-warning fw-bold text-uppercase small">Status</label>
                    <select id="filterStatus" class="form-select bg-dark text-white border-secondary"
                        onchange="filterActivities()">
                        <option value="All">Semua Status</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Sedang Proses">Sedang Proses</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card glass-card border-0 overflow-hidden">
        <div class="table-responsive" style="max-height: 420px; overflow-y: auto;">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light sticky-top">
                    <tr>
                        <th class="px-4 py-3" style="width:50px;">No.</th>
                        <th class="px-4 py-3">Foto</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Nama Klien</th>
                        <th class="px-4 py-3">Kelamin</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3">Detail</th>
                        <th class="px-4 py-3">Petugas</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @include('activities.partials.table_body')
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function filterActivities() {
        const kategori = document.getElementById('filterKategori').value;
        const dateRange = document.getElementById('filterDate').value;
        const status = document.getElementById('filterStatus').value;
        const tbody = document.getElementById('tableBody');
        const btnExport = document.getElementById('btnExport');

        tbody.innerHTML = '<tr><td colspan="10" class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';

        let start_date = '', end_date = '';
        if (dateRange) {
            const parts = dateRange.split(' to ');
            start_date = parts[0];
            end_date = parts[1] || parts[0];
        }

        const params = new URLSearchParams({ kategori, status, start_date, end_date });
        btnExport.href = "{{ route('activities.export') }}?" + params.toString();

        params.append('_t', new Date().getTime());

        fetch("{{ route('activities.index', [], false) }}?" + params.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(r => r.text())
            .then(html => { tbody.innerHTML = html; })
            .catch(err => {
                console.error(err);
                tbody.innerHTML = '<tr><td colspan="10" class="text-center text-danger py-5">Gagal memuat data. Silakan coba lagi.</td></tr>';
            });
    }

    document.addEventListener('DOMContentLoaded', function () {
        flatpickr("#filterDate", {
            mode: "range",
            enableTime: true,
            time_24hr: false,
            dateFormat: "Y-m-d H:i",
            allowInput: true,
            theme: "dark",
            onClose: function () { filterActivities(); }
        });
    });
</script>

<!-- Image Gallery Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0 shadow-none">
            <div class="modal-body position-relative p-0">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 z-3"
                    data-bs-dismiss="modal" aria-label="Close"></button>
                <div id="galleryCarousel" class="carousel slide" data-bs-ride="false">
                    <div class="carousel-inner rounded-4 shadow-lg" id="carouselInner"></div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#galleryCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#galleryCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Hidden Share Card -->
<div id="shareCardContainer" style="position: absolute; left: -9999px; top: 0;">
    <div id="shareCard" style="width: 500px; background: white; padding: 10px;">
        <div id="shareImagesGrid" class="d-flex flex-column gap-2"></div>
        <div class="d-none">
            <span id="shareNama"></span>
            <span id="shareKegiatan"></span>
            <span id="shareTanggal"></span>
        </div>
    </div>
</div>
</div>

<script>
    async function shareToWhatsapp(id, nama, kegiatan, photos, tanggal) {
        const shareBtn = event.currentTarget;
        const originalContent = shareBtn.innerHTML;
        shareBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
        shareBtn.disabled = true;

        try {
            const text = `*Laporan Kegiatan DINSOS*\nNama Klien: ${nama}\nKegiatan: ${kegiatan}\nTanggal: ${tanggal}`;
            const photoList = Array.isArray(photos) ? photos : (photos ? [photos] : []);
            let nativeShareSuccess = false;

            if (navigator.share && photoList.length > 0) {
                try {
                    const filePromises = photoList.map(async (src, i) => {
                        const response = await fetch(src);
                        const blob = await response.blob();
                        return new File([blob], `Dokumentasi-${i + 1}.jpg`, { type: 'image/jpeg' });
                    });
                    const files = await Promise.all(filePromises);
                    if (navigator.canShare({ files })) {
                        try { await navigator.clipboard.writeText(text); } catch (e) { }
                        await navigator.share({ files, title: 'Laporan Kegiatan', text });
                        nativeShareSuccess = true;
                    }
                } catch (err) {
                    if (err.name === 'AbortError') nativeShareSuccess = true;
                }
            }

            if (!nativeShareSuccess) {
                const grid = document.getElementById('shareImagesGrid');
                grid.innerHTML = '';
                document.getElementById('shareNama').innerText = nama;
                document.getElementById('shareKegiatan').innerText = kegiatan;
                document.getElementById('shareTanggal').innerText = tanggal;

                if (photoList.length > 0) {
                    const imagePromises = photoList.map(src => new Promise(resolve => {
                        const img = document.createElement('img');
                        img.crossOrigin = "anonymous";
                        img.src = src;
                        img.className = 'w-100 rounded-2 shadow-sm mb-2';
                        img.style.objectFit = 'contain';
                        img.onload = resolve;
                        img.onerror = resolve;
                        grid.appendChild(img);
                    }));
                    await Promise.all(imagePromises);
                } else {
                    grid.innerHTML = '<div class="text-center p-5 text-muted">No Image</div>';
                }

                const canvas = await html2canvas(document.getElementById('shareCard'), { useCORS: true, scale: 2, backgroundColor: '#ffffff' });
                canvas.toBlob(async (blob) => {
                    try {
                        await navigator.clipboard.write([new ClipboardItem({ "image/png": blob })]);
                        const waUrl = `https://web.whatsapp.com/send?text=${encodeURIComponent(text)}`;
                        if (confirm("✅ Foto disalin ke Clipboard!\n\nKlik OK → WhatsApp Terbuka → PASTE (Ctrl+V).")) {
                            window.open(waUrl, '_blank');
                        }
                    } catch (err) {
                        alert("Gagal copy gambar. Silakan download manual.");
                    }
                });
            }
        } catch (error) {
            console.error('Share Error:', error);
        } finally {
            shareBtn.innerHTML = originalContent;
            shareBtn.disabled = false;
        }
    }

    function showGallery(photos) {
        const carouselInner = document.getElementById('carouselInner');
        carouselInner.innerHTML = '';
        photos.forEach((src, index) => {
            const item = document.createElement('div');
            item.className = `carousel-item ${index === 0 ? 'active' : ''}`;
            item.innerHTML = `<img src="${src}" class="d-block w-100 rounded-4" style="max-height: 80vh; object-fit: contain; background: rgba(0,0,0,0.8);">`;
            carouselInner.appendChild(item);
        });
        new bootstrap.Modal(document.getElementById('imageModal')).show();
    }
</script>
@endsection