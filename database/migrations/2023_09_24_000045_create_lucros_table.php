<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateLucrosTable extends Migration
{

    public function up()
    {
        Schema::create('lucros', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('produtor_id');
            $table->unsignedBigInteger('forma_pagamento_id');
            $table->datetime('data_lancamento');
            $table->decimal('valor', 20, 2);
            $table->string('path_comprovante', 500)->nullable();
            $table->string('observacao', 1000)->nullable();
            $table->timestamps();
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('produtor_id')->references('id')->on('produtors');
            $table->foreign('forma_pagamento_id')->references('id')->on('forma_pagamentos');
            $table->index(['forma_pagamento_id'], 'idx_lucros_forma_pagamento');
            $table->index(['data_lancamento'], 'idx_lucros_data');
        });
    }


    public function down()
    {
        Schema::dropIfExists('lucros');
    }
}
