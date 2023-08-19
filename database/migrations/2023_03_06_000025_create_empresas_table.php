<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{

    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cliente_id');
            $table->string('nome', 500);
            $table->enum('tipo_pessoa', ['PF', 'PJ']);
            $table->string('cpf_cnpj', 14);
            $table->enum('status', ['A', 'I'])->default('A');  //A->Ativo  I->Inativo
            $table->timestamps();
            $table->unique(['cliente_id', 'cpf_cnpj'], 'empresa_uk');
            $table->foreign('cliente_id')->references('id')->on('clientes');
        });
    }


    public function down()
    {
        Schema::dropIfExists('empresas');
    }
}
