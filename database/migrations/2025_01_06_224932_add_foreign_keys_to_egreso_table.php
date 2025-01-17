<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToEgresoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('egreso', function (Blueprint $table) {
            $table->foreign(['gestion_id'], 'egreso_ibfk_1')->references(['gestion_id'])->on('gestion')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('egreso', function (Blueprint $table) {
            $table->dropForeign('egreso_ibfk_1');
        });
    }
}
