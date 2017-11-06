<?php

namespace tesis;

use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
        protected $table = 'programa';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plan','area', 'programa', 'abrev','plantel'
    ];

    public function Tesis(){
    	return $this->hasMany('tesis\tesis','idprograma','id');
    }
    public function UT(){
    	return $this->hasMany('tesis\ut','idprograma','id');
    }
    public function Rol(){
    	return $this->hasMany('tesis\rol','idprograma','id');
    }
}
