<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coach extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'cabor_id',
        'name',
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
        'certificate_path',
        'npwp_path',
        'sk_path',
        'is_active',
    ];

    public function cabor()
    {
        return $this->belongsTo(Cabor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
