<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Stage extends Model
{
  use Notifiable;
  /**
   * The table associated with the model.
   *
   * @var string
   */
  public $table = 'etapa';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

  protected $fillable =
  [
    'id', 'diagnostico_id', 'programa_id', 'alta_id', 'sigges_id', 'procedencia_id', 'funcionario_id', 'paciente_id', 'activa'
  ];

  public function diagnosis()
  {
    return $this->belongsToMany(Diagnosis::class, 'etapa_posee_diagnostico',  'etapa_id', 'diagnostico_id');
  }

  public function attendance()
  {
    return $this->hasMany('App\Attendance', 'etapa_id')->where('atencion.activa', 1)->orderBy("fecha", "desc");
  }

  public function lastAttendance()
  {
    return $this->attendance()->orderBy("fecha", "desc")->take(1);
  }

  public function patient()
  {
    return $this->belongsTo('App\Patient', 'paciente_id');
  }

  public function functionary()
  {
    return $this->belongsTo('App\Functionary', 'funcionario_id');
  }

}
