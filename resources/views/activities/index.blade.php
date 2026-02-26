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
            <a href="{{ route('activities.export') }}" id="btnExport" class="btn btn-success shadow-sm fw-bold px-4 rounded-pill">
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
                    <select id="filterKategori" class="form-select bg-dark text-white border-secondary" onchange="filterActivities()">
                        <option value="All">Semua Kategori</option>
                        <option value="Terlantar">Terlantar</option>
                        <option value="Tuna Susila">Tuna Susila</option>
                        <option value="ODGJ">ODGJ</option>
                        <option value="Anak">Anak</option>
                        <option value="Lansia">Lansia</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-warning fw-bold text-uppercase small">Waktu (Pilih Rentang & Jam)</label>
                    <input type="text" id="filterDate" class="form-control bg-dark text-white border-secondary" placeholder="Tentukan Tanggal & Waktu" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-warning fw-bold text-uppercase small">Status</label>
                    <select id="filterStatus" class="form-select bg-dark text-white border-secondary" onchange="filterActivities()">
                        <option value="All">Semua Status</option>
                        <option value="Selesai">Sukses</option>
                        <option value="Pending">Pending</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card glass-card border-0 overflow-hidden">
        <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">Foto</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Nama Klien</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3">Detail</th>
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

        // Logic Visual Loading
        tbody.innerHTML = '<tr><td colspan="7" class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';

        let start_date = '';
        let end_date = '';
        if(dateRange) {
            // Flatpickr range separator is usually " to "
            const parts = dateRange.split(' to ');
            start_date = parts[0];
            end_date = parts[1] || parts[0];
        }

        // Update Export Link
        const params = new URLSearchParams({ kategori, status, start_date, end_date });
        btnExport.href = "{{ route('activities.export') }}?" + params.toString();

        // AJAX Fetch with Cache Busting
        params.append('_t', new Date().getTime());
        
        fetch("{{ route('activities.index', [], false) }}?" + params.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            tbody.innerHTML = html;
        })
        .catch(err => {
            console.error(err);
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger py-5">Gagal memuat data. Silakan coba lagi.</td></tr>';
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#filterDate", {
            mode: "range",
            enableTime: true,
            time_24hr: true,
            dateFormat: "Y-m-d H:i",
            locale: "id",
            allowInput: true,
            theme: "dark",
            onClose: function(selectedDates, dateStr, instance) {
                // Only filter if range is fully selected or a single date is selected
                filterActivities();
            }
        });
    });
</script>

    <!-- Image Gallery Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0 shadow-none">
                <div class="modal-body position-relative p-0">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 z-3" data-bs-dismiss="modal" aria-label="Close"></button>
                    
                    <div id="galleryCarousel" class="carousel slide" data-bs-ride="false">
                        <div class="carousel-inner rounded-4 shadow-lg" id="carouselInner">
                            <!-- Items injected by JS -->
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#galleryCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#galleryCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Hidden Share Card Template -->
    <div id="shareCardContainer" style="position: absolute; left: -9999px; top: 0;">
        <div id="shareCard" style="width: 500px; background: white; padding: 10px;">
            <!-- Simple Header for context, usually helpful, but user said 'Tanpa Bingkai'. 
                 I will keep it extremely minimal or remove it. 
                 User said "cuman kirim 1 foto gambr depannya dan tidak ada template kata".
                 They want the photos. I will stack them. -->
             
             <!-- We need minimal data to prove it is the report, or maybe not? 
                  User said "share semua... sesuai dengan datanya". 
                  I will put the data very subtly or just the images if they really hate the frame. 
                  But 'sesuai data' implies he wants the text caption. The text caption is in the WA msg. 
                  The IMAGE should probably just be the images. -->

            <div id="shareImagesGrid" class="d-flex flex-column gap-2">
                <!-- Images injected by JS -->
            </div>
            
            <!-- Hidden Data container for potential future use or debugging -->
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
            // Setup Text Template
            const text = `*Laporan Kegiatan DINSOS*\nNama Klien: ${nama}\nKegiatan: ${kegiatan}\nTanggal: ${tanggal}`;
            
            // Format Photo List
            const photoList = Array.isArray(photos) ? photos : (photos ? [photos] : []);
            let nativeShareSuccess = false;

            // -------------------------------------------------------------
            // STRATEGY 1: Native "Direct" Share (Mobile / Supported Desktop)
            // -------------------------------------------------------------
            if (navigator.share && photoList.length > 0) {
                try {
                    // Prepare Files
                    const filePromises = photoList.map(async (src, i) => {
                        const response = await fetch(src);
                        const blob = await response.blob();
                        return new File([blob], `Dokumentasi-${i+1}.jpg`, { type: 'image/jpeg' });
                    });
                    const files = await Promise.all(filePromises);

                    if (navigator.canShare({ files })) {
                        // Attempt to copy text silently as backup
                        try { await navigator.clipboard.writeText(text); } catch(e){}

                        await navigator.share({
                            files: files,
                            title: 'Laporan Kegiatan',
                            text: text 
                        });
                        nativeShareSuccess = true; // Mark as done if successful
                    }
                } catch (err) {
                    if (err.name === 'AbortError') {
                         nativeShareSuccess = true; // User cancelled, don't show fallback
                    } else {
                        console.warn("Native share failed, falling back to desktop mode...", err);
                    }
                }
            }

            // ---------------------------------------------------------
            // STRATEGY 2: Desktop / Fallback (Stitch & Copy)
            // ---------------------------------------------------------
                // =========================================================
                // DESKTOP STRATEGY: STITCHED VERTICAL IMAGE
                // =========================================================
                // Reverting to the logic that was confirmed working.
                
                if (!nativeShareSuccess) {
                    const grid = document.getElementById('shareImagesGrid');
                    grid.innerHTML = '';
                    
                    document.getElementById('shareNama').innerText = nama;
                    document.getElementById('shareKegiatan').innerText = kegiatan;
                    document.getElementById('shareTanggal').innerText = tanggal;

                    if (photoList.length > 0) {
                        const imagePromises = photoList.map(src => {
                            return new Promise((resolve) => {
                                const img = document.createElement('img');
                                img.crossOrigin = "anonymous";
                                img.src = src;
                                img.className = 'w-100 rounded-2 shadow-sm mb-2';
                                img.style.objectFit = 'contain';
                                img.onload = resolve;
                                img.onerror = resolve;
                                grid.appendChild(img);
                            });
                        });
                        await Promise.all(imagePromises);
                    } else {
                        grid.innerHTML = '<div class="text-center p-5 text-muted">No Image</div>';
                    }

                    // Render Canvas
                    const canvas = await html2canvas(document.getElementById('shareCard'), {
                        useCORS: true,
                        scale: 2,
                        backgroundColor: '#ffffff'
                    });

                    canvas.toBlob(async (blob) => {
                        try {
                            const item = new ClipboardItem({ "image/png": blob });
                            await navigator.clipboard.write([item]);
                            
                            const waUrl = `https://web.whatsapp.com/send?text=${encodeURIComponent(text)}`;
                            
                            if(confirm("✅ Foto (Stitched) disalin ke Clipboard!\n\nKlik OK -> WhatsApp Terbuka -> PASTE (Ctrl+V).")) {
                                window.open(waUrl, '_blank');
                            }
                        } catch (err) {
                            alert("Gagal copy gambar. Silakan download manual.");
                        }
                    });
                }

        } catch (error) {
            console.error('Final Share Error:', error);
            // alert('Gagal sharing.'); // Silent fail better than spamming alerts
        } finally {
            shareBtn.innerHTML = originalContent;
            shareBtn.disabled = false;
        }
    }

    function showGallery(photos) {
        const carouselInner = document.getElementById('carouselInner');
        carouselInner.innerHTML = ''; // Clear previous

        photos.forEach((src, index) => {
            const isActive = index === 0 ? 'active' : '';
            const item = document.createElement('div');
            item.className = `carousel-item ${isActive}`;
            item.innerHTML = `<img src="${src}" class="d-block w-100 rounded-4" style="max-height: 80vh; object-fit: contain; background: rgba(0,0,0,0.8);">`;
            carouselInner.appendChild(item);
        });

        const myModal = new bootstrap.Modal(document.getElementById('imageModal'));
        myModal.show();
    }
</script>
@endsection
