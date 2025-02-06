<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('devices3d', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rooms3D_id')->constrained();
            $table->string('type'); // light/sensor
            $table->string('status')->default('off');
            $table->json('position'); // {x, y, z}
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices3_d');
    }
};
