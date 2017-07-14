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
        'nombre','nocontrol', 'email', 'password','login','rol','activo',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    private static $roles = [
                            0 =>'root',
                            1 =>'Director',
                            2 =>'Coordinador académico',
                            3 =>'Coordinador de carrera',
                            4 =>'Presidente de academia',
                            5 =>'Titular de Seminario de tesis',
                            6 =>'Asesor de tesis',
                            7 =>'Coasesor de tesis',
                            8 =>'Revisor',
                            9 =>'Tesista',
                    ];
    public static function rol($r){
        return self::$roles[$r];
    }   

 
}
