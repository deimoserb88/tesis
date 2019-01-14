<?php

namespace tesis\Http\Controllers;

use tesis\Cal;
use tesis\Tesis;
use Illuminate\Http\Request;
use Auth;

class CalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $urol = $request->session()->get('rol');
        $t = Tesis::find($request->idtesis);  
        $ct = Cal::where('idtesis',$request->idtesis)->get();
        $p = Cal::where([['idtesis',$request->idtesis],['eval','<=',5]])->avg('cal');
       // dd($ct);
        
        if($request->d == 'v'){
            return ['ct'=>$ct,'t'=>$t,'p'=>$p];
        }
        elseif ($request->d == 'l') {
            //dd($ct);
            return view('academico.tesiscalifica',compact('urol','t','ct','p'));                    
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Cal::insert(['idtesis'=>$request->idtesis,'cal'=>$request->cal,'eval'=>$request->eval,'obs'=>$request->obs,'idusuario'=>Auth::user()->id]);
        return [$request->idtesis,$request->cal,$request->eval];//
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \tesis\Cal  $cal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        Cal::find($request->idcal)->update(['cal'=>$request->cal]);
        return;// [$request->idcal,$request->cal,$request->eval];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \tesis\Cal  $cal
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cal = Cal::findOrFail($id);
        $cal->delete();
    }
}
