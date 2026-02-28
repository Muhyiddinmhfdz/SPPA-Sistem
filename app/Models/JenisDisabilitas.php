<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisDisabilitas extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'klasifikasi_disabilitas_id',
        'nama_jenis',
        'deskripsi',
        'is_active',
    ];

    public function klasifikasi_disabilitas()
    {
        return $this->belongsTo(KlasifikasiDisabilitas::class);
    }
}
