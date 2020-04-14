<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Speciality;

class Activity extends Model
{
  public function speciality()
  {
    return $this->belongsToMany(Speciality::class, 'actividad_posee_especialidad', 'actividad_id', 'especialidad_id');
  }

  public function type()
  {
    return $this->hasOne('App\Type', 'tipo_id');
  }

  use Notifiable;
  /**
   * The table associated with the model.
   *
   * @var string
   */
  public $table = 'actividad';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable =
  [
    'id', 'codigo_grupal', 'descripcion', 'actividad_abre_canasta', 'sin_asistencia', 'activa'
  ];
}
