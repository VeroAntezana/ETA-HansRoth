<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCarreraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carrera', function (Blueprint $table) {
            $table->integer('carrera_id', true);
            $table->string('nombre');
            $table->integer('nivel_id')->index('nivel_id');
            $table->integer('duracion_meses');
        });

        // Obtener los niveles existentes
        $niveles = DB::table('nivel')->pluck('nivel_id', 'nombre');

        // Definir la duración de cada nivel
        $duracionesPorNivel = [
            'BASICO' => 6,
            'AUXILIAR' => 6,
            'MEDIO' => 12
        ];

        // Lista de carreras
        $carreras = [
            'Agroecologia',
            'Bordado Artesanal e Industrial',
            'Carpinteria Industrial',
            'Textil y Confeccion',
            'Tecnologia de la Informacion Digital',
            'Mecanica Automotriz'
        ];

        // Insertar las combinaciones de carrera con el nivel_id correspondiente y su duración
        foreach ($carreras as $nombreCarrera) {
            foreach ($niveles as $nivelNombre => $nivelId) {
                // Asignamos la duración de acuerdo al nivel
                $duracion = $duracionesPorNivel[$nivelNombre] ?? 0; // Si no se encuentra, asigna 0

                DB::table('carrera')->insert([
                    'nombre' => $nombreCarrera,  // Nombre de la carrera
                    'nivel_id' => $nivelId,  // Nivel correspondiente
                    'duracion_meses' => $duracion,  // Duración según el nivel
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carrera');
    }
}
