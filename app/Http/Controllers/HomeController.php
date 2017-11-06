<?php

namespace tesis\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use tesis\Rol;
use tesis\UT;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Auth::user()->priv < 5){
            $rol = Rol::select('rol','idprograma')->where('idusuario','=',Auth::user()->id)->get()->toArray();
            $request->session()->put('rol',$rol);
            $ut = UT::select('idtesis')->where('idusuario','=',Auth::user()->id)->get()->toArray();
            $request->session()->put('ut',$ut);            
            return view('academico.home');
        }else{
            return "Tesista Home en construcción"; //view('tesista.home');            
        }
    }
}
