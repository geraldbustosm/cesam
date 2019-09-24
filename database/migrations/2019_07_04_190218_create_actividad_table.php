<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActividadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public  function up()
    {
        Schema::create('actividad', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('codigo_grupal')->nullable();
            $table->timestamps();
            $table->string('descripcion');
            $table->string('codigo')->nullable();

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
        Schema::dropIfExists('actividad');
    }
}
