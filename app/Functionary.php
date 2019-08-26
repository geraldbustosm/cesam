<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Speciality;
use App\User;

class Functionary extends Model
{
  public function speciality()
    {
        return $this->belongsToMany(Speciality::class, 'funcionario_posee_especialidad', 'funcionarios_id', 'especialidad_id');
    }

    public function user()
    {
        return $this->belongsTO(User::class,'user_id','id');
    }
    
	use Notifiable;
  /**
   * The table associated with the model.
   *
   * @var string
   */
  
  public function stage()
    {
        return $this->hasMany('App\Stage');
    }
  protected $table = 'funcionarios';
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = 
    [
      'id','profesion', 'user_id', 'activa'
    ];
}