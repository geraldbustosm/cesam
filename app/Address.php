<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Address extends Model
{
	use Notifiable;
  /**
   * The table associated with the model.
   *
   * @var string
   */
  public $table = 'direccion';
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'id','region', 'comuna', 'calle', 'numero', 'departamento'
  ];
}
