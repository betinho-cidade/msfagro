<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAliquotasTable extends Migration
{

    public function up()
    {
        Schema::create('aliquotas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('base_inicio', 10, 2)->default(0);
            $table->decimal('base_fim', 10, 2)->default(0);
            $table->decimal('aliquota', 8, 2)->default(0);
            $table->decimal('parcela_deducao', 10, 2)->default(0);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }


    public function down()
    {
        Schema::dropIfExists('aliquotas');
    }
}
