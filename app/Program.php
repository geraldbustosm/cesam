<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Program extends Model
{
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
    public $table = 'programa';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable =
    [
        'id','especialidad_programa_id', 'descripcion', 'activa'
    ];
}