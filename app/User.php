<?php

namespace tesis;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre','nocontrol', 'email', 'password','login','priv','activo',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
 
    private static $priv = [
                            0 =>'root',
                            1 =>'Administrador de plantel',
                            2 =>'Administrador de programa (Coordinador/Presidente)',
                            3 =>'Profesor de Seminario',
                            4 =>'Docente',
                            5 =>'Tesista',
                    ];
    public static function priv($p){
        return self::$priv[$p];
    }   

 
}
