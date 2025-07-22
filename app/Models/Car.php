<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'make',
        'model',
        'additional_info',
        'export_option',
        'price',
        'year',
        'kilometers',
        'steering_side',
        'wheel_size',
        'color',
        'body_condition',
        'mechanical_condition',
        'interior_color',
        'seats',
        'doors',
        'engine_size',
        'horsepower',
        'description',
    ];
}