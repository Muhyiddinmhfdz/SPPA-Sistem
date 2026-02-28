<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MonitoringLatihan extends Model
{
    use SoftDeletes;

    protected $table = 'monitoring_latihan';

    protected $fillable = [
        'person_type',
        'person_id',
        'cabor_id',
        'tanggal',
        'kehadiran',
        'durasi_latihan',
        'beban_latihan',
        'denyut_nadi_rpe',
        'catatan_pelatih',
        'kesimpulan',
        'dicatat_oleh',
        'is_active',
    ];

    protected $casts = ['tanggal' => 'date'];

    public function cabor()
    {
        return $this->belongsTo(Cabor::class);
    }
    public function atlet()
    {
        return $this->belongsTo(Atlet::class, 'person_id');
    }
    public function coach()
    {
        return $this->belongsTo(Coach::class, 'person_id');
    }
    public function pencatat()
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }
}
