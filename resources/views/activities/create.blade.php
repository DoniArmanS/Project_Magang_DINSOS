@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">Buat Laporan Baru</h2>
                <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card glass-card border-0 shadow-lg">
                <div class="card-body p-5">
                    <form action="{{ route('activities.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row g-4">
                            <!-- Nama Klien (Renamed from Nama Pelapor) -->
                            <div class="col-md-6">
                                <label for="nama" class="form-label fw-bold text-uppercase text-secondary small">Nama Klien</label>
                                <input type="text" class="form-control form-control-lg bg-light border-0" id="nama" name="nama" placeholder="Masukkan nama klien..." required>
                            </div>

                            <!-- Tanggal -->
                            <div class="col-md-6">
                                <label for="tanggal" class="form-label fw-bold text-uppercase text-secondary small">Tanggal Kegiatan</label>
                                <input type="date" class="form-control form-control-lg bg-light border-0" id="tanggal" name="tanggal" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <!-- Kategori -->
                            <div class="col-12">
                                <label class="form-label fw-bold text-uppercase text-secondary small mb-3">Kategori Pelayanan</label>
                                <div class="row g-3">
                                    <div class="col-6 col-md-3">
                                        <input type="radio" class="btn-check" name="kategori" id="cat_tksk" value="TKSK" required>
                                        <label class="btn btn-outline-primary w-100 py-3 rounded-3 fw-bold" for="cat_tksk">TKSK</label>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <input type="radio" class="btn-check" name="kategori" id="cat_psm" value="PSM">
                                        <label class="btn btn-outline-primary w-100 py-3 rounded-3 fw-bold" for="cat_psm">PSM</label>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <input type="radio" class="btn-check" name="kategori" id="cat_odgj" value="ODGJ">
                                        <label class="btn btn-outline-primary w-100 py-3 rounded-3 fw-bold" for="cat_odgj">ODGJ</label>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <input type="radio" class="btn-check" name="kategori" id="cat_disabilitas" value="Disabilitas">
                                        <label class="btn btn-outline-primary w-100 py-3 rounded-3 fw-bold" for="cat_disabilitas">Disabilitas</label>
                                    </div>
                                     <div class="col-6 col-md-3">
                                        <input type="radio" class="btn-check" name="kategori" id="cat_admin" value="Administrasi">
                                        <label class="btn btn-outline-primary w-100 py-3 rounded-3 fw-bold" for="cat_admin">Administrasi</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-12">
                                <label class="form-label fw-bold text-uppercase text-secondary small">Status Kegiatan</label>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status1" value="Selesai" checked>
                                        <label class="form-check-label fw-bold text-success" for="status1">
                                            <i class="fas fa-check-circle me-1"></i> Selesai
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status2" value="Pending">
                                        <label class="form-check-label fw-bold text-warning" for="status2">
                                            <i class="fas fa-clock me-1"></i> Pending
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Detail -->
                            <div class="col-12">
                                <label for="kegiatan" class="form-label fw-bold text-uppercase text-secondary small">Detail Kegiatan</label>
                                <textarea class="form-control bg-light border-0 rounded-3 p-3" id="kegiatan" name="kegiatan" rows="4" placeholder="Jelaskan rincian kegiatan..." required></textarea>
                            </div>

                 <!-- Bukti Foto -->
        <div class="col-12">
            <div class="glass-card p-4">
                <label class="form-label fw-bold mb-3">Foto Dokumentasi</label>
                <div class="upload-area border-2 border-dashed rounded-3 p-5 text-center position-relative" 
                     id="dropZone"
                     style="border-color: rgba(255,255,255,0.2); background: rgba(255,255,255,0.02); transition: all 0.3s; cursor: pointer;">
                    
                    <input type="file" name="foto" id="fotoInput" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" 
                           accept="image/*" onchange="previewImage(this)">
                    
                    <div id="uploadPlaceholder">
                        <div class="mb-3">
                            <i class="fas fa-cloud-upload-alt fa-3x text-white-50"></i>
                        </div>
                        <h6 class="fw-bold">Drag & Drop atau Klik untuk Upload</h6>
                        <small class="text-white-50">Mendukung format JPG, PNG (Max 10MB)</small>
                    </div>

                    <div id="imagePreviewContainer" class="d-none position-relative d-inline-block">
                        <img id="imagePreview" src="" class="img-fluid rounded-3 shadow-lg" style="max-height: 300px;">
                        <button type="button" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 m-2" 
                                onclick="removeImage(event)" style="width: 30px; height: 30px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                @error('foto')
                    <div class="text-danger mt-2 small">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-12 text-end mt-4">
            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-pill shadow-lg hover-scale">
                <i class="fas fa-save me-2"></i> Simpan Laporan
            </button>
        </div>
    </form>
</div>
            </div>
        </div>
    </div>
</div>

<script>
    const dropZone = document.getElementById('dropZone');
    const fotoInput = document.getElementById('fotoInput');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const imagePreview = document.getElementById('imagePreview');

    // Drag & Drop Effects
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '#4361ee';
        dropZone.style.background = 'rgba(67, 97, 238, 0.1)';
    });

    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = 'rgba(255,255,255,0.2)';
        dropZone.style.background = 'rgba(255,255,255,0.02)';
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = 'rgba(255,255,255,0.2)';
        dropZone.style.background = 'rgba(255,255,255,0.02)';
        
        if(e.dataTransfer.files.length) {
            fotoInput.files = e.dataTransfer.files;
            previewImage(fotoInput);
        }
    });

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                uploadPlaceholder.classList.add('d-none');
                imagePreviewContainer.classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeImage(event) {
        event.preventDefault(); // Prevent opening file dialog
        event.stopPropagation(); // Stop event bubbling
        
        fotoInput.value = ''; // Clear input
        imagePreview.src = '';
        imagePreviewContainer.classList.add('d-none');
        uploadPlaceholder.classList.remove('d-none');
    }
</script>
@endsection
