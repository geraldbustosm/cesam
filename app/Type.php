<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Type extends Model
{
	use Notifiable;
  /**
   * The table associated with the model.
   *
   * @var string
   */
  public function provision()
    {
        return $this->hasMany('App\Provision');
    }
  protected $table = 'tipo_prestacion';
  
  public function speciality()
    {
        return $this->belongsToMany(Speciality::class, 'tipo_posee_especialidad_canasta', 'tipo_id', 'especialidad_id');
    }
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = 
    [
      'id','descripcion','activa'
    ];
}