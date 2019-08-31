<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrevisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prevision', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('descripcion')->unique();
            $table->timestamps();
            $table->boolean('activa')->default(1);
        });
    }

    /*descripcion
     *descripcion the migrations.
     *descripcion
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prevision');
    }
}
