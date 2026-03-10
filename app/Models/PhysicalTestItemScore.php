<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class PhysicalTestItemScore extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'physical_test_item_id',
        'min_value',
        'max_value',
        'label',
        'score',
        'is_active',
    ];

    public function item()
    {
        return $this->belongsTo(PhysicalTestItem::class, 'physical_test_item_id');
    }
}
