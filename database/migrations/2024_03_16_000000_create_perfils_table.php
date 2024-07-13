<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerfilsTable extends Migration
{

    public function up()
    {
        Schema::create('perfils', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 20)->unique();
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('perfils');
    }
}
