<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Atlet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'cabor_id',
        'klasifikasi_disabilitas_id',
        'name',
        'jenis_disabilitas',
        'nik',
        'birth_place',
        'birth_date',
        'religion',
        'gender',
        'address',
        'blood_type',
        'last_education',
        'photo_path',
        'ktp_path',
        'achievement_certificate_path',
        'npwp_path',
        'sk_path',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cabor()
    {
        return $this->belongsTo(Cabor::class);
    }

    public function klasifikasi_disabilitas()
    {
        return $this->belongsTo(KlasifikasiDisabilitas::class);
    }

    public function cekKesehatan()
    {
        return $this->hasMany(CekKesehatan::class, 'person_id')
            ->where('person_type', 'atlet');
    }

    public function monitoringLatihan()
    {
        return $this->hasMany(MonitoringLatihan::class, 'person_id')
            ->where('person_type', 'atlet');
    }
}
