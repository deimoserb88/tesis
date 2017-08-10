<?php

namespace tesis\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;


use tesis\User;

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

    public function usuariosTesistas(Request $request){                
        $u = User::where('priv','=','5')->get();
        $tipo_usuario = 5;//5 -> tesistas
        return view('academico.ua',compact('u','errores','tipo_usuario'));
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
                return view('academico.ua',compact('u','errores','request'));
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
            if($request->priv < 5){
                return redirect()->action('AcademicoController@usuariosAcademicos');
            }else{
                return redirect()->action('AcademicoController@usuariosTesistas');
            }
        }
    }
}
