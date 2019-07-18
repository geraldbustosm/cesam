<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Patient extends Model
{
	use Notifiable;
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'paciente';
  /**
   * The model's default values for attributes.
   *
   * @var array
   */
  protected $attributes = [
      'prevision' => 1,
  ];
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'nombre1', 'nombre2', 'apellido1', 'apellido2', 'sexo', 'fecha_nacimiento',
  ];

  public function searchableAs()
    {
        return 'posts_index';
    }

  public function toSearchableArray()
    {
        $array = $this->toArray();

        // Customize array...

        return $array;
    }
}
