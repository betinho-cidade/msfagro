<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutorsTable extends Migration
{

    public function up()
    {
        Schema::create('produtors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cliente_id');
            $table->string('nome', 500);
            $table->string('email', 255);
            $table->enum('tipo_pessoa', ['PF', 'PJ']);
            $table->string('cpf_cnpj', 14);
            $table->string('telefone', 20)->nullable();
            $table->string('inscricao_estadual', 20)->nullable();
            $table->enum('inscricao_representante', ['S', 'N'])->default('N');  //S->Inscrição pertence ao representante informado  N->Inscrição não pertence ao representante informado
            $table->string('end_cep', 8)->nullable();
            $table->string('end_cidade', 60)->nullable();
            $table->string('end_uf', 2)->nullable();
            $table->string('end_logradouro', 80)->nullable();
            $table->string('end_numero', 20)->nullable();
            $table->string('end_bairro', 60)->nullable();
            $table->string('end_complemento', 40)->nullable();
            $table->enum('status', ['A', 'I'])->default('A');  //A->Ativo  I->Inativo
            $table->timestamps();
            $table->foreign('cliente_id')->references('id')->on('clientes');
        });
    }


    public function down()
    {
        Schema::dropIfExists('produtors');
    }
}
