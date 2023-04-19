<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFazendasTable extends Migration
{

    public function up()
    {
        Schema::create('fazendas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cliente_id');
            $table->string('nome', 500);
            $table->string('end_cep', 8)->nullable();
            $table->string('end_cidade', 60)->nullable();
            $table->string('end_uf', 2)->nullable();
            $table->string('geolocalizacao', 1000);
            $table->integer('qtd_macho')->nullable();
            $table->integer('qtd_femea')->nullable();
            $table->enum('status', ['A', 'I'])->default('A');  //A->Ativo  I->Inativo
            $table->timestamps();
            $table->foreign('cliente_id')->references('id')->on('clientes');
        });
    }


    public function down()
    {
        Schema::dropIfExists('fazendas');
    }
}
