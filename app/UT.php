<?php

namespace tesis;

use Illuminate\Database\Eloquent\Model;

class UT extends Model
{
    protected $table = 'ut';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idprograma','rol','idusuario','idtesis'
    ];

    public function User(){
    	return $this->belongsTo('tesis\User','idusuario');
    }

    public function Tesis(){
    	return $this->belongsTo('tesis\Tesis','idtesis');
    }

}
