<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyNonNullableColumnsInCarsTable extends Migration
{
    public function up()
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->string('steering_side')->nullable()->change();
            $table->string('wheel_size')->nullable()->change();
            $table->string('interior_color')->nullable()->change();
            $table->integer('seats')->nullable()->change();
            $table->integer('doors')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->string('steering_side')->nullable(false)->change();
            $table->string('wheel_size')->nullable(false)->change();
            $table->string('interior_color')->nullable(false)->change();
            $table->integer('seats')->nullable(false)->change();
            $table->integer('doors')->nullable(false)->change();
        });
    }
}