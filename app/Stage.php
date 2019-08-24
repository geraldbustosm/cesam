<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Stage extends Model
{
	use Notifiable;
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'etapa';
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  public function diagnosis()
  {
    return $this->hasOne('App\Diagnosis', 'diagnostico_id');
  }
  protected $fillable = 
    [
      'id','diagnostico_id','programa_id','alta_id','sigges_id','procedencia_id','funcionario_id','paciente_id','activa'
    ];
}