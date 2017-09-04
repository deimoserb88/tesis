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
        'idprograma','rol'
    ];
}
