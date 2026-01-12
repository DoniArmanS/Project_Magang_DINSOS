@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flexflex-col sm:flex-row justify-between items-center mb-6 space-y-4 sm:space-y-0">
        <h2 class="text-2xl font-bold text-gray-900">Data Kegiatan</h2>
        
        <form method="GET" action="{{ route('activities.index') }}" class="flex space-x-2">
            <select name="kategori" onchange="this.form.submit()" class="block w-full sm:w-auto pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm border">
                <option value="All" {{ request('kategori') == 'All' ? 'selected' : '' }}>Semua Kategori</option>
                <option value="ODGJ" {{ request('kategori') == 'ODGJ' ? 'selected' : '' }}>ODGJ</option>
                <option value="Terlantar" {{ request('kategori') == 'Terlantar' ? 'selected' : '' }}>Terlantar</option>
                <option value="Mr X" {{ request('kategori') == 'Mr X' ? 'selected' : '' }}>Mr X</option>
                <option value="Lainnya" {{ request('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>

            <select name="period" onchange="this.form.submit()" class="block w-full sm:w-auto pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm border">
                <option value="" {{ request('period') == '' ? 'selected' : '' }}>Semua Waktu</option>
                <option value="Hari Ini" {{ request('period') == 'Hari Ini' ? 'selected' : '' }}>Hari Ini</option>
                <option value="Minggu Ini" {{ request('period') == 'Minggu Ini' ? 'selected' : '' }}>Minggu Ini</option>
                <option value="Bulan Ini" {{ request('period') == 'Bulan Ini' ? 'selected' : '' }}>Bulan Ini</option>
                <option value="Tahun Ini" {{ request('period') == 'Tahun Ini' ? 'selected' : '' }}>Tahun Ini</option>
            </select>
            
            <a href="{{ route('activities.export', request()->all()) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none transition">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Excel
            </a>
        </form>
    </div>

    <!-- Mobile View (Cards) -->
    <div class="block sm:hidden space-y-4">
        @forelse($activities as $activity)
            <div class="bg-white shadow rounded-lg p-4 space-y-3">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            {{ $activity->kategori }}
                        </span>
                        <h3 class="text-lg font-medium text-gray-900 mt-1">{{ $activity->nama }}</h3>
                        <p class="text-sm text-gray-500">{{ $activity->tanggal->format('d/m/Y') }}</p>
                    </div>
                    @if($activity->foto_path)
                        <img src="{{ Storage::url($activity->foto_path) }}" alt="Foto" class="h-16 w-16 object-cover rounded-md">
                    @endif
                </div>
                <div>
                    <p class="text-sm text-gray-700 line-clamp-2">{{ $activity->kegiatan }}</p>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                    <span class="text-xs font-medium text-gray-500">Status: {{ $activity->status }}</span>
                    <button onclick="shareWhatsapp('{{ $activity->nama }}', '{{ $activity->kategori }}', '{{ $activity->kegiatan }}', '{{ $activity->status }}', '{{ $activity->tanggal->format('d/m/Y') }}')" class="inline-flex items-center text-sm text-green-600 font-medium hover:text-green-700">
                        <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                           <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        Share WA
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center py-10 bg-white rounded-lg shadow">
                <p class="text-gray-500">Belum ada data kegiatan.</p>
            </div>
        @endforelse
    </div>

    <!-- Desktop View (Table) -->
    <div class="hidden sm:block align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kegiatan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($activities as $index => $activity)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($activity->foto_path)
                                <img src="{{ Storage::url($activity->foto_path) }}" alt="Foto" class="h-10 w-10 object-cover rounded-md cursor-pointer hover:h-20 hover:w-20 transition-all z-10 relative">
                            @else
                                <span class="text-gray-400 text-xs">No Img</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $activity->tanggal->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $activity->nama }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 overflow-hidden max-w-xs truncate">
                            {{ $activity->kegiatan }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $activity->kategori }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $activity->status }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="shareWhatsapp('{{ $activity->nama }}', '{{ $activity->kategori }}', '{{ $activity->kegiatan }}', '{{ $activity->status }}')" class="text-green-600 hover:text-green-900 flex items-center">
                                <svg class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                   <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                                Share
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            Belum ada data.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

    <!-- Hidden Card Template -->
    <div id="capture-container" class="fixed top-0 left-0 -z-50 opacity-0 pointer-events-none">
        <div id="activity-card" class="bg-gradient-to-br from-indigo-500 to-purple-600 w-[400px] p-6 text-white rounded-xl shadow-2xl relative overflow-hidden">
            <!-- Decorative Circles -->
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-32 h-32 bg-white opacity-10 rounded-full"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-32 h-32 bg-white opacity-10 rounded-full"></div>

            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4 border-b border-white/20 pb-4">
                    <h3 class="text-xl font-bold">Laporan Kegiatan</h3>
                    <span class="text-sm font-light" id="card-date"></span>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-xs uppercase tracking-wider text-indigo-200">Pelapor</p>
                        <p class="font-semibold text-lg" id="card-nama"></p>
                    </div>
                    
                    <div class="flex gap-4">
                         <div>
                            <p class="text-xs uppercase tracking-wider text-indigo-200">Kategori</p>
                            <span class="inline-block bg-white/20 px-2 py-1 rounded text-sm font-medium mt-1" id="card-kategori"></span>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wider text-indigo-200">Status</p>
                            <span class="inline-block bg-green-500/20 px-2 py-1 rounded text-sm font-medium mt-1 border border-green-400/30" id="card-status"></span>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-wider text-indigo-200">Kegiatan</p>
                        <p class="text-sm leading-relaxed mt-1" id="card-kegiatan"></p>
                    </div>

                    <div class="pt-4 mt-4 border-t border-white/20 flex justify-between items-end">
                         <p class="text-xs italic text-indigo-200">PEMKOT MEDAN LAPOR BOSS</p>
                         <p class="text-xs opacity-75">Generated by Activity Tracker</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function shareWhatsapp(nama, kategori, kegiatan, status, tanggal) {
            // Populate hidden card
            document.getElementById('card-nama').innerText = nama;
            document.getElementById('card-kategori').innerText = kategori;
            document.getElementById('card-kegiatan').innerText = kegiatan;
            document.getElementById('card-status').innerText = status;
            document.getElementById('card-date').innerText = tanggal;

            // Simple text template
            const text = `*PEMKOT MEDAN LAPOR BOSS*\n\n` +
                        `*Pelapor:* ${nama}\n` +
                        `*Kategori:* ${kategori}\n` +
                        `*Status:* ${status}\n` +
                        `*Uraian Kegiatan:*\n${kegiatan}\n\n` +
                        `_Sent from Activity Tracker_`;
            
            const encodedText = encodeURIComponent(text);
            const waUrl = `https://wa.me/?text=${encodedText}`;

            // Logic to capture and download image, then open WA
            // We ask user confirmation somewhat implicitly by doing it on click
            html2canvas(document.querySelector("#activity-card"), {
                backgroundColor: null,
                scale: 2 // High Resolution
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = `Laporan-${nama}-${tanggal}.png`;
                link.href = canvas.toDataURL();
                link.click();
                
                // Open WA after short delay
                setTimeout(() => {
                    window.open(waUrl, '_blank');
                }, 1000);
            });
        }
    </script>
@endsection
