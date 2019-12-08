<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('rut')->unique();
            $table->string('primer_nombre');
            $table->string('segundo_nombre')->nullable();
            $table->string('apellido_materno')->nullable();
            $table->string('apellido_paterno');
            $table->string('nombre');
            $table->string('email')->unique();
            $table->integer('rol');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('activa')->default(1);
            $table->rememberToken();
            $table->timestamps();

            //$table->unsignedBigInteger('rol_id');
            //$table->foreign('rol_id')->references('id')->on('rol');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
