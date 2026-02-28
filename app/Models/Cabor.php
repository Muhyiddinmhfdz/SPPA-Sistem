<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cabor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'sk_start_date',
        'sk_end_date',
        'chairman_name',
        'secretary_name',
        'treasurer_name',
        'secretariat_address',
        'phone_number',
        'email',
        'npwp',
        'active_athletes_count',
        'active_coaches_count',
        'active_medics_count',
        'sk_file_path',
        'is_active',
    ];

    public function pelatihans()
    {
        return $this->hasMany(Pelatihan::class);
    }
}
