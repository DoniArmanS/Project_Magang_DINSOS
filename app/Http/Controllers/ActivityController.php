<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
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
            $now = \Carbon\Carbon::now();
            switch ($request->period) {
                case 'Hari Ini':
                    $query->whereDate('tanggal', $now->today());
                    break;
                case 'Minggu Ini':
                    $query->whereBetween('tanggal', [$now->startOfWeek(), $now->endOfWeek()]);
                    break;
                case 'Bulan Ini':
                    $query->whereMonth('tanggal', $now->month)->whereYear('tanggal', $now->year);
                    break;
                case 'Tahun Ini':
                    $query->whereYear('tanggal', $now->year);
                    break;
            }
        }

        $activities = $query->orderBy('tanggal', 'desc')->get();
        
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
            'foto' => 'required|image|max:10240', // Max 10MB
        ]);

        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('activities', 'public');
        }

        Activity::create([
            'nama' => $validated['nama'],
            'tanggal' => $validated['tanggal'],
            'kegiatan' => $validated['kegiatan'],
            'status' => $validated['status'],
            'kategori' => $validated['kategori'],
            'foto_path' => $path,
        ]);

        return redirect()->route('home')->with('success', 'Laporan kegiatan berhasil disimpan!');
    }

    // Export method implemented above
}
