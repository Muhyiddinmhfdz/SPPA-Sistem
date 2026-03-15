<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceTestResult extends Model
{
    protected $fillable = [
        'performance_test_id',
        'physical_test_item_id',
        'nilai',
        'physical_test_item_score_id',
        'is_active',
    ];

    public function performanceTest()
    {
        return $this->belongsTo(PerformanceTest::class);
    }

    public function physicalTestItem()
    {
        return $this->belongsTo(PhysicalTestItem::class, 'physical_test_item_id');
    }

    public function physicalTestItemScore()
    {
        return $this->belongsTo(PhysicalTestItemScore::class, 'physical_test_item_score_id');
    }
}
