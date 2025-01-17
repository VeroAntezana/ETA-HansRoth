<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToMatriculaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matricula', function (Blueprint $table) {
            $table->foreign(['estudiante_carrera_id'], 'matricula_ibfk_1')->references(['estudiante_carrera_id'])->on('estudiante_carrera')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['gestion_id'], 'matricula_ibfk_2')->references(['gestion_id'])->on('gestion')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matricula', function (Blueprint $table) {
            $table->dropForeign('matricula_ibfk_1');
            $table->dropForeign('matricula_ibfk_2');
        });
    }
}
