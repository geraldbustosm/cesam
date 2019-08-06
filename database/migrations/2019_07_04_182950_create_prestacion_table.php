<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrestacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prestacion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->double('frecuencia');
            $table->unsignedInteger('rangoEdad_inferior');
            $table->unsignedInteger('rangoEdad_superior');
            $table->string('ps-fam');
            $table->string('glosaTrasadora');
            $table->string('codigo');

            $table->unsignedBigInteger('tipo_id');

            $table->foreign('tipo_id')->references('id')->on('tipo_prestacion');
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
        Schema::dropIfExists('prestacion');
    }
}
