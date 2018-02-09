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
    	//El asesor, coasesor y revisores
        $acr = User::select('users.nombre','users.email','ut.rol')
                    ->join('ut','users.id','=','ut.idusuario')
                    ->where('ut.idtesis',$t->first()->id)
                    ->orderBy('ut.rol')
                    ->get();

    	if(count($t) > 0 ){
    		//ver si hay otros tesistas en la tesis
    		$tsts = User::select('nombre')
    					->join('tesista','users.id','=','tesista.idusuario')
    					->where('tesista.idtesis',$t->first()->id)
                        ->orderBy('nombre')
                        ->get();
    	}
        
    	return view('tesista.home',compact('t','acr','tsts'));
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

            return view('tesista.tesissubirpdf',compact('t'));
        }
        if(count($errors) > 0){
            return view('tesista.tesissubirpdf',compact('errors','t'));
        }
    }


}
