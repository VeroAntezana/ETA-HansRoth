<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateNivelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nivel', function (Blueprint $table) {
            $table->integer('nivel_id', true);
            $table->string('nombre', 50)->unique('nombre');
        });
        
        $niveles = [
            ['nombre' => 'BASICO'],
            ['nombre' => 'AUXILIAR'],
            ['nombre' => 'MEDIO'],
        ];

        // Insertamos los niveles en la tabla 'nivel'
        DB::table('nivel')->insert($niveles);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nivel');
    }
}
