<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Speciality;
use App\Type;

class Provision extends Model
{
  use Notifiable;
  /**
   * The table associated with the model.
   *
   * @var string
   */
  public $table = 'prestacion';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable =
  [
    'id', 'frecuencia', 'rangoEdad_inferior', 'rangoEdad_superior', 'ps_fam', 'glosaTrasadora', 'codigo', 'tipo_id', 'activa'
  ];
  
  public function speciality()
  {
    return $this->belongsToMany(Speciality::class, 'prestacion_posee_especialidad', 'prestacion_id', 'especialidad_id');
  }

  public function type()
  {
    return $this->belongsTo(Type::class, 'tipo_id');
  }
}
