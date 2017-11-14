<?php

namespace tesis;

use Illuminate\Database\Eloquent\Model;

class Tesista extends Model
{
    protected $table = 'tesista';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idprograma','idusuario', 'idtesis','gen',
    ];

    public function User(){
    	return $this->belongsTo('tesis\User','idusuario');
    }    
}
