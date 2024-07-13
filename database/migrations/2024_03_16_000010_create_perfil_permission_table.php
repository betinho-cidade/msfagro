<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerfilPermissionTable extends Migration
{

    public function up()
    {
        Schema::create('perfil_permission', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('perfil_id');
            $table->unsignedBigInteger('permission_id');
            $table->timestamps();
            $table->foreign('perfil_id')->references('id')->on('perfils');
            $table->foreign('permission_id')->references('id')->on('permissions');
            $table->unique(['perfil_id', 'permission_id']);
        });
    }


    public function down()
    {
        Schema::dropIfExists('perfil_permission');
    }
}
