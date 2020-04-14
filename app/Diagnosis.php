<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Diagnosis extends Model
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

    public $table = 'diagnostico';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable =
    [
        'id', 'descripcion', 'activa'
    ];
}