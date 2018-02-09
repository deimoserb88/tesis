<?php

namespace tesis\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use DB;


use tesis\User;
use tesis\Tesis;
use tesis\Tesista;
use tesis\Programa;
use tesis\UT;
use tesis\Rol;
use tesis\Mensaje;



class AcademicoController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }


    public function index(){
    	return view('academico.home');
    }

    public function usuariosAcademicos(){
        $u = User::where('priv','>',Auth::user()->priv)
                    ->where('priv','<','5')
                    ->get();
        $tipo_usuario = 1;//1~4 -> academicos
        $p = Programa::all();
        return view('academico.ua',compact('u','errores','tipo_usuario','p'));
    }

    public function usuariosTesistas(Request $request,$gen=''){
        //$gen = $gen==''?$gen:'%'.$gen.'%';
        $rol = $request->session()->get('rol');
        if(is_null($rol)){
            $rol = [];
        }
        if(Auth::user()->priv > 1){
            $progs = array_column($rol,'idprograma');//programas en los que el usuario tiene rol
            $ut = $request->session()->get('ut');//tesis con las que está relacionado el usuario
            //obteber los datos de los programas en los que el usuario tiene rol
            $p = User::select('programa.id','programa.programa','programa.abrev')
                        ->join('rol','users.id','=','rol.idusuario')
                        ->join('programa','rol.idprograma','=','programa.id')
                        ->distinct()
                        ->where('users.id','=',Auth::user()->id)->get();
        }else{
            $progs = Programa::select('id')->get()->toArray();//usado mas abajo para seleccionar a los tesistas
            $p = Programa::select('id','programa','abrev')->get();
        }

        //return $rol;
        //obtener los tesistas
        $u = User::select('users.id','users.nombre','users.nocontrol','users.priv','tesista.idtesis','tesista.idprograma','tesista.gen')
                    ->join('tesista','users.id','=','tesista.idusuario')
                    ->where('users.priv','>=','5')
                    ->where('tesista.gen','like','%'.$gen.'%')
                    ->whereIn('tesista.idprograma',$progs)
                    ->get();



        $g = Tesista::select(DB::raw('distinct(gen)'))->where('gen','!=','')->get();
        return view('academico.ut',compact('u','p','g','ut','gen','rol'));
    }


    public function usuariosNuevos(Request $request){
        $u = User::where('priv','=','9')->get();
        $tipo_usuario = 9;//9 -> nuevos
        if(Auth::user()->priv == 1){
            $p = Programa::all();
        }else{
            $p = Rol::select('programa.programa','programa.id')
                        ->join('programa','rol.idprograma','=','programa.id')
                        ->where('rol.idusuario','=',Auth::user()->id)
                        ->get();
        }
        return view('academico.ua',compact('u','errores','tipo_usuario','p'));
    }


/*select a.id,a.programa,a.abrev
from users c inner join rol b on c.id = b.idusuario inner join programa a on b.idprograma = a.id
where c.id = 4*/


    /**
     * [usuarioGuardar guardar susuarios por tipo]
     * @param  Request $request      [request]
     * @param  string  $accion       [c->Create, u->Update]
     * @return [type]                [hace un redirect]
     */
    public function usuarioGuardar(Request $request,$accion="c"){

        if($accion=='c'){
            $reglas = [
                'nombre' => 'required|string|max:191',
                'nocontrol' => 'required|string|max:8|unique:users',
                'email' => 'required|string|email|max:191|unique:users|regex:/^([A-Za-z0-9\._-])*@ucol.mx$/',
                'password' => 'required|string|min:6',
            ];
        }elseif($accion=='u'){
            $reglas = [
                'nombre' => 'required|string|max:191',
                'nocontrol' => 'required|string|max:8',
                'email' => 'required|string|email|max:191|regex:/^([A-Za-z0-9\._-])*@ucol.mx$/',
                'login' => 'required|string|max:20',
            ];
        }
        $validador = Validator::make($request->all(),$reglas);

        if($validador->fails()){
            if($accion=='c'){
                $u = User::where('priv','>',Auth::user()->priv)
                        ->where('priv','<','5')
                        ->get();
                $errores = $validador->errors();
                $tipo_usuario = $request->priv;
                return view('academico.ua',compact('u','errores','request','tipo_usuario'));
            }elseif($accion=='u'){
                $u = User::where('id','=',$request->id)->get();
                $errores = $validador->errors();
                return view('academico.usuarioeditar',compact('u','errores','request'));
            }
        }else{
            $u = User::firstOrNew(['id'=>$request->id]);
            $u->nombre = $request->nombre;
            $u->nocontrol = $request->nocontrol;
            $u->login = $accion == 'c'?$request->nocontrol:$request->login;
            $u->email = $request->email;
            if($accion == 'c'){
                $u->password = bcrypt($request->password);
            }
            $u->priv = $request->priv;
            $u->save();

            /**
             * El segmento que sigue es para guardar los datos
             * en la tabla tesista si priv es 5
             */
            if($request->priv == 5){
                $datos = [
                    'idusuario'=>$u->id,
                    'idprograma'=>(isset($request->carr)?$request->carr:null),
                    'gen'=>$request->gen
                    ];
                $t = Tesista::firstOrCreate($datos);
            }


            if($request->priv < 5){
                
                return redirect()->action('AcademicoController@usuarioRoles',['id'=>$request->id]);
                
                //return redirect()->action('AcademicoController@usuariosAcademicos');
                
            }elseif($request->priv == 5){
                return redirect()->action('AcademicoController@usuariosTesistas');
            }else{
                return redirect()->action('AcademicoController@usuariosNuevos');
            }
        }
    }

    public function usuarioEliminar($idtu){
        list($id,$tu) = explode(":",$idtu);
        User::where('id','=',$id)->delete();
        if($tu < 5){
            return redirect()->action('AcademicoController@usuariosAcademicos');
        }elseif($tu == 5){
            return redirect()->action('AcademicoController@usuariosTesistas');
        }else{
            return redirect()->action('AcademicoController@usuariosNuevos');
        }
    }


    public function usuarioRoles(Request $request,$id,$d='R'){
        //Las tesis que imparte
        $t = Tesis::select('tesis.nom','tesis.gen','tesis.estado','ut.rol')
                    ->join('ut','tesis.id','=','ut.idtesis')
                    ->where('ut.idusuario','=',$id)
                    ->get();

        //los roles que tiene
        $r = Rol::select('rol.id','rol.rol','programa.programa','rol.idprograma')
                    ->join('programa','rol.idprograma','=','programa.id')
                    ->where('rol.idusuario','=',$id)
                    ->get();
        //su nombre
        $u = User::select('id','nombre','nocontrol')
                    ->where('id','=',$id)
                    ->get();

        //los programas del plantel
        if(Auth::user()->priv == 1){//si tiene privilegios 1 (el mas alto)
            $p = Programa::all();
        }else{//si tiene privilegios 2, aqui no se llega con privilegios menores
            $p = Rol::select('programa.programa','programa.id')
                        ->join('programa','rol.idprograma','=','programa.id')
                        ->where('rol.idusuario','=',Auth::user()->id)
                        ->get();
        }
        $g = Tesis::select(DB::raw('distinct(gen)'))->get();

        $urol = $request->session()->get('rol'); //el rol del usuario en sesion

        //en caso de
        //return $urol;
        if($d == 'R'){
            return view('academico.usuariorol',compact('t','r','u','p','g','urol'));
        }elseif($d == 'T'){
            return view('academico.usuariotesis',compact('t','r','u','p','g','urol'));
        }

    }


    public function rolAsignar(Request $request){

        $datos = [  'idusuario'=>$request->id,
                    'idprograma'=>$request->prog
                    ];

        if($request->rol < 9){
            Rol::insert($datos + ['rol'=>$request->rol]);
        }else{
            Tesista::insert($datos + ['gen'=>$request->gen]);
        }
        
        return redirect()->route('usuarioRoles',['id'=>$request->id]);
    }


    public function asignaCarr(Request $request){
        if($request->ajax()){

            Tesista::updateOrCreate(
                ['idusuario'=>$request->pk],
                ['idprograma'=>$request->value]
            );

            return response()->json(['success'=>true]);
        }
    }

    public function asignaGen(Request $request){
        if($request->ajax()){

            $t = Tesista::where('idusuario','=',$request->pk)
                        ->update(['gen'=>$request->value]);

            return response()->json(['success'=>true]);
        }
    }


//cuenta del usuario
    public function usuarioCuenta(Request $request){

        //roles activos
        $r = Rol::select('rol.rol','rol.created_at','programa.programa')
                    ->leftJoin('programa','programa.id','=','rol.idprograma')
                    ->where('rol.idusuario','=',Auth::user()->id)
                    ->get();

        $t = UT::select('tesis.nom','tesis.gen','tesis.estado','programa.programa')
                    ->join('tesis','tesis.id','=','ut.idtesis')
                    ->join('programa','programa.id','=','ut.idprograma')
                    ->where('ut.idusuario','=',Auth::user()->id)
                    ->get();
        return view('academico.usuariocuenta',compact('r','t'));

    }

    public function usuarioCambiaDato(Request $request){
        if($request->ajax()){
            $u = User::where('id','=',$request->pk)
                        ->update([$request->name=>$request->value]);

            return response()->json(['success'=>true]);
        }
    }

    public function usuarioCambiaEmail(Request $request){

        if($request->ajax()){

            $reglas = [
                'value' => 'required|string|email|max:191|regex:/^([A-Za-z0-9\._-])*@ucol.mx$/',
            ];

            $validador = Validator::make($request->all(),$reglas);

            if(!$validador->fails()){
                $u = User::where('id','=',$request->pk)
                        ->update(['email'=>$request->value]);
                return response()->json(['success'=>true]);
            }else{
                //return $validador->messages();
                return response()->json(['success'=>false,'msg'=>'Debe ser una dirección de correo de la Universida de Colima']);
            }

        }
    }

    public function contrasenaCambiar(Request $request){
        User::where('id',Auth::user()->id)->update(['password'=>bcrypt($request->contrasenanueva)]);
        return redirect()->route('usuarioCuenta');
    }


//****************************************


/**
 * TESIS
 */

    public function tesisNueva(){

        $idusuario = Auth::user()->id;

        $progs = Programa::select('programa.id','programa.programa')
                        ->join('rol','programa.id','=','rol.idprograma')
                        ->where('rol.idusuario','=',$idusuario)
                        ->where('rol.rol','=',6)
                        ->distinct()
                        ->get();
        return view('academico.tesisnueva',compact('progs'));
    }


    public function tesisGuardar(Request $request){
        $t = new Tesis;
        $t->idprograma = $request->idprograma;
        $t->nom = $request->titulo;
        $t->gen = $request->gen;
        $t->desc = $request->desc;
        $t->tesistas = $request->tesistas;
        $t->save();

        $ut = new UT(['idtesis'=>$t->id,'idusuario'=>Auth::user()->id]);
        $ut->idprograma = $request->idprograma;
        $ut->rol = 6;
        $ut->save();

        return redirect()->action('AcademicoController@tesis');

    }

/**
 * [tesisAsignar Funcion para asingar los roles de los academicos a la tesis]
 * @param  Request $request [description]
 * @return [type]           [description]
 */
    public function tesisAsignar(Request $request){
        UT::updateOrCreate( ['idusuario'=>$request->id,'idtesis'=>$request->idtesis],
                            ['idprograma'=>$request->prog,
                             'rol'=>$request->rol
                            ]);
        return redirect()->route('usuarioRoles',['id'=>$request->id]);
    }

    public function tesisTesista(Request $request,$id){
        //la tesis en cuestion
        $t = Tesis::select('tesis.id','tesis.nom','tesis.tesistas','programa.abrev')
                    ->join('programa','tesis.idprograma','=','programa.id')
                    ->where('tesis.id','=',$id)
                    ->get();

        //los tesistas asignados a la tesis
        $ta = User::select('users.id','nocontrol','nombre')
                    ->join('tesista','users.id','=','tesista.idusuario')
                    ->where('tesista.idtesis','=',$id)
                    ->get();

        //Se seleccionaran solo los tesistas del programas al que pertenece la tesis....
        $idprograma = Programa::select('programa.id')
                    ->leftJoin('tesis','programa.id','=','tesis.idprograma')
                    ->where('tesis.id','=',$id)
                    ->get();

        //...los tesistas disponibles para asignar
        $tt = User::select('users.id','users.nocontrol','users.nombre')
                    ->join('tesista','users.id','=','tesista.idusuario')
                    ->whereNull('tesista.idtesis')
                    ->where('tesista.idprograma','=',$idprograma->first()->id)
                    ->get();
        $urol = $request->session()->get('rol'); //el rol del usuario en sesion

        return view('academico.tesistesista',compact('t','ta','tt','urol'));

    }

    public function asignaTesista(Request $request){
        $gencarr = Tesis::select('gen','idprograma')->find($request->idtesis);

        Tesista::updateOrCreate(['idusuario' => $request->idtesista],
                ['idprograma' => $gencarr->idprograma,
                'gen' => $gencarr->gen,
                'idtesis' => $request->idtesis]
            );
        Tesis::where('id',$request->idtesis)->update(['estado'=>3]);
        return redirect()->route('tesisTesista',['id'=>$request->idtesis]);
    }

    public function tesisRemoverTesista(Request $request){
        Tesista::where('idusuario','=',$request->idtesista)
                ->where('idtesis','=',$request->idtesis)
                ->delete();
        return redirect()->route('tesisTesista',['id'=>$request->idtesis]);
    }



    /****************************************
     * [Listado de Tesis]
     * @return $tesis
     */
    public function tesis(Request $request){

        //tesis que administra como director, cord. acad., cord. carrera, presidente academia o profesor de seminario

        $urol = $request->session()->get('rol');

        //se obtienen todos los programas en los que el usuario tiene un rol entre 1 y 5
        $progs = [];
        if(!is_null($urol)){
            foreach($urol as $ur){
                if($ur['rol'] <= 5){
                    $progs[] = $ur['idprograma'];
                }
            }
        }else{
            $urol = [];
        }

        //los usuarios con privilegios 1 pueden ver las tesis de todos los programas
        if(Auth::user()->priv == 1){
            $tesisA = Tesis::select('tesis.*','programa.abrev')
                        ->join('programa','tesis.idprograma','=','programa.id')
                        ->get();

        }else{
            $tesisA = Tesis::select('tesis.*','programa.abrev')
                        ->join('programa','tesis.idprograma','=','programa.id')
                        ->whereIn('programa.id',$progs)
                        ->get();
        }

        //Tesis en las que participa como asesor, coasesor o revisor
        $idusuario = Auth::user()->id;
        $tesisP = Tesis::select('tesis.*','programa.abrev','ut.rol')
                    ->join('programa','tesis.idprograma','=','programa.id')
                    ->join('ut','tesis.id','=','ut.idtesis')
                    ->where('ut.idusuario','like',$idusuario)
                    ->distinct()
                    ->get();
        return view('academico.tesis',compact('tesisA','tesisP','urol'));
    }


    public function getTesisDetalle(Request $request){
        if($request->ajax()){

            $t = Tesis::select('desc','nom','estado')
                        ->where('id','=',$request->idtesis)
                        ->get()->toArray();
            $a = User::select('users.nombre','ut.rol')
                        ->join('ut','users.id','=','ut.idusuario')
                        ->where('ut.idtesis','=',$request->idtesis)
                        ->whereIn('ut.rol',[6,7,8])
                        ->get()->toArray();
            $ts = User::select('users.nombre')
                        ->join('tesista','users.id','=','tesista.idusuario')
                        ->where('tesista.idtesis','=',$request->idtesis)
                        ->get()->toArray();

            return ['tesis'=>$t,'docentes'=>$a,'tesistas'=>$ts];
            //return response()->json(['tesis'=>$t,'asesor'=>$a,'tesistas'=>$ts]);

        }else{
            return [false];
        }
    }

/*
    Mensajes
*/

    /**
     * [enviarMensaje Ajax para guardar mensaje enviado]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function enviarMensaje(Request $request){
        if($request->ajax()){
            $nuevoMensaje = new Mensaje;
            $nuevoMensaje->idusuario_de = Auth::user()->id;
            $nuevoMensaje->idusuario_para = $request->idusuario;
            $nuevoMensaje->mensaje = $request->mensaje;
            $nuevoMensaje->save();
            return [true];
        }else{
            return [false];
        }
    }

    public function mensajes(Request $request){
        $leido = $request->fmensajes == 2 ? '%%' : $request->fmensajes;
        $m = Mensaje::select('mensaje.*','users.nombre')
                        ->join('users','mensaje.idusuario_de','=','users.id')
                        ->where('mensaje.idusuario_para','=',$request->idu)
                        ->where('mensaje.leido','like',$leido)
                        ->get();
        $fmensajes = $request->fmensajes;
        return view('academico.mensajes',compact('m','fmensajes'));
    }


}
