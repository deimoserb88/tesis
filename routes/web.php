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

Route::get('/tesistaHome', 'TesistaController@index')->name('tesistaHome');


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

Route::get('/usuarioEditar/{id}', function(Request $request,$id){
		$urol = $request->session()->get('rol');
	    $u = tesis\User::where('id','=',$id)->get();
        if(Auth::user()->priv == 1){
            $p = tesis\Programa::all();
        }else{
            $p = tesis\Rol::select('programa.programa','programa.id')
                        ->join('programa','rol.idprograma','=','programa.id')
                        ->where('rol.idusuario','=',Auth::user()->id)
                        ->get();
        }
    	return view('academico.usuarioeditar',compact('u','urol','p'));
})->name('usuarioEditar');

/*Usuarios y sus roles*/

Route::get('/usuarioRoles/{id}/{d?}','AcademicoController@usuarioRoles')->name('usuarioRoles');

Route::post('/rolAsignar','AcademicoController@rolAsignar')->name('rolAsignar');

Route::get('/rolQuitar/{id}/{idusuario}', 'AcademicoController@rolQuitar')->name('rolQuitar');
/*
Route::get('/quitarRol/{id}/{idusuario}', function($id,$idusuario){
	tesis\Rol::where('id','=',$id)->delete();
	return redirect()->route('usuarioRoles',['id'=>$idusuario]);
});
*/
Route::get('/usuarioTesis/{id}/{d?}', function($id,$d='T'){
	return redirect()->route('usuarioRoles',['id'=>$id,'d'=>$d]);
})->name('usuarioTesis');

//Cuenta del usuario
Route::get('/usuarioCuenta','AcademicoController@usuarioCuenta')->name('usuarioCuenta');
Route::post('/usuarioCambiaDato','AcademicoController@usuarioCambiaDato')->name('usuarioCambiaDato');
Route::post('/usuarioCambiaEmail','AcademicoController@usuarioCambiaEmail')->name('usuarioCambiaEmail');
Route::post('/contrasenaCambiar','AcademicoController@contrasenaCambiar')->name('contrasenaCambiar');



//********************************************************************************************************


/**
 * Tesis
 */
//***Ruta para los academicos
Route::get('/tesis/{gen?}','AcademicoController@tesis')->name('tesisA');

//***Ruta para los tesistas
Route::get('/tesisTesistas/{gen?}','TesistaController@tesis')->name('tesisT');

Route::get('/tesisNueva', 'AcademicoController@tesisNueva')->name('tesisNueva');

Route::get('/tesisEditar/{id}', 'AcademicoController@tesisEditar')->name('tesisEditar');

Route::get('/tesisAprobar/{id}', function($id){
	tesis\Tesis::where('id','=',$id)->update(['estado'=>2]);
	return redirect()->route('tesisA');
});

Route::get('/tesisTesista/{id}','AcademicoController@tesisTesista')->name('tesisTesista');

/**
 * Ruta para asignar docentes (coasesores y revisores) a la tesis
 */
Route::post('/tesisAsignar','AcademicoController@tesisAsignar')->name('tesisAsignar');

/**
 * Ruta para remover docentes (coasesores y revisores) a la tesis
 */
Route::get('/tesisRemoverRevisor','AcademicoController@tesisRemoverRevisor')->name('tesisRemoverRevisor');

/*
	Ruta para que los tesistas seleccionen la tesis
 */
Route::post('/tesisSeleccionar','TesistaController@tesisSeleccionar')->name('tesisSeleccionar');


/**
 * Ruta para definir estado de la tesis
 */
Route::post('/tesisEstado', 'AcademicoController@tesisEstado')->name('tesisEstado');


/**
 * Rutas para asignar o remover tesistas de la tesis
 */
Route::get('/asignaTesista/{idtesis}/{idtesista}','AcademicoController@asignaTesista')->name('asignaTesista');
Route::get('/tesisRemoverTesista/{idtesis}/{idtesista}','AcademicoController@tesisRemoverTesista')->name('tesisRemoverTesista');
//*****************************************


Route::post('/tesisGuardar','AcademicoController@tesisGuardar')->name('tesisGuardar');

Route::get('/tesisEliminar/{idtesis}', function($idtesis){
	tesis\Tesis::where('id',$idtesis)->delete();
	return redirect()->action('AcademicoController@tesis');
});

Route::get('/tesisCalifica',function(){
	return view('academico.tesiscalifica');
})->name('tesisCalifica');


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
		return [false];
	}
})->name('getTesisId');

//Guardar la URL del documento de Google Drive
Route::post('/tesisGuardarUrl',function(Request $request){
	if($request->ajax()){
		tesis\Tesis::where('id',$request->idtesis)
					->update(['urldoc'=>$request->urldoc]);
	}
	return [true];
})->name('tesisGuardarUrl');

//Subir archivo de PDF
Route::get('/tesisSubirPdf/{idtesis}', function($idtesis){
	$t = tesis\Tesis::where('id',$idtesis)->get();
	return view('tesista.tesissubirpdf',compact('t'));
})->name('tesisSubirPdf');

Route::post('/tesisGuardarPdf','TesistaController@tesisGuardarPdf')->name('tesisGuardarPdf');

Route::get('/tesisPdfVer/{id}', function($id){
	$ruta = tesis\Tesis::select('pdf')->where('id',$id)->get();
	if(Auth::user()->priv == 5){
		return view('tesista.tesispdfver',compact('ruta'));
	}else{
		return view('academico.tesispdfver',compact('ruta'));
	}
})->name('tesisPdfVer');


Route::post('/getTesisDetalle', 'AcademicoController@getTesisDetalle')->name('getTesisDetalle');

/**
 * Rutas para la lista de tesistas, para asignar carrera y generacion
 */

Route::post('/asignaCarr', 'AcademicoController@asignaCarr')->name('asignaCarr');

Route::post('/asignaGen', 'AcademicoController@asignaGen')->name('asignaGen');

//la siguente ruta es para que el tesiste se asigna la carrera y la generacion a si mismo
Route::post('/tesistaProGen','HomeController@tesistaProGen')->name('tesistaProGen');


/**Rutas para las calificaciones
*
*/
Route::get('cal','CalController@index')->name('cal');
				
Route::post('store','CalController@store')->name('store');

Route::post('cal/{idcal}/{cal}','CalController@update')->name('update');

Route::get('eliminaCal/{id}','CalController@destroy')->name('destroy');

/********************************************************************************************************
 * Calendario (actividades)
 */
Route::get('agenda','AgendaController@index')->name('agenda');

Route::post('actividadGuardar','AgendaController@store')->name('actividadGuardar');

Route::post('actividadActualizar','AgendaController@update')->name('actividadActualizar');

Route::post('cambiarActividad','AgendaController@cambiarActividad')->name('cambiarActividad');

//obtener las actividades cuando hay cambio de mes
Route::get('obtenerActividades/{mes}/{anio}','AgendaController@obtenerActividades')->name('obtenerActividades');




/********************************************************************************************************
 * Mensajes
 */


Route::get('/mensajes/{idu}/{fmensajes}', 'AcademicoController@mensajes')->name('mensajes');

Route::post('/enviarMensaje','AcademicoController@enviarMensaje')->name('enviarMensaje');

Route::post('/leerMensaje',function(Request $request){
	if($request->ajax()){
		$mensaje = tesis\Mensaje::select('mensaje.*','users.nombre')
									->join('users','mensaje.idusuario_de','=','users.id')
									->where('mensaje.id','=',$request->idmensaje)
									->get()->toArray();
		tesis\Mensaje::where('mensaje.id',$request->idmensaje)->update(['leido'=>'1']);
		return response()->json($mensaje);
	}else{
		return [false];
	}
});

Route::get('/mensajeBorrar/{idmensaje}',function($idmensaje){	
	tesis\Mensaje::where('id',$idmensaje)->delete();
	return redirect()->action('AcademicoController@mensajes',['idu'=>Auth::user()->id,'fmensaje'=>2]);
});