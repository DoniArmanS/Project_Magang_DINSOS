<?php

namespace App\Exports;

use App\Models\Activity;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ActivityExport implements FromCollection, WithHeadings, WithMapping, WithDrawings, WithStyles, WithColumnWidths
{
    protected $request;
    protected $activities;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Activity::query();

        // Kategori Filter
        if (isset($this->request['kategori']) && $this->request['kategori'] != 'All') {
            $query->where('kategori', $this->request['kategori']);
        }

        // Period Filter
        if (isset($this->request['period'])) {
            $now = Carbon::now();
            switch ($this->request['period']) {
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

        $this->activities = $query->orderBy('tanggal', 'desc')->get();
        return $this->activities;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Waktu Input', // Added Column
            'Nama',
            'Kegiatan',
            'Status',
            'Kategori',
            'Foto', 
        ];
    }

    public function map($activity): array
    {
        return [
            $activity->id,
            $activity->tanggal->format('d/m/Y'),
            $activity->created_at->format('H:i'), // Export Created At Time
            $activity->nama,
            $activity->kegiatan,
            $activity->status,
            $activity->kategori,
            '', 
        ];
    }

    public function drawings()
    {
        $drawings = [];
        // collection() loads the data. We need to iterate it.
        // Note: collection() matches the data executed.
        // If collection() is called multiple times, it might be an issue.
        // So we stored it in $this->activities.

        if (!$this->activities) {
            $this->collection();
        }

        foreach ($this->activities as $index => $activity) {
            if ($activity->foto_path && Storage::disk('public')->exists($activity->foto_path)) {
                $drawing = new Drawing();
                $drawing->setName('Foto');
                $drawing->setDescription('Foto Kegiatan');
                $drawing->setPath(Storage::disk('public')->path($activity->foto_path));
                $drawing->setHeight(80);
                $drawing->setCoordinates('H' . ($index + 2)); // H is the 8th column (Foto)
                $drawings[] = $drawing;
            }
        }

        return $drawings;
    }

    public function styles(Worksheet $sheet)
    {
        // Set row height for all rows to fit images
        foreach ($this->activities as $index => $activity) {
            $sheet->getRowDimension($index + 2)->setRowHeight(85);
        }

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 12,
            'C' => 10, // Width for Waktu Input
            'D' => 20,
            'E' => 40,
            'F' => 15,
            'G' => 15,
            'H' => 20, // Width for Foto
        ];
    }
}
