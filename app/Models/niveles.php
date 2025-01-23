<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class niveles extends Model
{
    use HasFactory;
    protected $table = 'nivel';

    protected $primaryKey = 'nivel_id';
    protected $fillable = [
        'nombre',
    ];
    // Modelo Nivel.php
    public function carreras()
    {
        return $this->hasMany(Carreras::class, 'nivel_id', 'nivel_id');
    }

    public function estudiantes()
    {
        return $this->hasMany(Estudiantes::class, 'nivel_id');
    }

    public $timestamps = false;
}
