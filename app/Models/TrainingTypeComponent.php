<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrainingTypeComponent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'training_type_id',
        'name',
        'is_active',
    ];

    public function trainingType()
    {
        return $this->belongsTo(TrainingType::class);
    }

    public function scores()
    {
        return $this->hasMany(TrainingTypeComponentScore::class, 'training_type_component_id')->orderBy('score', 'desc');
    }
}
