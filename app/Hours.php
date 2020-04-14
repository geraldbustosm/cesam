<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hours extends Model
{
    public $table = 'funcionario_posee_horas_actividad'; 
    /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'id', 'funcionario_id','actividad_id','horasDeclaradas','horasRealizadas'
    ];
}