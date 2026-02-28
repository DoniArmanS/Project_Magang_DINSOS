<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class ActivityController extends Controller
{
    /**
     * Display the dashboard with stats.
     */
    public function dashboard(Request $request)
    {
        $totalActivities = Activity::count();
        $completedTasks = Activity::where('status', 'Selesai')->count();
        $pendingTasks = Activity::where('status', 'Sedang Proses')->count();

        // Jika AJAX → return JSON chart data saja
        if ($request->ajax()) {
            return response()->json($this->buildChartData($request));
        }

        $chartData = $this->buildChartData($request);

        // Recent Activities
        $recentActivities = Activity::with('user')->latest()->take(5)->get();

        return view('home', compact(
            'totalActivities', 'completedTasks', 'pendingTasks',
            'recentActivities'
        ) + $chartData);
    }

    /**
     * Build chart data based on period/range filter
     */
    private function buildChartData(Request $request): array
    {
        $categories = [
            'Terlantar' => 'Terlantar',
            'Tuna Susila' => 'Tuna Susila',
            'ODGJ' => 'ODGJ',
            'Anak' => 'Anak',
            'Lansia' => 'Lansia',
        ];

        // Nama singkat untuk label X-axis
        $shortLabels = ['Terlantar', 'Tuna Susila', 'ODGJ', 'Anak', 'Lansia'];

        $colors = ['#4361ee', '#f72585', '#4cc9f0', '#48c78e', '#ffc107'];
        $bgColors = [
            'rgba(67,97,238,0.8)', 'rgba(247,37,133,0.8)',
            'rgba(76,201,240,0.8)', 'rgba(72,199,142,0.8)', 'rgba(255,193,7,0.8)'
        ];

        $period = $request->get('period', 'hari_ini');
        $startDate = null;
        $endDate = null;

        switch ($period) {
            case 'hari_ini':
                $startDate = Carbon::today('Asia/Jakarta');
                $endDate = Carbon::today('Asia/Jakarta');
                $subtitle = 'Hari ini, ' . $startDate->translatedFormat('d F Y');
                break;
            case 'minggu_ini':
                $startDate = Carbon::now('Asia/Jakarta')->startOfWeek();
                $endDate = Carbon::now('Asia/Jakarta')->endOfWeek();
                $subtitle = 'Minggu ini (' . $startDate->format('d') . '–' . $endDate->translatedFormat('d M Y') . ')';
                break;
            case 'bulan_ini':
                $startDate = Carbon::now('Asia/Jakarta')->startOfMonth();
                $endDate = Carbon::now('Asia/Jakarta')->endOfMonth();
                $subtitle = 'Bulan ' . $startDate->translatedFormat('F Y');
                break;
            case 'tahun_ini':
                $startDate = Carbon::now('Asia/Jakarta')->startOfYear();
                $endDate = Carbon::now('Asia/Jakarta')->endOfYear();
                $subtitle = 'Tahun ' . now()->year;
                break;
            case 'custom':
                $startDate = $request->get('start') ?Carbon::parse($request->get('start')) : Carbon::today('Asia/Jakarta')->subDays(6);
                $endDate = $request->get('end') ?Carbon::parse($request->get('end')) : Carbon::today('Asia/Jakarta');
                $subtitle = $startDate->format('d/m/Y') . ' – ' . $endDate->format('d/m/Y');
                break;
            default: // hari_ini
                $startDate = Carbon::today('Asia/Jakarta');
                $endDate = Carbon::today('Asia/Jakarta');
                $subtitle = 'Hari ini, ' . $startDate->translatedFormat('d F Y');
        }

        // Hitung total per kategori dalam rentang periode
        $counts = [];
        foreach (array_keys($categories) as $i => $cat) {
            $counts[] = Activity::where('kategori', $cat)
                ->whereBetween('tanggal', [
                $startDate->format('Y-m-d') . ' 00:00:00',
                $endDate->format('Y-m-d') . ' 23:59:59'
            ])->count();
        }

        $chartDatasets = [[
                'label' => 'Jumlah Kegiatan',
                'data' => $counts,
                'backgroundColor' => $bgColors,
                'borderColor' => $colors,
                'borderWidth' => 2,
                'borderRadius' => 6,
            ]];

        return [
            'chartLabels' => $shortLabels,
            'chartDatasets' => $chartDatasets,
            'chartSubtitle' => $subtitle ?? 'Hari ini',
        ];
    }


    /**
     * Display a listing of activities (with filters).
     */
    public function index(Request $request)
    {
        $query = Activity::with(['photos', 'user']);

        if ($request->filled('kategori') && $request->kategori != 'All') {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('status') && $request->status != 'All') {
            $query->where('status', $request->status);
        }

        $activities = $query->orderBy('tanggal', 'desc')->get();

        if ($request->ajax()) {
            return view('activities.partials.table_body', compact('activities'))->render();
        }

        return view('activities.index', compact('activities'));
    }

    /**
     * Export
     */
    public function export(Request $request)
    {
        $filename = 'Kegiatan.xlsx';

        $content = \Maatwebsite\Excel\Facades\Excel::raw(
            new \App\Exports\ActivityExport($request->all()),
            \Maatwebsite\Excel\Excel::XLSX
        );

        return response($content, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Show the form for creating a new activity.
     */
    public function create()
    {
        return view('activities.create');
    }

    /**
     * Store a newly created activity.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'kegiatan' => 'required|string',
            'status' => 'required|string',
            'kategori' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'nullable|date',
            'tempat_tinggal' => 'nullable|string|max:255',
            'foto' => 'array|max:4',
            'foto.*' => 'image|max:10240',
        ], [
            'foto.max' => 'Maksimal upload 4 foto.',
            'foto.*.max' => 'Ukuran foto maksimal 10MB.',
            'foto.*.image' => 'File harus berupa gambar.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
        ]);

        $activity = Activity::create([
            'user_id' => auth()->id(),
            'nama' => $validated['nama'],
            'tanggal' => $validated['tanggal'],
            'kegiatan' => $validated['kegiatan'],
            'status' => $validated['status'],
            'kategori' => $validated['kategori'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'tempat_tinggal' => $validated['tempat_tinggal'] ?? null,
            'foto_path' => null,
        ]);

        if ($request->hasFile('foto')) {
            // Pastikan folder public/activities ada
            $destinationPath = public_path('activities');
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            foreach ($request->file('foto') as $index => $file) {
                // Generate unique filename & move ke public/activities
                $filename = time() . '_' . $index . '_' . $file->getClientOriginalName();
                $file->move($destinationPath, $filename);
                $path = 'activities/' . $filename;

                // Save to activity_photos table
                \App\Models\ActivityPhoto::create([
                    'activity_id' => $activity->id,
                    'foto_path' => $path,
                ]);

                // Set First Image as Main Thumbnail (Legacy Support)
                if ($index === 0) {
                    $activity->update(['foto_path' => $path]);
                }
            }
        }

        return redirect()->route('activities.index')->with('success', 'Laporan kegiatan berhasil disimpan!');
    }

    /**
     * Update status (User: Sedang Proses → Selesai only; Admin: anything)
     */
    public function updateStatus(Request $request, Activity $activity)
    {
        $request->validate(['status' => 'required|in:Sedang Proses,Selesai']);

        $user = auth()->user();

        if (!$user->isAdmin()) {
            // User hanya bisa ubah dari "Sedang Proses" → "Selesai"
            if ($activity->status !== 'Sedang Proses' || $request->status !== 'Selesai') {
                return back()->with('error', 'Anda hanya dapat mengubah status dari Sedang Proses ke Selesai.');
            }
        }

        $activity->update(['status' => $request->status]);

        return back()->with('success', 'Status kegiatan berhasil diperbarui.');
    }

    /**
     * Remove the specified activity (Admin only).
     */
    public function destroy(Activity $activity)
    {
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Hanya Admin yang dapat menghapus data.');
        }

        // Hapus semua foto dari public/
        foreach ($activity->photos as $photo) {
            if ($photo->foto_path) {
                $filePath = public_path($photo->foto_path);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }
        }

        // Hapus foto utama juga
        if ($activity->foto_path) {
            $mainPath = public_path($activity->foto_path);
            if (File::exists($mainPath)) {
                File::delete($mainPath);
            }
        }

        $activity->photos()->delete();
        $activity->delete();

        return redirect()->route('activities.index')->with('success', 'Kegiatan berhasil dihapus.');
    }
}