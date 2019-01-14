<?php

namespace tesis\Http\Controllers;

use Illuminate\Http\Request;
use tesis\Tesis;
use tesis\Agenda;
use tesis\User;
use tesis\Mensaje;
use Auth;

class AgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {      
        $urol = session()->get('rol');
        $es_psi = in_array(5, array_column($urol, 'rol')) ? 'true':'false';//es profesor de seminario de inv?
        $idprogs = array_column($urol,'idprograma');
        $t = Tesis::select('id','idprograma','nom')->get()->toArray();
        //$t = Tesis::select('id','idprograma','nom')->whereIn('idprograma',$idprogs)->get()->toArray();
        //dd(array_intersect($idprogs,array_column($t,'idprograma')));
        $ams = Agenda::whereMonth('inicio',date('m'))->whereYear('inicio',date('Y'))->get();//actividades del mes
        $res = User::select('nombre','users.id')
                    ->join('agenda','users.id','=','agenda.idusuario')
                    ->distinct()
                    ->get()->toArray();
        //dd($res[array_search(61,array_column($res,'id'))]['nombre']);
        return view('academico.agenda',compact('urol','es_psi','idprogs','t','ams','res'));
    }

    //obtener las actividades despues de un cambio de mes con los contorles de navegacion
    public function obtenerActividades($mes,$anio){
        $ams = Agenda::select('tesis.nom','agenda.*','users.nombre')
                    ->join('tesis','agenda.idtesis','=','tesis.id')
                    ->join('users','agenda.idusuario','=','users.id')
                    ->whereMonth('agenda.inicio',$mes)
                    ->whereYear('agenda.inicio',$anio)->get();//actividades del mes

        return $ams ;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        list($idtesis,$idprograma) = explode(":",$request->idtesis);
        $a             = new Agenda;
        $a->idtesis    = $idtesis;
        $a->idprograma = $idprograma;
        $a->idusuario  = Auth::user()->id;
        $a->actividad  = $request->actividad;
        $a->inicio     = $request->start.':00';
        $a->fin        = $request->end.':00';
        $a->color      = $request->color;
        $a->save();

        //crear un mensaje para los involucrados (tesistas, asesores, etc.)
        //tesistas
        $t = User::select('users.id','nombre')
                        ->join('tesista','tesista.idusuario','=','users.id')
                        ->where('tesista.idtesis',$idtesis)
                        ->get();
        //academicos
        $ac = User::select('users.id','nombre')
                        ->join('ut','ut.idusuario','=','users.id')
                        ->where('ut.idtesis',$idtesis)
                        ->get();
        //mensajes tesistas
        foreach($t as $tesista){
            $m = new Mensaje;
            $m->idusuario_de = Auth::user()->id;
            $m->idusuario_para = $tesista->id;
            $m->mensaje = 'Su tesis tiene programada la actividad '.$request->actividad.' para le fecha ' . $request->start.':00, verifique el calendario';
            $m->save();
        }
        //mensajes academicos
        foreach($ac as $acad){
            $m = new Mensaje;
            $m->idusuario_de = Auth::user()->id;
            $m->idusuario_para = $acad->id;
            $m->mensaje = 'Una de las tesis en las que participa tiene programada la actividad '.$request->actividad.' para le fecha ' . $request->start.':00, verifique el calendario';
            $m->save();
        }

        return redirect()->action('AgendaController@index');
    }

 
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if($request->delete){
            Agenda::where('id',$request->id)->delete();            
        }else{
            list($idtesis,$idprograma) = explode(":",$request->idtesis);
            Agenda::where('id',$request->id)->update([
                    'actividad' => $request->actividad,
                    'idtesis'   => $idtesis,
                    'idprograma'=> $idprograma,
                    'color'     => $request->color,
                    ]);
        }
        return redirect()->action('AgendaController@index');
    }

    public function cambiarActividad(Request $request){
        Agenda::where('id',$request->id)->update([
                'inicio' => $request->start.':00',
                'fin'    => $request->end.':00',
                ]);
        return $request->end;
    }


}
