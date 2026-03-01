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
    protected $rowCounter = 0;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Activity::with(['photos', 'user'])->orderBy('tanggal', 'desc');

        // Kategori Filter
        if (isset($this->request['kategori']) && $this->request['kategori'] != 'All') {
            $query->where('kategori', $this->request['kategori']);
        }

        // Status Filter
        if (isset($this->request['status']) && $this->request['status'] != 'All') {
            $query->where('status', $this->request['status']);
        }

        // Date Range Filter (from index filter format)
        if (!empty($this->request['start_date']) && !empty($this->request['end_date'])) {
            $query->whereBetween('tanggal', [$this->request['start_date'], $this->request['end_date']]);
        }

        $this->activities = $query->get();
        $this->rowCounter = 0;
        return $this->activities;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Kegiatan',
            'Waktu Input',
            'Petugas',
            'Nama Klien',
            'Jenis Kelamin',
            'Tgl Lahir',
            'Tempat Tinggal',
            'Kategori',
            'Detail Kegiatan',
            'Status',
            'Foto',
        ];
    }

    public function map($activity): array
    {
        $this->rowCounter++;
        $petugas = $activity->user?->name ?? '-';
        $jabatan = $activity->user?->jenis_user ?? '';
        if ($jabatan) {
            $petugas .= ' (' . $jabatan . ')';
        }
        return [
            $this->rowCounter,
            $activity->tanggal->format('d/m/Y h:i A'),
            $activity->created_at->format('d/m/Y h:i A'),
            $petugas,
            $activity->nama,
            $activity->jenis_kelamin,
            $activity->tanggal_lahir ? $activity->tanggal_lahir->format('d/m/Y') : '-',
            $activity->tempat_tinggal ?? '-',
            $activity->kategori,
            $activity->kegiatan,
            $activity->status,
            '', // placeholder untuk foto
        ];
    }

    public function drawings()
    {
        $drawings = [];
        if (!$this->activities) $this->collection();

        foreach ($this->activities as $rowIndex => $activity) {
            $rowNum      = $rowIndex + 2;
            $photoColumn = 'L'; // Kolom L (ke-12)
            $photos      = $activity->photos;

            if ($photos->isEmpty() && $activity->foto_path) {
                if (file_exists(public_path($activity->foto_path))) {
                    $drawing = new Drawing();
                    $drawing->setName('Foto');
                    $drawing->setDescription('Foto Kegiatan');
                    $drawing->setPath(public_path($activity->foto_path));
                    $drawing->setHeight(80);
                    $drawing->setCoordinates($photoColumn . $rowNum);
                    $drawing->setOffsetX(10);
                    $drawing->setOffsetY(10);
                    $drawings[] = $drawing;
                }
            } else {
                foreach ($photos as $photoIndex => $photo) {
                    if (file_exists(public_path($photo->foto_path))) {
                        $drawing = new Drawing();
                        $drawing->setName('Foto ' . ($photoIndex + 1));
                        $drawing->setDescription('Foto Kegiatan');
                        $drawing->setPath(public_path($photo->foto_path));
                        $drawing->setHeight(80);
                        $drawing->setCoordinates($photoColumn . $rowNum);

                        $colIdx  = $photoIndex % 2;
                        $rowIdx  = floor($photoIndex / 2);
                        $drawing->setOffsetX(10 + ($colIdx * 140));
                        $drawing->setOffsetY(10 + ($rowIdx * 100));

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
            $rowNum     = $index + 2;
            $photoCount = $activity->photos->count();
            if ($photoCount == 0 && $activity->foto_path) $photoCount = 1;

            $rowsNeeded = max(1, ceil($photoCount / 2));
            $height     = ($rowsNeeded * 100) + 30;
            $sheet->getRowDimension($rowNum)->setRowHeight($height);

            $sheet->getStyle('A' . $rowNum . ':L' . $rowNum)->applyFromArray([
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                    'wrapText' => true,
                    'indent'   => 1,
                ],
            ]);
        }

        return [
            1 => [
                'font'      => ['bold' => true],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
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
            'B' => 18,
            'C' => 16,
            'D' => 25,
            'E' => 22,
            'F' => 14,
            'G' => 12,
            'H' => 25,
            'I' => 15,
            'J' => 40,
            'K' => 14,
            'L' => 50,
        ];
    }
}