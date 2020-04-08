<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEtapaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('etapa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->date('pci')->nullable();

            $table->unsignedBigInteger('programa_id');
            $table->unsignedBigInteger('alta_id')->nullable();
            $table->unsignedBigInteger('sigges_id');
            $table->unsignedBigInteger('procedencia_id');
            $table->unsignedBigInteger('funcionario_id');
            $table->unsignedBigInteger('paciente_id');

            $table->foreign('paciente_id')->references('id')->on('paciente');
            $table->foreign('programa_id')->references('id')->on('programa');
            $table->foreign('alta_id')->references('id')->on('alta')->nullable();
            $table->foreign('sigges_id')->references('id')->on('sigges');
            $table->foreign('procedencia_id')->references('id')->on('procedencia');
            $table->foreign('funcionario_id')->references('id')->on('funcionarios');

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
        Schema::dropIfExists('etapa');
    }
}
