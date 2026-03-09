<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PembinaanPrestasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'atlet_id',
        'tanggal',
        'periodesasi_latihan',
        'intensitas_latihan',
        'target_performa',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function atlet()
    {
        return $this->belongsTo(Atlet::class);
    }

    public function details()
    {
        return $this->hasMany(PembinaanPrestasiDetail::class, 'pembinaan_prestasi_id');
    }
}
