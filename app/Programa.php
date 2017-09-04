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
}
