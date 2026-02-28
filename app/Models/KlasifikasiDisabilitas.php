<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KlasifikasiDisabilitas extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'kode_klasifikasi',
        'nama_klasifikasi',
        'deskripsi',
        'is_active',
    ];

    public function jenis_disabilitas()
    {
        return $this->hasMany(JenisDisabilitas::class);
    }
}
