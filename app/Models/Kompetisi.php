<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kompetisi extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'cabor_id',
        'atlet_id',
        'tingkatan',
        'nama_kompetisi',
        'waktu_pelaksanaan',
        'tempat_pelaksanaan',
        'jumlah_peserta',
        'hasil_peringkat',
        'hasil_medali',
        'kesimpulan_evaluasi',
        'dicatat_oleh',
        'is_active',
    ];

    protected $casts = [
        'waktu_pelaksanaan' => 'date',
        'is_active' => 'boolean',
    ];

    public function cabor()
    {
        return $this->belongsTo(Cabor::class);
    }

    public function atlet()
    {
        return $this->belongsTo(Atlet::class);
    }

    public function pencatat()
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }
}
