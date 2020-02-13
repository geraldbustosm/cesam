<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**/

    public function functionary(){
        return $this->hasOne(Functionary::class);
    }
    /**
     * The attributes that are mass assignable.
     * agrege esta linea para ver los cambios
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'primer_nombre', 'segundo_nombre','apellido_materno','apellido_paterno','rut', 'email', 'password', 'rol','id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
