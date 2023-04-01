<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLancamentosTable extends Migration
{

    public function up()
    {
        Schema::create('lancamentos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->unsignedBigInteger('produtor_id');
            $table->unsignedBigInteger('categoria_id');
            $table->unsignedBigInteger('origem')->nullable();
            $table->unsignedBigInteger('destino')->nullable();
            $table->enum('segmento', ['MG', 'MF']);  //MG->Movimentação Bovina  MF->Movimentação Fiscal
            $table->datetime('data_criacao');
            $table->enum('tipo', ['CP', 'VD', 'EG']);  //CP->Compra/Despesa  VD->Venda/Receita  EG->Engorda
            $table->enum('item_macho', ['M1', 'M2', 'M3', 'M4'])->nullable();  //M1->Macho de 0 à 12 meses  M2->Macho de 12 à 24 meses  M3->Macho de 25 à 36 meses  M4->Macho acima de 36 meses
            $table->enum('item_femea', ['F1'])->nullable();  //F1->Fêmea de 0 à 2 meses
            $table->string('item_texto', 300)->nullable();
            $table->string('path_documento', 500)->nullable();
            $table->string('documento', 50)->nullable();
            $table->string('path_gta', 500)->nullable();
            $table->string('gta', 50)->nullable();
            $table->integer('qtd_macho')->default(0);
            $table->integer('qtd_femea')->default(0);
            $table->string('observacao', 1000)->nullable();
            $table->timestamps();
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('produtor_id')->references('id')->on('produtors');
            $table->foreign('categoria_id')->references('id')->on('categorias');
            $table->foreign('origem')->references('id')->on('fazendas');
            $table->foreign('destino')->references('id')->on('fazendas');
        });
    }


    public function down()
    {
        Schema::dropIfExists('lancamentos');
    }
}
