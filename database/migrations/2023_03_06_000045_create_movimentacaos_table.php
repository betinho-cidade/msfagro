<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateMovimentacaosTable extends Migration
{

    public function up()
    {
        Schema::create('movimentacaos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lancamento_id')->nullable();
            $table->unsignedBigInteger('produtor_id');
            $table->unsignedBigInteger('forma_pagamento_id');
            $table->datetime('data_programada');
            $table->datetime('data_pagamento')->nullable();
            $table->enum('tipo', ['R' ,'D']); //R->Receita  D->Despesa
            $table->enum('segmento', ['MG', 'MF']);  //MG->Movimentação Bovina  MF->Movimentação Fiscal
            $table->decimal('valor', 8, 2);
            $table->string('path_comprovante', 500)->nullable();
            $table->string('nota', 50);
            $table->string('path_nota', 500);
            $table->enum('situacao', ['PD', 'PG', 'CL'])->default('PD');  //PD->Movimentação Pendente  PG->Movimentação Paga  CL->Movimentação Cancelada
            $table->string('item_texto', 300);
            $table->timestamps();
            $table->foreign('lancamento_id')->references('id')->on('lancamentos');
            $table->foreign('produtor_id')->references('id')->on('produtors');
            $table->foreign('forma_pagamento_id')->references('id')->on('forma_pagamentos');
            $table->index(['lancamento_id'], 'idx_movimentacaos_lancamento');
            $table->index(['forma_pagamento_id'], 'idx_movimentacaos_forma_pagamento');
            $table->index(['data_programada'], 'idx_movimentacaos_data');
            $table->index(['tipo'], 'idx_movimentacaos_tipo');
            $table->index(['tipo', 'data_programada'], 'idx_movimentacaos_tipo_data');
        });
    }


    public function down()
    {
        Schema::dropIfExists('movimentacaos');
    }
}
