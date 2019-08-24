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
  public function stage()
    {
        return $this->hasMany('App\Stage');
    }
  protected $table = 'paciente';

  public function sex()
  {
    return $this->hasOne('App\Sex', 'sexo_id');
  }
  public function address()
  {
      return $this->belongsToMany(Address::class, 'paciente_posee_direccion', 'paciente_id', 'direccion_id');
  }
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'id', 'DNI','nombre1', 'nombre2', 'apellido1', 'apellido2', 'sexo_id', 'fecha_nacimiento',
  ];
}
