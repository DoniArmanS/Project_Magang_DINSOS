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

        $this->activities = $query->with('photos')->orderBy('tanggal', 'desc')->get();
        return $this->activities;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Waktu Input',
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
            $activity->created_at->format('H:i'),
            $activity->nama,
            // Limit to 5 words as requested "max 5 kata doang"
            \Illuminate\Support\Str::words($activity->kegiatan, 5, '...'), 
            $activity->status,
            $activity->kategori,
            '', // Empty for photos
        ];
    }

    public function drawings()
    {
        $drawings = [];
        if (!$this->activities) $this->collection();

        foreach ($this->activities as $rowIndex => $activity) {
            $rowNum = $rowIndex + 2;
            $photoColumn = 'H';
            $photos = $activity->photos;

            if ($photos->isEmpty() && $activity->foto_path) {
                if (Storage::disk('public')->exists($activity->foto_path)) {
                    $drawing = new Drawing();
                    $drawing->setName('Foto');
                    $drawing->setDescription('Foto Kegiatan');
                    $drawing->setPath(Storage::disk('public')->path($activity->foto_path));
                    $drawing->setHeight(80);
                    $drawing->setCoordinates($photoColumn . $rowNum);
                    $drawing->setOffsetX(10); 
                    $drawing->setOffsetY(10);
                    $drawings[] = $drawing;
                }
            } else {
                foreach ($photos as $photoIndex => $photo) {
                    if (Storage::disk('public')->exists($photo->foto_path)) {
                        $drawing = new Drawing();
                        $drawing->setName('Foto ' . ($photoIndex + 1));
                        $drawing->setDescription('Foto Kegiatan');
                        $drawing->setPath(Storage::disk('public')->path($photo->foto_path));
                        $drawing->setHeight(80);
                        
                        $drawing->setCoordinates($photoColumn . $rowNum);
                        
                        // GRID LOGIC (2 Columns)
                        // Column Index (0 or 1)
                        $colIdx = $photoIndex % 2; 
                        // Row Index (0, 0, 1, 1, 2...)
                        $rowIdx = floor($photoIndex / 2);

                        // Offset Calculation (Increased Spacing)
                        // Gap increased to avoid touching:
                        // X Step: 140px (Img width ~100-120px)
                        // Y Step: 100px (Img height 80px + 20px gap)
                        $offsetX = 10 + ($colIdx * 140); 
                        $offsetY = 10 + ($rowIdx * 100);

                        $drawing->setOffsetX($offsetX);
                        $drawing->setOffsetY($offsetY);
                        
                        $drawings[] = $drawing;
                    }
                }
            }
        }
        return $drawings;
    }

    public function styles(Worksheet $sheet)
    {
        foreach ($this->activities as $index => $activity) {
            $rowNum = $index + 2;
            $photoCount = $activity->photos->count();
            if($photoCount == 0 && $activity->foto_path) $photoCount = 1;
            
            // Calculate Height based on GRID ROWS (Height 100px per row)
            $rowsNeeded = ceil($photoCount / 2);
            if($rowsNeeded < 1) $rowsNeeded = 1;

            // Height Formula: (Rows * 100) + Padding
            // Added 30px extra padding at bottom for "Margin" look
            $height = ($rowsNeeded * 100) + 30;
            
            $sheet->getRowDimension($rowNum)->setRowHeight($height);
            
            // Vertical Alignment Top + Wrap Text + Indent (Padding)
            $sheet->getStyle('A'.$rowNum.':H'.$rowNum)->applyFromArray([
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                    'wrapText' => true,
                    'indent' => 1, // Adds "Left Padding"
                ],
            ]);
        }

        return [
            1 => [
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 12,
            'C' => 10,
            'D' => 20,
            'E' => 40,
            'F' => 15,
            'G' => 15,
            'H' => 50, // Base width for Foto column (Wider to accommodate at least 2-3 photos visually)
        ];
    }
}
