<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class PhysicalTestCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'cabor_id',
        'name',
        'is_active',
    ];

    public function cabor()
    {
        return $this->belongsTo(Cabor::class);
    }

    public function items()
    {
        return $this->hasMany(PhysicalTestItem::class);
    }
}
