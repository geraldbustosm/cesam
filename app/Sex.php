<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Sex extends Model
{
	use Notifiable;
  /**
   * The table associated with the model.
   *
   * @var string
   */
  public function patient()
    {
        return $this->hasMany('App\Patient');
    }
  protected $table = 'sexo';
  
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