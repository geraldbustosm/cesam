<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePacientePoseeAtributosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paciente_posee_atributos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->unsignedBigInteger('paciente_id');
            $table->unsignedBigInteger('atributos_id');

            $table->foreign('paciente_id')->references('id')->on('paciente');
            $table->foreign('atributos_id')->references('id')->on('atributos');

            /*** IF in the future someone whants to  have attributes and time logic
            $table->boolean('estadoAtributo')->nullable();
            $table->boolean('reseteable')->nullable();
            $table->date('fechaAtributo')->nullable();
            $table->unsignedBigInteger('numeroDiasReseteo')->nullable();
            ***/

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
        Schema::dropIfExists('paciente_posee_atributos');
    }
}
