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
 
    public static function priv($p){
        return ['root',
                'Administrador de plantel',
                'Administrador de programa (Coordinador/Presidente)',
                'Profesor de Seminario',
                'Docente',
                'Tesista',
                9=>'No definida',
                ][$p];
    }   

 
}
