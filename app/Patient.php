<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Patient extends Model
{
	use Notifiable;
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'paciente';
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'id', 'DNI','nombre1', 'nombre2', 'apellido1', 'apellido2', 'sexo', 'fecha_nacimiento',
  ];
}
