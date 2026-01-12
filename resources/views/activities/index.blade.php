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
                            <div class="d-flex justify-content-end gap-2">
                                @if($activity->status == 'Selesai')
                                    <button class="btn btn-sm btn-outline-success rounded-pill px-3" 
                                        onclick="shareToWhatsapp(
                                            {{ $activity->id }}, 
                                            '{{ addslashes($activity->nama) }}', 
                                            '{{ addslashes($activity->kegiatan) }}', 
                                            '{{ $activity->foto_path ? "/storage/" . $activity->foto_path : "" }}',
                                            '{{ \Carbon\Carbon::parse($activity->tanggal)->translatedFormat('d F Y') }}'
                                        )">
                                        <i class="fab fa-whatsapp me-1"></i> Share
                                    </button>
                                @endif
                                
                                <form action="{{ route('activities.destroy', $activity->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                        <i class="fas fa-trash-alt me-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
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

    <!-- Hidden Share Card Template -->
    <div id="shareCardContainer" style="position: absolute; left: -9999px; top: 0;">
        <div id="shareCard" class="p-4 text-white" style="width: 400px; background: radial-gradient(circle at top right, #283593, #1a237e); border-radius: 15px; font-family: 'Outfit', sans-serif;">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                    <i class="fas fa-chart-line text-dark fa-lg"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold">SIM-<span style="color: #ffca28;">PPKS</span></h4>
                    <small class="text-white-50">Laporan Kegiatan Dinas Sosial</small>
                </div>
            </div>
            
            <div class="bg-white bg-opacity-10 p-3 rounded-3 mb-3 border border-white border-opacity-25">
                <h5 class="fw-bold mb-3 border-bottom border-white border-opacity-25 pb-2">Detail Laporan</h5>
                <div class="mb-2">
                    <small class="text-white-50 d-block">Nama Klien</small>
                    <span class="fw-medium" id="shareNama"></span>
                </div>
                <div class="mb-2">
                    <small class="text-white-50 d-block">Kegiatan</small>
                    <span class="fw-medium" id="shareKegiatan"></span>
                </div>
                <div>
                    <small class="text-white-50 d-block">Tanggal</small>
                    <span class="fw-medium" id="shareTanggal"></span>
                </div>
            </div>

            <div class="mb-3">
                <img id="shareImage" src="" class="img-fluid rounded-3 w-100 object-fit-cover" style="height: 250px; display: none;">
            </div>

            <div class="text-center pt-2 border-top border-white border-opacity-25">
                <small class="text-white-50">Project Magang DINSOS &copy; {{ date('Y') }}</small>
            </div>
        </div>
    </div>
</div>

<script>
    async function shareToWhatsapp(id, nama, kegiatan, fotoPath, tanggal) {
        const shareBtn = event.currentTarget;
        const originalContent = shareBtn.innerHTML;
        shareBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
        shareBtn.disabled = true;

        try {
            // Setup Text
            const text = `*Laporan Kegiatan DINSOS*\nNama Klien: ${nama}\nKegiatan: ${kegiatan}\nTanggal: ${tanggal}`;
            
            // Setup Card Data
            document.getElementById('shareNama').innerText = nama;
            document.getElementById('shareKegiatan').innerText = kegiatan;
            document.getElementById('shareTanggal').innerText = tanggal;
            
            const imgEl = document.getElementById('shareImage');
            if(fotoPath) {
                imgEl.src = fotoPath; 
                imgEl.style.display = 'block';
                await new Promise((resolve) => {
                    if(imgEl.complete) resolve();
                    else imgEl.onload = resolve;
                });
            } else {
                imgEl.style.display = 'none';
            }

            // Generate Image
            const canvas = await html2canvas(document.getElementById('shareCard'), {
                useCORS: true,
                scale: 2
            });

            canvas.toBlob(async (blob) => {
                const file = new File([blob], `Laporan-${nama}.png`, { type: 'image/png' });
                const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

                if (isMobile && navigator.canShare && navigator.canShare({ files: [file] })) {
                    // Mobile: Native Share
                    try {
                        await navigator.share({
                            files: [file],
                            title: 'Laporan Kegiatan',
                            text: text
                        });
                    } catch (err) {
                         if (err.name !== 'AbortError') copyToClipboardAndOpenWA(blob, text);
                    }
                } else {
                    // Desktop: Copy Image to Clipboard
                    try {
                        const item = new ClipboardItem({ "image/png": blob });
                        await navigator.clipboard.write([item]);
                        
                        // Show Instructions
                        alert("✅ Foto berhasil disalin!\n\nTekan 'OK' lalu langsung PASTE (Ctrl + V) di kolom chat WhatsApp.");
                        
                        // Open WhatsApp Web
                        const encodedText = encodeURIComponent(text);
                        window.open(`https://web.whatsapp.com/send?text=${encodedText}`, '_blank');
                    } catch (err) {
                        console.error("Clipboard failed:", err);
                        alert("Gagal menyalin foto otomatis (Browser tidak mendukung). Silakan kirim foto manual.");
                        
                        // Open WA with Text Only (No Link)
                        const encodedText = encodeURIComponent(text);
                        window.open(`https://web.whatsapp.com/send?text=${encodedText}`, '_blank');
                    }
                }
            });

        } catch (error) {
            console.error('Share failed:', error);
            alert('Terjadi kesalahan saat memproses gambar.');
        } finally {
            shareBtn.innerHTML = originalContent;
            shareBtn.disabled = false;
        }
    }

    function showImage(src) {
        document.getElementById('modalImage').src = src;
        new bootstrap.Modal(document.getElementById('imageModal')).show();
    }
</script>
@endsection
