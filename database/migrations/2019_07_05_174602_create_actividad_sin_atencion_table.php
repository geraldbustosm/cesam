<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActividadSinAtencionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actividad_sin_atencion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('asistencia');
            $table->date('fecha');
            $table->unsignedInteger('codigo_grupal')->nullable();
            $table->timestamps();

            $table->unsignedBigInteger('paciente_id')->nullable();
            $table->unsignedBigInteger('etapa_id')->nullable();

            $table->foreign('paciente_id')->references('id')->on('paciente');
            $table->foreign('etapa_id')->references('id')->on('etapa');
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
        Schema::dropIfExists('actividad_sin_atencion');
    }
}
