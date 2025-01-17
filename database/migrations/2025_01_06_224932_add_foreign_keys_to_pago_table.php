<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPagoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pago', function (Blueprint $table) {
            $table->foreign(['matricula_id'], 'pago_ibfk_1')->references(['matricula_id'])->on('matricula')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pago', function (Blueprint $table) {
            $table->dropForeign('pago_ibfk_1');
        });
    }
}
