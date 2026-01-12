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
            'Nama',
            'Kegiatan',
            'Status',
            'Kategori',
            'Foto', // Placeholder column for drawings
        ];
    }

    public function map($activity): array
    {
        // We will calculate index in map, but since we map row by row, we rely on collection order.
        // Actually, map() doesn't give us the index easily for drawings.
        // But drawings() method is separate.
        return [
            $activity->id, // Just ID as placeholder for No, or we can calculate logic later, but for now ID is fine or we can omit
            $activity->tanggal->format('d/m/Y'),
            $activity->nama,
            $activity->kegiatan,
            $activity->status,
            $activity->kategori,
            '', // Leave empty for image
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
                $drawing->setCoordinates('G' . ($index + 2)); // G is the 7th column. +2 for header row.
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
            'C' => 20,
            'D' => 40,
            'E' => 15,
            'F' => 15,
            'G' => 20,
        ];
    }
}
