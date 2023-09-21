<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClienteNotificacaosTable extends Migration
{

    public function up()
    {
        Schema::create('cliente_notificacaos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('notificacao_id');
            $table->timestamps();
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('notificacao_id')->references('id')->on('notificacaos');
            $table->unique(['cliente_id', 'notificacao_id'], 'cliente_notificacao_uk');
        });
    }


    public function down()
    {
        Schema::dropIfExists('cliente_notificacaos');
    }
}
