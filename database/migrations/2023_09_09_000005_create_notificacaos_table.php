<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificacaosTable extends Migration
{

    public function up()
    {
        Schema::create('notificacaos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('movimentacao_id')->nullable();
            $table->string('nome', 300);
            $table->longtext('resumo');
            $table->string('url_notificacao', 1000)->nullable();
            $table->datetime('data_inicio');
            $table->datetime('data_fim');            
            $table->enum('destaque', ['S', 'N'])->default('N');  //S->Sim  N->Não  
            $table->enum('todos', ['S', 'N'])->default('S');  //S->Sim  N->Não  
            $table->enum('status', ['A', 'I'])->default('I');  //A->Ativo  I->Inativo         
            $table->timestamps();
            $table->foreign('movimentacao_id')->references('id')->on('movimentacaos');
        });
    }


    public function down()
    {
        Schema::dropIfExists('notificacaos');
    }
}
