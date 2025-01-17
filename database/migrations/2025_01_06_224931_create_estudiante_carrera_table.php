<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstudianteCarreraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estudiante_carrera', function (Blueprint $table) {
            $table->integer('estudiante_carrera_id', true);
            $table->integer('estudiante_id')->index('estudiante_id');
            $table->integer('carrera_id')->index('carrera_id');
            $table->date('fecha_inscripcion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estudiante_carrera');
    }
}
