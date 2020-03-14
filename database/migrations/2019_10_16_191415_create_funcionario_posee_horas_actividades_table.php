<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFuncionarioPoseeHorasActividadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funcionario_posee_horas_actividad', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->unsignedBigInteger('funcionario_id');
            $table->unsignedBigInteger('actividad_id');
            
            $table->float('horasDeclaradas')->default('0');
            $table->float('horasRealizadas')->default('0');

            $table->foreign('funcionario_id')->references('id')->on('funcionarios');
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
        Schema::dropIfExists('funcionario_posee_horas_actividad');
    }
}
