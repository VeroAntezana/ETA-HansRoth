<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class carrera_niveles extends Model
{
    use HasFactory;
    protected $fillable = [
        'carrera_id',
        'nivel_id'
    ];

    public function carrera()
    {
        return $this->belongsTo(Carreras::class, 'carrera_id');
    }

    // Relación con Nivel
    public function nivel()
    {
        return $this->belongsTo(Niveles::class, 'nivel_id');
    }
}
