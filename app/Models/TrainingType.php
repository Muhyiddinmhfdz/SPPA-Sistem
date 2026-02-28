<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrainingType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cabor_id',
        'name',
        'is_active',
    ];

    public function cabor()
    {
        return $this->belongsTo(Cabor::class);
    }

    public function components()
    {
        return $this->hasMany(TrainingTypeComponent::class);
    }
}
