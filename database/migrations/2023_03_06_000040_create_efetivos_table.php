<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEfetivosTable extends Migration
{

    public function up()
    {
        Schema::create('efetivos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->unsignedBigInteger('categoria_id');
            $table->datetime('data_programada');
            $table->unsignedBigInteger('origem_id')->nullable();
            $table->unsignedBigInteger('destino_id')->nullable();
            $table->enum('segmento', ['MG'])->default('MG');  //MG->Movimentação Bovina  MF->Movimentação Fiscal
            $table->enum('tipo', ['CP', 'VD', 'EG']);  //CP->Compra/Despesa  VD->Venda/Receita  EG->Engorda
            $table->enum('item_macho', ['M1', 'M2', 'M3', 'M4'])->nullable();  //M1->Macho de 0 à 12 meses  M2->Macho de 12 à 24 meses  M3->Macho de 25 à 36 meses  M4->Macho acima de 36 meses
            $table->enum('item_femea', ['F1', 'F2', 'F3', 'F4'])->nullable();  //F1->Fêmea de 0 à 2 meses  F2->Fêmea de 12 à 24 meses  F3->Fêmea de 25 à 36 meses  F4->Fêmea acima de 36 meses
            $table->string('path_gta', 500);
            $table->string('gta', 50);
            $table->integer('qtd_macho')->default(0);
            $table->integer('qtd_femea')->default(0);
            $table->string('observacao', 1000)->nullable();
            $table->timestamps();
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('categoria_id')->references('id')->on('categorias');
            $table->foreign('origem_id')->references('id')->on('fazendas');
            $table->foreign('destino_id')->references('id')->on('fazendas');
        });
    }


    public function down()
    {
        Schema::dropIfExists('efetivos');
    }
}
