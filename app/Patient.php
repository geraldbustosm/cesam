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
      'id', 'DNI','nombre1', 'nombre2', 'apellido1', 'apellido2', 'sexo_id', 'fecha_nacimiento', 'prevision_id'
  ];

  public function stage()
  {
    return $this->hasMany('App\Stage', 'paciente_id');
  }

  public function prevition(){
    return $this->belongsTo('App\Prevition', 'prevision_id');
  }
  public function sex(){
    return $this->belongsTo('App\Sex', 'sexo_id');
  }
}
