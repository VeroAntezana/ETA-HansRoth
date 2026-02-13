<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSemestreIdToMatricula extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matricula', function (Blueprint $table) {
            $table->unsignedBigInteger('semestre_id')
                ->nullable()
                ->after('gestion_id');

            $table->foreign('semestre_id')
                ->references('semestre_id')
                ->on('semestre')
                ->nullOnDelete();
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
            $table->dropForeign(['semestre_id']);
            $table->dropColumn('semestre_id');
        });
    }
}
