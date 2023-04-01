<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionRoleTable extends Migration
{

    public function up()
    {
        Schema::create('permission_role', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');
            $table->timestamps();
            $table->foreign('permission_id')->references('id')->on('permissions');
            $table->unique(['permission_id', 'role_id']);
            $table->foreign('role_id')->references('id')->on('roles');
        });
    }


    public function down()
    {
        Schema::dropIfExists('permission_role');
    }
}
