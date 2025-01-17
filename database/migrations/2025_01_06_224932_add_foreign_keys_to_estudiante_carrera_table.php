<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToEstudianteCarreraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estudiante_carrera', function (Blueprint $table) {
            $table->foreign(['estudiante_id'], 'estudiante_carrera_ibfk_1')->references(['estudiante_id'])->on('estudiante')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['carrera_id'], 'estudiante_carrera_ibfk_2')->references(['carrera_id'])->on('carrera')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estudiante_carrera', function (Blueprint $table) {
            $table->dropForeign('estudiante_carrera_ibfk_1');
            $table->dropForeign('estudiante_carrera_ibfk_2');
        });
    }
}
