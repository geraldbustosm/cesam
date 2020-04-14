<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Patient;

class Attributes extends Model
{
  use Notifiable;
  /**
   * The table associated with the model.
   *
   * @var string
   */
  public $table = 'atributos';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  public function patients()
  {
    return $this->belongsToMany(Patient::class, 'paciente_posee_atributos', 'atributos_id', 'paciente_id');
  }
  
  protected $fillable = [
    'id', 'descripcion', 'tipo', 'activa'
  ];
}
