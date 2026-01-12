<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'tanggal',
        'kategori',
        'status',
        'kegiatan',
        'foto_path',
    ];
    protected $casts = [
        'tanggal' => 'date',
    ];
}
