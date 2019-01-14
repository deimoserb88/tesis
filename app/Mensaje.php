<?php

namespace tesis;

use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    protected $table = 'mensaje';


    protected $fillable = [
        'idusuario_de','idusuario_para','mensaje','leido',
    ];

    public function User(){
    	return $this->belongsTo('tesis\User','idusuario_de');
    }
}
