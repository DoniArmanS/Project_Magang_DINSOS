<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'tanggal',
        'kategori',
        'status',
        'kegiatan',
        'jenis_kelamin',
        'tanggal_lahir',
        'tempat_tinggal',
        'foto_path',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'tanggal_lahir' => 'date',
    ];

    public function photos()
    {
        return $this->hasMany(ActivityPhoto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}