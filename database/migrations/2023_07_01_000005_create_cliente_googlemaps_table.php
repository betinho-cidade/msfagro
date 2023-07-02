<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClienteGooglemapsTable extends Migration
{

    public function up()
    {
        Schema::create('cliente_googlemaps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cliente_id');
            $table->datetime('anomes_referencia');
            $table->integer('qtd_apimaps')->default(0);
            $table->integer('qtd_geolocation')->default(0);
            $table->timestamps();
            $table->foreign('cliente_id')->references('id')->on('clientes');
        });
    }


    public function down()
    {
        Schema::dropIfExists('cliente_googlemaps');
    }
}
