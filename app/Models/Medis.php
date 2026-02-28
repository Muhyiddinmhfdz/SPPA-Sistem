<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medis extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'klasifikasi',
        'nik',
        'birth_place',
        'birth_date',
        'religion',
        'gender',
        'address',
        'blood_type',
        'last_education',
        'education_certificate_path',
        'photo_path',
        'ktp_path',
        'competency_certificate_path',
        'npwp_path',
        'sk_appointment_path',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
