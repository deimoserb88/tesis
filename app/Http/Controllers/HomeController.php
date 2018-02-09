<?php

namespace tesis\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use tesis\Rol;
use tesis\UT;
use tesis\User;
use tesis\Tesista;
use tesis\Programa;


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
        }elseif(Auth::user()->priv == 5){
            return redirect()->action('TesistaController@index');//view('tesista.home');
        }else{ //priv == 9, es un usuario nuevo al que no se le ha asignado rol o tesis
            if(strlen(Auth::user()->nocontrol) == 4){
                //es academico
                $tu = 'a';
                //coordinadores y presidentes de academia, los directivos no tienen ro,l asociado a programas
                $u = User::select('users.id','users.nombre','users.email','users.priv','rol.rol','programa.abrev')
                            ->leftJoin('rol','users.id','=','rol.idusuario')
                            ->leftJoin('programa','rol.idprograma','=','programa.id')
                            ->where('rol.rol','<=','4')
                            ->distinct()
                            ->orderBy('rol.rol','asc')
                            ->get();
            }else{
                //es tesista
                $tu = 't';
                //se valida si el tesista ya se registro como tesista (tabla tesista),
                $t = Tesista::where('idusuario','=',Auth::user()->id)->get()->toArray();
                $u = [];
                if(count($t)>0){
                    $u = User::select('users.id','users.nombre','users.email','users.priv','rol.rol')
                                ->join('rol','users.id','=','rol.idusuario')
                                ->whereIn('rol.rol',[4,5])                                
                                ->where('rol.idprograma','=',$t[0]['idprograma'])
                                ->distinct()
                                ->get();
                }
                $p = Programa::all();
            }
            return view('home',compact('tu','u','t','p'));
        }
    }

    //Este metodo es llamado cuando los tesistas entran por primera vez
    public function tesistaProGen(Request $request){
        $t = new Tesista(['idusuario'=>$request->idusuario]);
        $t->idprograma = $request->idprograma;
        $t->gen = $request->gen;
        $t->save();

        $s = User::where('id','=',$request->idusuario)->update(['priv'=>5]);

        return redirect()->action('HomeController@index');
    }

}
