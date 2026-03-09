<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PembinaanPrestasiDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pembinaan_prestasi_id',
        'training_type_component_id',
        'value',
        'score',
    ];

    protected $casts = [
        'value' => 'double',
        'score' => 'integer',
    ];

    public function pembinaan_prestasi()
    {
        return $this->belongsTo(PembinaanPrestasi::class);
    }

    public function training_type_component()
    {
        return $this->belongsTo(TrainingTypeComponent::class);
    }
}
