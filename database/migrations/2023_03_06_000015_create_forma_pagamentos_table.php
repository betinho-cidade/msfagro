<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormaPagamentosTable extends Migration
{

    public function up()
    {
        Schema::create('forma_pagamentos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cliente_id');
            $table->enum('tipo_conta', ['CC', 'CP', 'PX', 'BL', 'ES'])->default('CC');  //CC->Conta Corrente  CP->Conta Poupança  PX->Pix  BL->Boleto  ES->spécie (dinheiro)
            $table->string('titular', 200);
            $table->string('doc_titular', 100);
            $table->string('banco', 200)->nullable();
            $table->string('agencia', 50)->nullable();
            $table->string('conta', 50)->nullable();
            $table->string('pix', 255)->nullable();
            $table->enum('status', ['A', 'I'])->default('A');  //A->Ativo  I->Inativo
            $table->timestamps();
            $table->foreign('cliente_id')->references('id')->on('clientes');
        });
    }


    public function down()
    {
        Schema::dropIfExists('forma_pagamentos');
    }
}
