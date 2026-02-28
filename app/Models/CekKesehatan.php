<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CekKesehatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cek_kesehatan';

    protected $fillable = [
        'person_type',
        'person_id',
        'cabor_id',
        'tanggal',
        'kondisi_harian',
        'tingkat_cedera',
        'riwayat_medis',
        'kesimpulan',
        'catatan',
        'dibuat_oleh',
        'is_active',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

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

    public function dibuatOleh()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    // Accessor: get person name regardless of type
    public function getPersonNameAttribute()
    {
        if ($this->person_type === 'atlet') {
            return $this->atlet?->name ?? '-';
        }
        return $this->coach?->name ?? '-';
    }
}
