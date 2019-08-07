<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Speciality;

class Functionary extends Model
{
  public function speciality()
    {
        return $this->belongsToMany(Speciality::class, 'funcionario_posee_especialidad', 'funcionarios_id', 'especialidad_id');
    }
    
	use Notifiable;
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'funcionarios';
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = 
    [
      'id','profesion','nombre1', 'nombre2', 'apellido1', 'user_id', 'activa'
    ];
}