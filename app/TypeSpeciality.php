<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeSpeciality extends Model
{
    protected $table = 'tipo_posee_especialidad_canasta'; 
    /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'id', 'tipo_id','especialidad_id'
    ];
}

