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
/**
 * Usuarios
 */
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


Route::get('/usuarioRoles/{id}/{d?}','AcademicoController@usuarioRoles')->name('usuarioRoles');

Route::post('/rolAsignar','AcademicoController@rolAsignar')->name('rolAsignar');

Route::get('/quitarRol/{id}/{idusuario}', function($id,$idusuario){
	tesis\Rol::where('id','=',$id)->delete();
	return redirect()->route('usuarioRoles',['id'=>$idusuario]);
});

Route::get('/usuarioTesis/{id}/{d?}', function($id,$d='T'){
	return redirect()->route('usuarioRoles',['id'=>$id,'d'=>$d]);
})->name('usuarioTesis');

/**
 * Tesis
 */
Route::get('/tesis','AcademicoController@tesis')->name('tesis');

Route::get('/tesisNueva', 'AcademicoController@tesisNueva')->name('tesisNueva');

Route::post('/tesisAsignar','AcademicoController@tesisAsignar')->name('tesisAsignar');

Route::post('/tesisGuardar','AcademicoController@tesisGuardar')->name('tesisGuardar');

Route::post('/getTesistas', function(Request $request){
	if($request->ajax()){
		$tesistas = tesis\User::select('nombre')
								->join('tesista','users.id','tesista.idusuario')
								->where('tesista.idtesis','=',$request->idtesis)
								->get()->toArray();
		return response()->json($tesistas);
	}else{
		return false;
	}

})->name('getTesistas');




Route::post('/getTesisId', function(Request $request){
	if($request->ajax()){
		$tesis = tesis\Tesis::select('id','nom')
								->where('gen','=',$request->gen)
								->where('idprograma','=',$request->prog)
								->get()->toArray();
		return response()->json($tesis);				
	}else{
		return false;
	}
})->name('getTesisId');

Route::post('/getTesisDetalle', 'AcademicoController@getTesisDetalle')->name('getTesisDetalle');

/**
 * Rutas para la lista de tesistas, para asignar carrera y generacion
 */

Route::post('/asignaCarr', 'AcademicoController@asignaCarr')->name('asignaCarr');

Route::post('/asignaGen', 'AcademicoController@asignaGen')->name('asignaGen');





/*Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');*/
