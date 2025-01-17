<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class carrera_Estudiantes extends Model
{
    use HasFactory;
    protected $table = 'estudiante_carrera';
    protected $primaryKey = 'estudiante_carrera_id';
    protected $fillable = ['estudiante_id', 'carrera_id', 'fecha_inscripcion'];
    public $timestamps = false;
}
