<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    /**
     * Display the dashboard with stats.
     */
    public function dashboard()
    {
        $totalActivities = Activity::count();
        $completedTasks = Activity::where('status', 'Selesai')->count();
        $pendingTasks = Activity::where('status', 'Pending')->count();
        $activeUsers = 24; // Static placeholder

        // Chart Data (Last 7 Days)
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('D');
            $chartData[] = Activity::whereDate('tanggal', $date)->count();
        }

        // Recent Activities
        $recentActivities = Activity::latest()->take(5)->get();

        return view('home', compact('totalActivities', 'completedTasks', 'pendingTasks', 'activeUsers', 'chartLabels', 'chartData', 'recentActivities'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Activity::query();
        
        // Kategori Filter
        if ($request->has('kategori') && $request->kategori != 'All') {
             $query->where('kategori', $request->kategori);
        }

        // Period Filter
        if ($request->has('period')) {
            $now = \Carbon\Carbon::now('Asia/Jakarta');
            switch ($request->period) {
                case 'Hari Ini':
                    $query->whereDate('tanggal', $now->toDateString());
                    break;
                case 'Minggu Ini':
                    $query->whereBetween('tanggal', [$now->startOfWeek()->toDateString(), $now->endOfWeek()->toDateString()]);
                    break;
                case 'Bulan Ini':
                    $query->whereMonth('tanggal', $now->month)->whereYear('tanggal', $now->year);
                    break;
                case 'Tahun Ini':
                    $query->whereYear('tanggal', $now->year);
                    break;
            }
        }

        // Status Filter
    if ($request->has('status') && $request->status != 'All') {
         $query->where('status', $request->status);
    }

    $activities = $query->with('photos')->orderBy('tanggal', 'desc')->get();
        
        if ($request->ajax()) {
            return view('activities.partials.table_body', compact('activities'))->render();
        }

        return view('activities.index', compact('activities'));
    }

    // ... create and store methods remain ...

    /**
     * Export logic
     */
    public function export(Request $request)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ActivityExport($request->all()), 'laporan_kegiatan.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('activities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'kegiatan' => 'required|string',
            'status' => 'required|string',
            'kategori' => 'required|string',
            'foto.*' => 'image|max:10240', // Max 10MB per file
        ], [
            'foto.max' => 'Maksimal upload 4 foto.',
        ]);

        // Create Activity
        $activity = Activity::create([
            'nama' => $validated['nama'],
            'tanggal' => $validated['tanggal'],
            'kegiatan' => $validated['kegiatan'],
            'status' => $validated['status'],
            'kategori' => $validated['kategori'],
            'foto_path' => null, // Will update with first image
        ]);

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $index => $file) {
                // Store file
                $path = $file->store('activities', 'public');

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

        return redirect()->route('home')->with('success', 'Laporan kegiatan berhasil disimpan!');
    }

    // Export method implemented above
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        if ($activity->foto_path) {
            Storage::disk('public')->delete($activity->foto_path);
        }

        $activity->delete();

        return redirect()->route('activities.index')->with('success', 'Kegiatan berhasil dihapus.');
    }
}
