<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarreraNivelesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carrera_niveles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carrera_id')->references('id')->on('carreras')->onDelete('cascade');
            $table->foreignId('nivel_id')->references('id')->on('niveles')->onDelete('cascade');
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
        Schema::dropIfExists('carrera_niveles');
    }
}
