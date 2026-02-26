@forelse($activities as $activity)
<tr>
    <td class="px-4">
        @if($activity->foto_path)
            @php
                $photoUrls = $activity->photos->pluck('foto_path')->map(fn($p) => Storage::url($p))->toArray();
                // Fallback to main photo if empty (legacy coverage)
                if(empty($photoUrls) && $activity->foto_path) $photoUrls[] = Storage::url($activity->foto_path);
            @endphp
            <img src="{{ Storage::url($activity->foto_path) }}" 
                 class="rounded-circle border border-2 border-primary" 
                 width="45" height="45" 
                 style="object-fit: cover; cursor: pointer;" 
                 onclick='showGallery(@json($photoUrls))'>
        @else
            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white" style="width: 45px; height: 45px;">
                <i class="fas fa-image"></i>
            </div>
        @endif
    </td>
    <td class="px-4 fw-bold text-secondary">{{ \Carbon\Carbon::parse($activity->tanggal)->translatedFormat('d M Y, H:i') }}</td>
    <td class="px-4 fw-bold text-dark">{{ $activity->nama }}</td>
    <td class="px-4">
        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">{{ $activity->kategori }}</span>
    </td>
    <td class="px-4 text-muted small" style="max-width: 200px;">
        {{ Str::words($activity->kegiatan, 4, '...') }}
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
                    onclick='shareToWhatsapp(
                        {{ $activity->id }}, 
                        "{{ addslashes($activity->nama) }}", 
                        "{{ addslashes($activity->kegiatan) }}", 
                        @json($photoUrls),
                        "{{ \Carbon\Carbon::parse($activity->tanggal)->translatedFormat('d F Y') }}"
                    )'>
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
        <h5 class="text-muted opacity-50 mb-0">Tidak ada data kegiatan.</h5>
    </td>
</tr>
@endforelse
