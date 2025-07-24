<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToCarsTable extends Migration
{
    public function up()
    {
        Schema::table('cars', function (Blueprint $table) {
            if (!Schema::hasColumn('cars', 'top_speed')) {
                $table->integer('top_speed')->nullable()->after('horsepower');
            }
            if (!Schema::hasColumn('cars', 'horsepower')) {
                $table->integer('horsepower')->nullable()->after('engine_size');
            }
            if (!Schema::hasColumn('cars', 'engine_size')) {
                $table->decimal('engine_size', 3, 1)->nullable()->after('mechanical_condition');
            }
            if (!Schema::hasColumn('cars', 'body_condition')) {
                $table->string('body_condition')->nullable()->after('color');
            }
            if (!Schema::hasColumn('cars', 'mechanical_condition')) {
                $table->string('mechanical_condition')->nullable()->after('body_condition');
            }
            if (!Schema::hasColumn('cars', 'color')) {
                $table->string('color')->nullable()->after('kilometers');
            }
            if (!Schema::hasColumn('cars', 'kilometers')) {
                $table->integer('kilometers')->nullable()->after('price');
            }
            if (!Schema::hasColumn('cars', 'price')) {
                $table->decimal('price', 8, 2)->nullable()->after('year');
            }
            if (!Schema::hasColumn('cars', 'description')) {
                $table->text('description')->nullable()->after('top_speed');
            }
        });
    }

    public function down()
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn([
                'top_speed', 'horsepower', 'engine_size', 'body_condition',
                'mechanical_condition', 'color', 'kilometers', 'price', 'description'
            ]);
        });
    }
}