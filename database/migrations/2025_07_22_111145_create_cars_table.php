<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('make');
            $table->string('model');
            $table->integer('year');
            $table->decimal('price', 8, 2);
            $table->integer('kilometers');
            $table->string('color');
            $table->string('body_condition');
            $table->string('mechanical_condition');
            $table->decimal('engine_size', 3, 1);
            $table->integer('horsepower');
            $table->integer('top_speed');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cars');
    }
}