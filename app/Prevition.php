<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Prevition extends Model
{
    use Notifiable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'prevision';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable =
    [
        'id', 'descripcion', 'activa'
    ];

    public function patients(){
        return $this->hasMany('App\Patient');
    }
}
