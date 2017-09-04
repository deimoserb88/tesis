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
        'idprograma','nom', 'desc', 'tesistas','urldoc','pdf',
    ];

}
