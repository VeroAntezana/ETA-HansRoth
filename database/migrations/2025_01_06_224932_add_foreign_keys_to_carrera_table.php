<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCarreraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carrera', function (Blueprint $table) {
            $table->foreign(['nivel_id'], 'carrera_ibfk_1')->references(['nivel_id'])->on('nivel')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carrera', function (Blueprint $table) {
            $table->dropForeign('carrera_ibfk_1');
        });
    }
}
