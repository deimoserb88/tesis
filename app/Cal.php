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
        'eval','cal', 'obs'
    ];
}
