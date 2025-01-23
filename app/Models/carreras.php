<?php

namespace App\Models;

use App\Http\Controllers\NivelesController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carreras extends Model
{
    use HasFactory;


    protected $table = 'carrera';


    protected $primaryKey = 'carrera_id';


    protected $fillable = [
        'nombre',
        'duracion_meses',
        'nivel_id'
    ];


    public $timestamps = false;


    public function nivel()
    {
        return $this->belongsTo(niveles::class, 'nivel_id', 'nivel_id');
    }

    public function estudiantes()
    {
        return $this->hasManyThrough(
            Estudiantes::class,
            carrera_Estudiantes::class,
            'carrera_id',
            'estudiante_id',
            'carrera_id',
            'estudiante_carrera_id'
        );
    }
    public function estudianteCarreras()
    {
        return $this->hasMany(carrera_Estudiantes::class, 'carrera_id');
    }
}
