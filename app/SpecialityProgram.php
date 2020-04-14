<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class SpecialityProgram extends Model
{
	use Notifiable;
  /**
   * The table associated with the model.
   *
   * @var string
   */
  public $table = 'especialidad_programa';
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = 
    [
      'id', 'codigo','descripcion','activa'
    ];
}