<?php

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

use Illuminate\Http\Request;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/academicoHome', 'AcademicoController@index')->name('academicoHome');

Route::get('/usuariosAcademicos', 'AcademicoController@usuariosAcademicos')->name('usuariosAcademicos');

Route::get('/usuariosTesistas/{gen?}', 'AcademicoController@usuariosTesistas')->name('usuariosTesistas');

Route::get('/usuariosNuevos', 'AcademicoController@usuariosNuevos')->name('usuariosNuevos');

Route::post('/usuarioGuardar/{accion?}/{tipo?}', 'AcademicoController@usuarioGuardar')->name('usuarioGuardar');


/* 
idtu-> id y tipo de usuario con formato id:tu, tu se usa para mostrar la lista correspondiente 1~4->academicos, 5->tesistes, 9->nuevos
 */
Route::get('/usuarioEliminar/{idtu}', 'AcademicoController@usuarioEliminar')->name('usuarioEliminar');

Route::get('/usuarioEditar/{id}', function($id){
	        $u = tesis\User::where('id','=',$id)->get();
    		return view('academico.usuarioeditar',compact('u'));
})->name('usuarioEditar');


Route::get('/tesis','AcademicoController@tesis')->name('tesis');



/**
 * Rutas para la lista de tesistas, para asignar carrera y generacion
 */

Route::post('/asignaCarr', 'AcademicoController@asignaCarr')->name('asignaCarr');

Route::post('/asignaGen', 'AcademicoController@asignaGen')->name('asignaGen');





/*Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');*/
