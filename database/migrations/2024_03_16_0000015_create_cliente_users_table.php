<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClienteUsersTable extends Migration
{

    public function up()
    {
        Schema::create('cliente_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('user_id')->unique('cliente_user_uk');
            $table->unsignedBigInteger('perfil_id');
            //$table->enum('tipo', ['AD', 'VS']);  //AD->Administrador  VS->Visualizador 
            $table->enum('menu_aberto', ['S', 'N'])->default('N');  //S->Sim  N->Não
            $table->timestamps();
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('perfil_id')->references('id')->on('perfils');
        });
    }


    public function down()
    {
        Schema::dropIfExists('cliente_users');
    }
}
