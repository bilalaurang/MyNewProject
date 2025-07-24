<?php

use App\Http\Controllers\CarsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CarsController::class, 'index'])->name('cars.index');
Route::get('/cars/create', [CarsController::class, 'create'])->name('cars.create');
Route::post('/cars', [CarsController::class, 'store'])->name('cars.store');
Route::get('/cars/{car}', [CarsController::class, 'show'])->name('cars.show');