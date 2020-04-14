<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Functionary;

class Speciality extends Model
{
  use Notifiable;
  /**
   * The table associated with the model.
   *
   * @var string
   */
  public $table = 'especialidad';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable =
  [
    'id', 'descripcion', 'activa'
  ];

  public function functionary()
  {
    return $this->belongsToMany(Functionary::class, 'funcionario_posee_especialidad', 'especialidad_id', 'funcionarios_id');
  }

  public function provision()
  {
    return $this->belongsToMany(Provision::class, 'prestacion_posee_especialidad', 'especialidad_id', 'prestacion_id');
  }

  public function activity()
  {
    return $this->belongsToMany(Activity::class, 'actividad_posee_especialidad', 'especialidad_id', 'actividad_id');
  }
  
  public function type()
  {
    return $this->belongsToMany(Type::class, 'tipo_posee_especialidad_canasta',  'especialidad_id', 'tipo_id');
  }
}
