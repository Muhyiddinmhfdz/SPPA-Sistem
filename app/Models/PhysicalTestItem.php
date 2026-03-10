<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class PhysicalTestItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'physical_test_category_id',
        'name',
        'jenis_disabilitas_id',
        'satuan',
        'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(PhysicalTestCategory::class, 'physical_test_category_id');
    }

    public function jenisDisabilitas()
    {
        return $this->belongsTo(JenisDisabilitas::class, 'jenis_disabilitas_id');
    }

    public function scores()
    {
        return $this->hasMany(PhysicalTestItemScore::class);
    }
}
