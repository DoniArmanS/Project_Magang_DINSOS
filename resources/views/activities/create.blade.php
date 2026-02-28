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
                            <!-- Nama Klien -->
                            <div class="col-md-6">
                                <label for="nama" class="form-label fw-bold text-uppercase text-secondary small">Nama
                                    Klien</label>
                                <input type="text" class="form-control form-control-lg bg-light border-0" id="nama"
                                    name="nama" placeholder="Masukkan nama klien..." value="{{ old('nama') }}" required>
                                @error('nama')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <!-- Jenis Kelamin -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-uppercase text-secondary small">Jenis
                                    Kelamin</label>
                                <div class="d-flex gap-4 mt-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk1"
                                            value="Laki-laki" {{ old('jenis_kelamin')=='Laki-laki' ? 'checked' : '' }}
                                            required>
                                        <label class="form-check-label fw-bold text-primary" for="jk1">
                                            <i class="fas fa-mars me-1"></i> Laki-laki
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk2"
                                            value="Perempuan" {{ old('jenis_kelamin')=='Perempuan' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold text-danger" for="jk2">
                                            <i class="fas fa-venus me-1"></i> Perempuan
                                        </label>
                                    </div>
                                </div>
                                @error('jenis_kelamin')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <!-- Tanggal Lahir (Opsional) -->
                            <div class="col-md-6">
                                <label for="tanggal_lahir"
                                    class="form-label fw-bold text-uppercase text-secondary small">
                                    Tanggal Lahir <span class="text-muted fw-normal">(Opsional)</span>
                                </label>
                                <input type="date" class="form-control form-control-lg bg-light border-0"
                                    id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                            </div>

                            <!-- Tempat Tinggal (Opsional) -->
                            <div class="col-md-6">
                                <label for="tempat_tinggal"
                                    class="form-label fw-bold text-uppercase text-secondary small">
                                    Tempat Tinggal <span class="text-muted fw-normal">(Opsional)</span>
                                </label>
                                <input type="text" class="form-control form-control-lg bg-light border-0"
                                    id="tempat_tinggal" name="tempat_tinggal" placeholder="Masukkan alamat..."
                                    value="{{ old('tempat_tinggal') }}">
                            </div>

                            <!-- Waktu Kegiatan -->
                            <div class="col-md-6">
                                <label for="tanggal_display"
                                    class="form-label fw-bold text-uppercase text-secondary small">Waktu
                                    Kegiatan</label>
                                <input type="text" class="form-control form-control-lg bg-light border-0"
                                    id="tanggal_display" placeholder="Pilih tanggal & waktu..." required readonly>
                                <input type="hidden" id="tanggal" name="tanggal">
                                @error('tanggal')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <!-- Kategori -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-uppercase text-secondary small mb-2">Kategori
                                    Pelayanan</label>
                                <select class="form-select form-select-lg bg-light border-0" name="kategori" required>
                                    <option value="" selected disabled>Pilih Kategori...</option>
                                    <option value="Terlantar" {{ old('kategori')=='Terlantar' ? 'selected' :'' }}>
                                        Terlantar</option>
                                    <option value="Tuna Susila" {{ old('kategori')=='Tuna Susila' ? 'selected' :'' }}>
                                        Tuna Susila</option>
                                    <option value="ODGJ" {{ old('kategori')=='ODGJ' ? 'selected' :'' }}>ODGJ (Orang
                                        Dengan Gangguan Jiwa)</option>
                                    <option value="Anak" {{ old('kategori')=='Anak' ? 'selected' :'' }}>Anak</option>
                                    <option value="Lansia" {{ old('kategori')=='Lansia' ? 'selected' :'' }}>Lansia
                                    </option>
                                </select>
                                @error('kategori')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <!-- Status Kegiatan -->
                            <div class="col-12">
                                <label class="form-label fw-bold text-uppercase text-secondary small">Status
                                    Kegiatan</label>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status1"
                                            value="Selesai" {{ old('status', 'Selesai' )=='Selesai' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold text-success" for="status1">
                                            <i class="fas fa-check-circle me-1"></i> Selesai
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status2"
                                            value="Sedang Proses" {{ old('status')=='Sedang Proses' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold text-warning" for="status2">
                                            <i class="fas fa-spinner me-1"></i> Sedang Proses
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Detail Kegiatan -->
                            <div class="col-12">
                                <label for="kegiatan"
                                    class="form-label fw-bold text-uppercase text-secondary small">Detail
                                    Kegiatan</label>
                                <textarea class="form-control bg-light border-0 rounded-3 p-3" id="kegiatan"
                                    name="kegiatan" rows="4" placeholder="Jelaskan rincian kegiatan..."
                                    required>{{ old('kegiatan') }}</textarea>
                                @error('kegiatan')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <!-- Bukti Foto -->
                            <div class="col-12">
                                <div class="glass-card p-4">
                                    <label class="form-label fw-bold mb-3">Foto Dokumentasi</label>
                                    <div class="upload-area border-2 border-dashed rounded-3 p-5 text-center position-relative"
                                        id="dropZone"
                                        style="border-color: rgba(255,255,255,0.2); background: rgba(255,255,255,0.02); transition: all 0.3s; cursor: pointer;">

                                        <input type="file" name="foto[]" id="fotoInput"
                                            class="position-absolute top-0 start-0 w-100 h-100 opacity-0"
                                            accept="image/*" multiple onchange="previewImage(this)">

                                        <div id="uploadPlaceholder">
                                            <div class="mb-3"><i
                                                    class="fas fa-cloud-upload-alt fa-3x text-white-50"></i></div>
                                            <h6 class="fw-bold">Drag & Drop atau Klik untuk Upload</h6>
                                            <small class="text-white-50">Mendukung format JPG, PNG (Max 10MB, maks 4
                                                foto)</small>
                                        </div>

                                        <div id="imagePreviewContainer" class="row g-2 d-none mt-3"></div>
                                    </div>
                                    @error('foto')<div class="text-danger mt-2 small">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-12 text-end mt-4">
                                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-pill shadow-lg">
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
    const dropZone = document.getElementById('dropZone');
    const fotoInput = document.getElementById('fotoInput');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    let dt = new DataTransfer();

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
        if (e.dataTransfer.files.length) handleFiles(e.dataTransfer.files);
    });

    function previewImage(input) {
        if (input.files && input.files.length > 0) handleFiles(input.files);
    }

    function handleFiles(files) {
        for (let i = 0; i < files.length; i++) dt.items.add(files[i]);
        fotoInput.files = dt.files;
        renderPreview();
    }

    function renderPreview() {
        imagePreviewContainer.innerHTML = '';
        if (dt.files.length > 0) {
            uploadPlaceholder.classList.add('d-none');
            imagePreviewContainer.classList.remove('d-none');
            for (let i = 0; i < dt.files.length; i++) {
                const file = dt.files[i];
                const reader = new FileReader();
                reader.onload = function (e) {
                    const col = document.createElement('div');
                    col.className = 'col-12 col-md-4 mb-2 position-relative fade-in';
                    col.innerHTML = `
                        <div class="position-relative overflow-hidden rounded-3 shadow-sm border border-secondary border-opacity-25" style="aspect-ratio: 1/1;">
                            <img src="${e.target.result}" class="w-100 h-100" style="object-fit: cover;">
                            <button type="button" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 m-2 shadow-sm"
                                    onclick="removeSingleFile(${i})" style="width: 32px; height: 32px; z-index: 10;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>`;
                    imagePreviewContainer.appendChild(col);
                };
                reader.readAsDataURL(file);
            }
        } else {
            uploadPlaceholder.classList.remove('d-none');
            imagePreviewContainer.classList.add('d-none');
        }
    }

    function removeSingleFile(index) {
        const newDt = new DataTransfer();
        for (let i = 0; i < dt.files.length; i++) {
            if (i !== index) newDt.items.add(dt.files[i]);
        }
        dt = newDt;
        fotoInput.files = dt.files;
        renderPreview();
    }
</script>

<script>
    // Flatpickr AM/PM untuk input waktu kegiatan
    document.addEventListener('DOMContentLoaded', function () {
        const hiddenField = document.getElementById('tanggal');
        const pad = n => String(n).padStart(2, '0');

        // Set default now into hidden field
        const now = new Date();
        hiddenField.value = `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(now.getDate())} ${pad(now.getHours())}:${pad(now.getMinutes())}`;

        flatpickr('#tanggal_display', {
            enableTime: true,
            time_24hr: false,
            dateFormat: 'd/m/Y h:i K',  // flatpickr K = AM/PM uppercase
            defaultDate: now,
            onChange: function (selectedDates) {
                if (selectedDates.length > 0) {
                    const d = selectedDates[0];
                    hiddenField.value = `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())} ${pad(d.getHours())}:${pad(d.getMinutes())}`;
                }
            }
        });
    });
</script>

<style>
    .fade-in {
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection