@forelse($activities as $index => $activity)
@php
$photoUrls = $activity->photos->pluck('foto_path')->map(fn($p) => Storage::url($p))->toArray();
if(empty($photoUrls) && $activity->foto_path) $photoUrls[] = Storage::url($activity->foto_path);
@endphp
<tr>
    {{-- No. Urut --}}
    <td class="px-4 fw-bold text-center text-muted">{{ $loop->iteration }}</td>

    {{-- Foto --}}
    <td class="px-4">
        @if($activity->foto_path)
        <img src="{{ Storage::url($activity->foto_path) }}" class="rounded-circle border border-2 border-primary"
            width="45" height="45" style="object-fit: cover; cursor: pointer;" onclick='showGallery(@json($photoUrls))'>
        @else
        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white"
            style="width: 45px; height: 45px;">
            <i class="fas fa-image"></i>
        </div>
        @endif
    </td>

    {{-- Tanggal + Waktu (AM/PM) --}}
    <td class="px-4 fw-bold text-secondary">
        {{ \Carbon\Carbon::parse($activity->tanggal)->translatedFormat('d M Y') }}<br>
        <small class="text-muted">{{ \Carbon\Carbon::parse($activity->tanggal)->format('h:i A') }}</small>
    </td>

    {{-- Nama Klien --}}
    <td class="px-4 fw-bold text-dark">{{ $activity->nama }}</td>

    {{-- Jenis Kelamin --}}
    <td class="px-4">
        @if($activity->jenis_kelamin === 'Laki-laki')
        <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 rounded-pill">
            <i class="fas fa-mars me-1"></i> L
        </span>
        @else
        <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-pill">
            <i class="fas fa-venus me-1"></i> P
        </span>
        @endif
    </td>

    {{-- Kategori --}}
    <td class="px-4">
        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">{{ $activity->kategori
            }}</span>
    </td>

    {{-- Detail --}}
    <td class="px-4 text-muted small" style="max-width: 180px;">
        {{ Str::words($activity->kegiatan, 4, '...') }}
    </td>

    {{-- Petugas --}}
    <td class="px-4 text-dark small">
        <span class="fw-semibold">{{ $activity->user?->name ?? '-' }}</span><br>
        <small class="text-muted">{{ $activity->user?->jenis_user ?? '' }}</small>
    </td>

    {{-- Status --}}
    <td class="px-4">
        @if($activity->status === 'Selesai')
        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
            <i class="fas fa-check-circle me-1"></i> Selesai
        </span>
        @else
        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">
            <i class="fas fa-spinner me-1"></i> Sedang Proses
        </span>
        @endif
    </td>

    {{-- Aksi --}}
    <td class="px-4 text-end">
        <div class="d-flex justify-content-end align-items-center gap-2 flex-nowrap">
            {{-- Left side: Status + Hapus --}}
            <div class="d-flex gap-1 flex-wrap">
                {{-- Update Status --}}
                @if($activity->status === 'Sedang Proses' || auth()->user()->isAdmin())
                <form action="{{ route('activities.updateStatus', $activity->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    @if($activity->status === 'Sedang Proses')
                    <input type="hidden" name="status" value="Selesai">
                    <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3">
                        <i class="fas fa-check me-1"></i> Selesaikan
                    </button>
                    @elseif(auth()->user()->isAdmin())
                    <input type="hidden" name="status" value="Sedang Proses">
                    <button type="submit" class="btn btn-sm btn-outline-warning rounded-pill px-3">
                        <i class="fas fa-undo me-1"></i> Proses
                    </button>
                    @endif
                </form>
                @endif

                {{-- Hapus (Admin only) --}}
                @if(auth()->user()->isAdmin())
                <form action="{{ route('activities.destroy', $activity->id) }}" method="POST"
                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                        <i class="fas fa-trash-alt me-1"></i> Hapus
                    </button>
                </form>
                @endif
            </div>

            {{-- Right side: Share WA (bigger, only when Selesai) --}}
            @if($activity->status == 'Selesai')
            <button class="btn btn-sm btn-success rounded-pill px-3 py-2 share-btn" onclick='shareToWhatsapp(
                        {{ $activity->id }},
                        "{{ addslashes($activity->nama) }}",
                        "{{ addslashes($activity->kegiatan) }}",
                        @json($photoUrls),
                        "{{ \Carbon\Carbon::parse($activity->tanggal)->translatedFormat(' d F Y') }}" )'>
                <i class="fab fa-whatsapp me-1"></i> Share
            </button>
            @endif
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="10" class="text-center py-5">
        <h5 class="text-muted opacity-50 mb-0">Tidak ada data kegiatan.</h5>
    </td>
</tr>
@endforelse