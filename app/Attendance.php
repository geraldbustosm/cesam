<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Attendance extends Model
{
  use Notifiable;
  /**
   * The table associated with the model.
   *
   * @var string
   */
  public $table = 'atencion';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  public function functionary()
  {
    return $this->belongsTo('App\Functionary', 'funcionario_id');
  }

  public function stage()
  {
    return $this->belongsTo('App\Stage', 'etapa_id');
  }

  public function provision()
  {
    return $this->belongsTo('App\Provision', 'prestacion_id');
  }

  public function activity()
  {
    return $this->belongsTo('App\Activity', 'actividad_id');
  }

  protected $fillable =
  [
    'id', 'funcionario_id', 'etapa_id', 'prestacion_id', 'actividad_id', 'fecha', 'asistencia', 'hora', 'duracion', 'activa', 'abre_canasta', 'repetido'
  ];
}
