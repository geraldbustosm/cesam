<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSexoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sexo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->boolean('activa')->default(1);
            $table->string('descripcion')->unique();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sexo');
    }
}
