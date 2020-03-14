<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDireccionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('direccion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pais')->nullable();
            $table->string('region')->nullable();
            $table->string('comuna')->nullable();
            $table->string('calle')->nullable();
            $table->string('numero')->nullable();
            $table->string('departamento')->nullable();
            $table->timestamps();
            $table->boolean('activa')->default(1);
            $table->bigInteger('paciente_id')->nullable();
            $table->foreign('paciente_id')->references('id')->on('paciente');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('direccion');
    }
}
