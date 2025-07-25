<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hotel_occupancies', function (Blueprint $table) {
            $table->id();
            $table->integer('month');
            $table->integer('year');
            $table->float('occupancy');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hotel_occupancies');
    }
};
