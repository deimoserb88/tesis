<?php

namespace tesis;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
     protected $table = 'rol';


    protected $fillable = [
        'idprograma','rol','idusuario',
    ];

    public function User(){
    	return $this->belongsTo('tesis\User','idusuario');
    }

    public function Programa(){
    	return $this->belongsTo('tesis\Tesis','idprograma');
    }     

    public static function rol($r){
		return [0 =>'Sin rol',
                1 =>'Director',
                2 =>'Coordinador académico',
                3 =>'Coordinador de carrera',
                4 =>'Presidente de academia',
                5 =>'Titular de Seminario de tesis',
                6 =>'Asesor',
                7 =>'Coasesor',
                8 =>'Revisor',
                9 =>'Tesista',
                ][$r];
    } 

}
