<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRooms3DTable extends Migration
{
    public function up()
    {
        Schema::create('rooms3D', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color');
            $table->json('size');
            $table->json('position');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rooms3D');
    }
}
