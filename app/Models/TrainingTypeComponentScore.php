<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingTypeComponentScore extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'training_type_component_id',
        'min_value',
        'max_value',
        'label',
        'score',
    ];

    public function component()
    {
        return $this->belongsTo(TrainingTypeComponent::class, 'training_type_component_id');
    }
}
