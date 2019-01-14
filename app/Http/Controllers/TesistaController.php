<?php

namespace tesis\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

use tesis\User;
use tesis\Tesis;
use tesis\Tesista;
use tesis\Programa;
use tesis\UT;
use tesis\TS;
use tesis\Rol;
use tesis\Mensaje;

class TesistaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
    	//La tesis que tiene asignada
    	$t = Tesis::select('tesis.*')
    				->join('tesista','tesis.id','=','tesista.idtesis')
    				->where('tesista.idusuario',Auth::user()->id)
    				->get();
                    
        if(count($t) > 0 ){
            //El asesor, coasesor y revisores
            $acr = User::select('users.nombre','users.email','ut.rol')
                            ->join('ut','users.id','=','ut.idusuario')
                            ->where('ut.idtesis',$t->first()->id)
                            ->orderBy('ut.rol')
                            ->get();

                            //ver si hay otros tesistas en la tesis
    		$tsts = User::select('nombre')
    					->join('tesista','users.id','=','tesista.idusuario')
    					->where('tesista.idtesis',$t->first()->id)
                        ->orderBy('nombre')
                        ->get();
        }else{
            //El presidente de la academia por si no tiene tesis asignada
            $tst = Tesista::select('idprograma')
                        ->where('idusuario',Auth::user()->id)
                        ->get();
            $pa = User::select('users.id','nombre','email','rol.rol')
                        ->join('rol','users.id','=','rol.idusuario')
                        ->where([['rol.idprograma',$tst->first()->idprograma],['rol.rol','<',6]])
                        ->get();
        }
        
    	return view('tesista.home',compact('t','acr','tsts','pa'));
    }

    public function tesisGuardarPdf(Request $request){
        $t = Tesis::where('id',$request->idtesis)->get();        
        $errors = [];
        if($request->pdf->getClientSize() > 10000000){
            $errors = collect(['muygrande'=>$request->pdf->getClientSize()]);
        }elseif(!preg_match('/(pdf)/',$request->pdf->getClientMimeType())){
            $errors = collect(['nombreinvalido'=>$request->pdf->getClientOriginalName()]);            
        }else{
            
            $nnombre = 'tesis_' . str_pad($t->first()->id,5,'0',STR_PAD_LEFT) . '.pdf';

            $request->pdf->move(storage_path('app/public/tesis/'),$nnombre);

            Tesis::where('id',$request->idtesis)->update(['pdf' => $nnombre]);

            return redirect()->action('TesistaController@index');
        }
        if(count($errors) > 0){
            
            return view('tesista.tesissubirpdf',compact('errors','t'));
        }
    }

    public function tesis(Request $request){
        $gen = isset($request->gen) ? $request->gen : '%%';
        $g = Tesis::select('gen')->distinct()->orderBy('gen')->get();        
        $tesis = Tesis::select('tesis.*','programa.abrev')
                    ->join('programa','tesis.idprograma','=','programa.id')
                    ->where([['gen','like',$gen],['estado','>',1]])
                    ->get();
        $miTesis = User::select('users.nombre','tesis.*')
                    ->join('tesista','users.id','=','tesista.idusuario')
                    ->join('tesis','tesista.idtesis','=','tesis.id')
                    ->where('users.id',Auth::user()->id)
                    ->get();
        $mts = TS::select('idtesis')->where('idusuario',Auth::user()->id)->get()->toArray();//mis tesis seleccionadas
        return view('tesista.tesis',compact('tesis','miTesis','gen','g','mts'));
    }

    public function tesisSeleccionar(Request $request){
         if($request->ajax()){            
            $ts  = TS::where([['idtesis',$request->idtesis],['idusuario',Auth::user()->id]])->count();
            $tts = TS::where('idusuario',Auth::user()->id)->count();
            if($ts==0){
                if($tts<3){
                    TS::insert([
                        ['idtesis'=>$request->idtesis,'idusuario'=>Auth::user()->id]
                    ]);
                    return ['r'=>1];//1 seleccionar tesis
                }else{
                    return ['r'=>3];//3 ya selecciono tres tesis        
                }
            }else{            
                TS::where([['idtesis',$request->idtesis],['idusuario',Auth::user()->id]])->delete();
                return ['r'=>2];//2 retirar seleccion de la tesis
            }
        }         
        return [];
    }

}
