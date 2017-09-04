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



class AcademicoController extends Controller
{
    
    public function __construct(){
        $this->middleware('auth');
    }


    public function index(){        
    	return view('academico.home');
    }

    public function usuariosAcademicos(Request $request){                
        $u = User::where('priv','>',Auth::user()->priv)
                    ->where('priv','<','5')
                    ->get();
        $tipo_usuario = 1;//1~4 -> academicos
        return view('academico.ua',compact('u','errores','tipo_usuario'));
    }

    public function usuariosTesistas(Request $request,$gen=''){        
        //$gen = $gen==''?$gen:'%'.$gen.'%';
        $u = User::select(DB::raw('users.id,users.nombre,users.nocontrol,users.priv,tesista.idtesis,tesista.idprograma,tesista.gen'))
                    ->leftJoin('tesista','users.id','=','tesista.idusuario')
                    ->where('users.priv','=','5')
                    ->where('tesista.gen','like','%'.$gen.'%')
                    ->get(); 
        $p = Programa::select('id','programa','abrev')->where('activo','=',1)->get();
        $g = Tesista::distinct('gen')->where('gen','!=','')->get();
        return view('academico.ut',compact('u','p','g','gen'));
    }
    
    public function usuariosNuevos(Request $request){                
        $u = User::where('priv','=','9')->get();
        $tipo_usuario = 9;//9 -> nuevos
        return view('academico.ua',compact('u','errores','tipo_usuario'));
    }





    /**
     * [usuarioGuardar guardar susuarios por tipo]
     * @param  Request $request      [request]
     * @param  string  $accion       [c->Crud, u->crUd]     
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
                    ];
                $t = Tesista::firstOrCreate($datos);
            }            


            if($request->priv < 5){
                return redirect()->action('AcademicoController@usuariosAcademicos');
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

    public function tesis(){
        
        return view('academico.tesis');
    }

}
