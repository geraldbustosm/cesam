<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Provenance extends Model
{
    use Notifiable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'procedencia';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable =
    [
        'id', 'descripcion', 'activa'
    ];

    public function stage()
    {
        return $this->hasMany('App\Stage');
    }
}
