<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFuncionarioPoseeActividadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funcionario_posee_actividad', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->unsignedBigInteger('funcionarios_id');
            $table->unsignedBigInteger('actividad_id');

            $table->foreign('funcionarios_id')->references('id')->on('funcionarios');
            $table->foreign('actividad_id')->references('id')->on('actividad');
            $table->boolean('activa')->default(1);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('funcionario_posee_actividad');
    }
}
