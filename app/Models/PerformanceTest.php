<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PerformanceTest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'atlet_id',
        'cabor_id',
        'klasifikasi_disabilitas_id',
        'jenis_disabilitas_id',
        'alat_bantu',
        'status_kesehatan',
        'tanggal_pelaksanaan',
        'spesialisasi',
        'penguji',
        'is_active',
    ];

    protected $casts = [
        'tanggal_pelaksanaan' => 'date',
    ];

    public function atlet()
    {
        return $this->belongsTo(Atlet::class);
    }

    public function cabor()
    {
        return $this->belongsTo(Cabor::class);
    }

    public function klasifikasiDisabilitas()
    {
        return $this->belongsTo(KlasifikasiDisabilitas::class, 'klasifikasi_disabilitas_id');
    }

    public function klasifikasi_disabilitas()
    {
        return $this->klasifikasiDisabilitas();
    }

    public function jenisDisabilitas()
    {
        return $this->belongsTo(JenisDisabilitas::class, 'jenis_disabilitas_id');
    }

    public function results()
    {
        return $this->hasMany(PerformanceTestResult::class);
    }
}
