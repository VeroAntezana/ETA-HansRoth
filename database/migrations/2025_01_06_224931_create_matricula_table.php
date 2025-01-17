<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatriculaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matricula', function (Blueprint $table) {
            $table->integer('matricula_id', true);
            $table->integer('estudiante_carrera_id')->index('estudiante_carrera_id');
            $table->integer('gestion_id')->index('gestion_id');
            $table->date('fecha_matricula');
            $table->enum('estado', ['Activa', 'Finalizada']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matricula');
    }
}
