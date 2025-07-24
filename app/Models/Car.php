<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'make', 'model', 'year', 'price', 'kilometers', 'color', 'body_condition',
        'mechanical_condition', 'engine_size', 'horsepower', 'top_speed', 'steering_side',
        'wheel_size', 'interior_color', 'seats', 'doors', 'description', 'showroom_info'
    ];
}