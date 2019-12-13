<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePacienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paciente', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('DNI')->unique();;  
            $table->string('nombre1');
            $table->string('nombre2')->nullable();
            $table->string('apellido1');
            $table->string('apellido2')->nullable();
            $table->date('fecha_nacimiento');
            
            
            $table->unsignedBigInteger('prevision_id');
            $table->foreign('prevision_id')->references('id')->on('prevision');
            $table->unsignedBigInteger('sexo_id');
            $table->foreign('sexo_id')->references('id')->on('sexo');

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
        Schema::dropIfExists('paciente');
    }
}
