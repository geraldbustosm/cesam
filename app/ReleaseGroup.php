<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ReleaseGroup extends Model
{
  use Notifiable;
  /**
   * The table associated with the model.
   *
   * @var string
   */

  public $table = 'grupo_alta';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable =
  [
    'id', 'descripcion', 'activa'
  ];

  public function release()
  {
    return $this->hasMany('App\Release');
  }
}
