<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAtencionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atencion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('fecha');
            $table->boolean('asistencia');
            $table->timestamps();
            $table->timestamps('hora');
            $table->timestamps('duracion');

            $table->unsignedBigInteger('etapa_id');
            $table->unsignedBigInteger('funcionario_id');
            $table->unsignedBigInteger('prestacion_id');
            $table->unsignedBigInteger('actividad_id')->nullable();

            $table->foreign('etapa_id')->references('id')->on('etapa');
            $table->foreign('funcionario_id')->references('id')->on('funcionarios');
            $table->foreign('prestacion_id')->references('id')->on('prestacion');
            $table->foreign('actividad_id')->references('id')->on('actividad')->nullable();
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
        Schema::dropIfExists('atencion');
    }
}
