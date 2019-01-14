<?php

namespace tesis;

use Illuminate\Database\Eloquent\Model;

class Cal extends Model
{
    protected $table = 'cal';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'eval','cal', 'obs','idtesis','idusuario'
    ];

    public function User(){
    	return $this->belongsTo('tesis\User','idusuario');
    }

    public function Tesis(){
    	return $this->belongsTo('tesis\Tesis','idtesis');
    }

    
}
