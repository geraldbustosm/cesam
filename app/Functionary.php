<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Speciality;
use App\User;
use App\Provision;

class Functionary extends Model
{
  public function speciality()
  {
    return $this->belongsToMany(Speciality::class, 'funcionario_posee_especialidad', 'funcionarios_id', 'especialidad_id');
  }

  public function firstSpeciality()
  {
    return $this->belongsToMany(Speciality::class, 'funcionario_posee_especialidad', 'funcionarios_id', 'especialidad_id')->take(1);
  }

  public function user()
  {
    return $this->belongsTO(User::class, 'user_id', 'id');
  }
  public function posibleProvisions()
  {
    return $this->hasManyThrough(Provision::class, Speciality::class);
  }


  use Notifiable;
  /**
   * The table associated with the model.
   *
   * @var string
   */

  public function stage()
  {
    return $this->hasMany('App\Stage', 'funcionario_id');
  }
  
  public $table = 'funcionarios';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable =
  [
    'id', 'user_id', 'activa', 'horasDeclaradas', 'horasRealizadas'
  ];
}
