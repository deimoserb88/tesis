<?php

namespace tesis;

use Illuminate\Database\Eloquent\Model;

class TS extends Model
{

	protected $table = 'ts';

    protected $fillable = [
    		'idtesis',
    		'idusuario'
    ];

    public function User(){
    	return $this->belongsTo('tesis\User','idusuario');
    }

    public function Tesis(){
    	return $this->belongsTo('tesis\Tesis','idtesis');
    }

}
