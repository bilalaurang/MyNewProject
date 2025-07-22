<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('make');
            $table->string('model');
            $table->text('additional_info')->nullable();
            $table->boolean('export_option')->default(false);
            $table->decimal('price', 10, 2);
            $table->integer('year');
            $table->integer('kilometers');
            $table->string('steering_side');
            $table->string('wheel_size');
            $table->string('color');
            $table->string('body_condition');
            $table->string('mechanical_condition');
            $table->string('interior_color');
            $table->integer('seats');
            $table->integer('doors');
            $table->decimal('engine_size', 4, 1);
            $table->integer('horsepower');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};