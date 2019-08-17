<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProvisionSpeciality extends Model
{
    protected $table = 'prestacion_posee_especialidad'; 
    /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'id', 'prestacion_id','especialidad_id'
    ];
}

