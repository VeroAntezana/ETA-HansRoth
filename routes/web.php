<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NivelesController;
use App\Http\Controllers\CarrerasController;
use App\Http\Controllers\EstudiantesController;
use App\Http\Controllers\PagosController;
use App\Http\Controllers\GestionController;
use App\Http\Controllers\reportesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/pagos/pdf', [PagosController::class, 'pdf'])->name('pagos.pdf');

Route::resource('niveles',NivelesController::class);
Route::resource('carreras',CarrerasController::class);

Route::resource('gestiones',GestionController::class);
//pagos
Route::get('/search', [PagosController::class, 'search']);
Route::get('/pagos/lista', [PagosController::class, 'lista'])->name('pagos.lista');
Route::get('/pagos/{id}/print', [PagosController::class, 'print'])->name('pagos.print');
Route::resource('pagos',PagosController::class);
Route::get('getEstudianteInfo/{id}', [PagosController::class, 'getEstudianteInfo']);
// Rutas específicas para niveles de carreras
Route::get('estudiantes/{carrera}/{nivel}', [EstudiantesController::class, 'indexByNivel'])->name('estudiantes.indexByNivel');
Route::resource('estudiantes',EstudiantesController::class);

Route::post('/estudiantes/create/matricular', [EstudiantesController::class, 'MatricularEstudianteAntiguo'])->name('estudiantes.matricular');

Route::resource('Reportes',reportesController::class);
Route::get('/reportes/index-egreso', [reportesController::class, 'index_egreso'])->name('reportes.index-egreso');






