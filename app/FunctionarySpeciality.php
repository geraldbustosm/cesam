<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FunctionarySpeciality extends Model
{
    protected $table = 'funcionario_posee_especialidad'; 
    /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'id', 'funcionarios_id','especialidad_id'
    ];
}
