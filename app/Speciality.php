<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Functionary;

class Speciality extends Model
{
  public function functionary()
    {
        return $this->belongsToMany(Functionary::class, 'funcionario_posee_especialidad', 'especialidad_id','funcionarios_id');
    }
  public function provision()
    {
        return $this->belongsToMany(Provision::class, 'prestacion_posee_especialidad', 'especialidad_id','prestacion_id');
    }
	
	use Notifiable;
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'especialidad';
  
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