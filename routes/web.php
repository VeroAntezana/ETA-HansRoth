<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NivelesController;
use App\Http\Controllers\CarrerasController;
use App\Http\Controllers\EstudiantesController;
use App\Http\Controllers\PagosController;
use App\Http\Controllers\GestionController;
use App\Http\Controllers\reportesController;
use App\Http\Controllers\EgresoController;

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
Route::get('/pagos/extra', [PagosController::class, 'createExtra'])->name('pagos.extra');
Route::post('/pagos/extra/store', [PagosController::class, 'storeExtra'])->name('pagos.storeExtra');
Route::get('/pagos/extra/{id}', [PagosController::class, 'showExtra'])->name('pagos.showExtra');


Route::resource('pagos',PagosController::class);
Route::get('getEstudianteInfo/{id}', [PagosController::class, 'getEstudianteInfo']);
// Rutas específicas para niveles de carreras
Route::get('estudiantes/{carrera}/{nivel}', [EstudiantesController::class, 'indexByNivel'])->name('estudiantes.indexByNivel');
Route::get('estudiantes/export', [EstudiantesController::class, 'exportExcel'])->name('estudiantes.export');
Route::resource('estudiantes',EstudiantesController::class);

Route::post('/estudiantes/create/matricular', [EstudiantesController::class, 'MatricularEstudianteAntiguo'])->name('estudiantes.matricular');

Route::resource('reportes',reportesController::class);
Route::get('/reportes/index-egreso', [reportesController::class, 'index_egreso'])->name('egresos.lista');
Route::resource('egresos', EgresoController::class);
Route::resource('reportes',reportesController::class);
Route::get('/reportes/index-egreso', [reportesController::class, 'index_egreso'])->name('reportes.index-egreso');
Route::post('/exportar-excel', [reportesController::class, 'exportToExcel'])->name('reportes.export');
Route::get('egresos/print/{egreso_id}', [EgresoController::class, 'print'])->name('egresos.print');
//Ingresos Extra








