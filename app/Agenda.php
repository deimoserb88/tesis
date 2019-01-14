<?php

namespace tesis;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
   protected $table = 'agenda';
   protected $fillable = ['idtesis','idprograma','idusuario','actividad','inicio','fin','color'];
}
