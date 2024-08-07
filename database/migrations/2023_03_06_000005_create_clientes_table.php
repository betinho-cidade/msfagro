<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{

    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('tipo', ['AG', 'PE', 'AB'])->default('PE');  //AG->Agricultor  PE->Pecuarista  AB->Ambos
            $table->string('nome', 200);
            $table->string('email', 255);
            $table->enum('tipo_pessoa', ['PF', 'PJ']);
            $table->string('cpf_cnpj', 14)->unique();
            $table->string('telefone', 20)->nullable();
            $table->string('end_cep', 8)->nullable();
            $table->string('end_cidade', 60)->nullable();
            $table->string('end_uf', 2)->nullable();
            $table->string('end_logradouro', 80)->nullable();
            $table->string('end_numero', 20)->nullable();
            $table->string('end_bairro', 60)->nullable();
            $table->string('end_complemento', 40)->nullable();
            $table->integer('qtd_apimaps')->default(0);
            $table->integer('qtd_geolocation')->default(0);
            $table->enum('status', ['A', 'I'])->default('A');  //A->Ativo  I->Inativo
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}
