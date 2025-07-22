<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index()
    {
        $cars = Car::all();
        return view('cars.index', compact('cars'));
    }

    public function create()
    {
        return view('cars.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'additional_info' => 'nullable|string',
            'export_option' => 'boolean',
            'price' => 'required|numeric|min:0',
            'year' => 'required|integer|min:1900|max:'.(date('Y') + 1),
            'kilometers' => 'required|integer|min:0',
            'steering_side' => 'required|string|in:left,right',
            'wheel_size' => 'required|string|max:50',
            'color' => 'required|string|max:50',
            'body_condition' => 'required|string|max:100',
            'mechanical_condition' => 'required|string|max:100',
            'interior_color' => 'required|string|max:50',
            'seats' => 'required|integer|min:1',
            'doors' => 'required|integer|min:1',
            'engine_size' => 'required|numeric|min:0',
            'horsepower' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        Car::create($request->all());

        return redirect()->route('cars.create')->with('success', 'Car details saved successfully!');
    }
}