<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Egreso extends Model
{
    use HasFactory;
    protected $fillable = [
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'gestion_id',
    ];

    public function gestion()
    {
        return $this->belongsTo(Gestion::class,'gestion_id');
    }
    public $timestamps = false;
}
