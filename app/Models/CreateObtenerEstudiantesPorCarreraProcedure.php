<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateObtenerEstudiantesPorCarreraProcedure extends Model
{
    use HasFactory;
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Crear el procedimiento almacenado
        DB::unprepared("
            DELIMITER $$

            CREATE DEFINER=`root`@`localhost` PROCEDURE `ObtenerEstudiantesPorCarrera`(
                IN p_carrera_id INT
            )
            BEGIN
                SELECT
                    e.estudiante_id AS ID_Estudiante, -- ID del estudiante
                    e.nombre AS NombreEstudiante,
                    e.apellidos AS ApellidosEstudiante,
                    e.ci AS CI,
                    c.nombre AS NombreCarrera,
                    n.nombre AS Nivel
                FROM
                    estudiante e
                INNER JOIN
                    estudiante_carrera ec ON e.estudiante_id = ec.estudiante_id
                INNER JOIN
                    carrera c ON ec.carrera_id = c.carrera_id
                INNER JOIN
                    nivel n ON c.nivel_id = n.nivel_id
                WHERE
                    c.carrera_id = p_carrera_id;
            END$$

            DELIMITER ;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Eliminar el procedimiento almacenado
        DB::unprepared("DROP PROCEDURE IF EXISTS ObtenerEstudiantesPorCarrera");
    }
}
