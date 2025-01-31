<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Egreso extends Model
{
    use HasFactory;
    protected $table = 'egreso';


    protected $primaryKey = 'egreso_id';
    protected $fillable = [
        'descripcion',
        'fecha',
        'monto',
        'responsable',
        'gestion_id',
    ];

    public function gestion()
    {
        return $this->belongsTo(Gestion::class,'gestion_id');
    }
    public $timestamps = false;
}
