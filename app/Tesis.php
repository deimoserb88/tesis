<?php

namespace tesis;

use Illuminate\Database\Eloquent\Model;

class Tesis extends Model
{
        protected $table = 'tesis';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idprograma','nom', 'desc', 'tesistas','urldoc','pdf','gen',
    ];

    public function UT(){
    	return $this->hasMany('tesis\UT','idtesis','id');
    }
    
    public function Cal(){
    	return $this->hasMany('tesis\Cal','idtesis','id');
    }

    public static function tesisEstado($edo){
    	return ['Error','Tesis nueva','Aprobada','Asignada','Concluida','No aprobada','Eliminada'][$edo];
    }


}
