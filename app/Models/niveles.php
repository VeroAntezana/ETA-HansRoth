<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class niveles extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
    ];
    public function carreras()
    {
        return $this->belongsToMany(Carreras::class, 'carrera_niveles', 'nivel_id', 'carrera_id');
    }
    public function estudiantes()
    {
        return $this->hasMany(Estudiantes::class, 'nivel_id');
    }
  
  

}
