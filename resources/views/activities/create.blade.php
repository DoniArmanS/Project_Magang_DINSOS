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

                            <!-- Upload -->
                            <div class="col-12">
                                <label class="form-label fw-bold text-uppercase text-secondary small">Foto Dokumentasi</label>
                                <div class="border-2 border-dashed border-secondary rounded-3 p-5 text-center bg-light position-relative" id="drop-zone">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-secondary mb-3"></i>
                                    <h5>Drag & Drop atau Klik untuk Upload</h5>
                                    <p class="text-muted small">Mendukung Format JPG, PNG (Max 10MB)</p>
                                    <input type="file" name="foto" id="foto" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" onchange="previewImage(this)">
                                </div>
                                <div class="mt-3 text-center">
                                    <img id="preview" class="img-fluid rounded-3 shadow-sm d-none" style="max-height: 200px;">
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="col-12 text-end mt-4">
                                <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-lg fw-bold">
                                    <i class="fas fa-save me-2"></i> Simpan Laporan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('preview');
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
