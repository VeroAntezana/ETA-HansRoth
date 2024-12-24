<?php

namespace App\Models;
use App\Models\Niveles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class carreras extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'duracion'
        
        
    ];

   
       // Carrera.php
public function niveles()
{
    return $this->belongsToMany(Niveles::class, 'carrera_niveles', 'carrera_id', 'nivel_id');
}

    

    public function estudiantes()
    {
        return $this->hasMany(Estudiantes::class, 'carrera_id');
    }
}
