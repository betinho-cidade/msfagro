<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGooglemapsTable extends Migration
{

    public function up()
    {
        Schema::create('googlemaps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('valor_credito', 10, 2)->default(0);
            $table->decimal('valor_extra_apimaps', 10, 2)->default(0);
            $table->integer('qtd_apimaps')->default(0);
            $table->decimal('valor_extra_geolocation', 10, 2)->default(0);
            $table->integer('qtd_geolocation')->default(0);
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('googlemaps');
    }
}
