<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatriculasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matriculas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('estudiantes')->onDelete('cascade'); // Relación con Estudiantes
            $table->foreignId('gestion_id')->constrained('gestiones')->onDelete('cascade'); // Relación con Gestion
            $table->foreignId('carrera_nivel_id')->constrained('carrera_nivels')->onDelete('cascade'); // Relación con carrera_niveles
            $table->date('fecha_matricula')->default(now()); // Fecha de matrícula
            $table->enum('estado', ['activo', 'inactivo'])->default('activo'); // Estado de la matrícula
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matriculas');
    }
}
