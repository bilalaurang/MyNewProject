@extends('layouts.main')

@section('content')
    <div class="container">
        <h1>Edit Car</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('cars.update', $car->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group mb-3">
                <label for="make">Make</label>
                <input type="text" name="make" id="make" class="form-control" value="{{ old('make', $car->make) }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="model">Model</label>
                <input type="text" name="model" id="model" class="form-control" value="{{ old('model', $car->model) }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="year">Year</label>
                <input type="number" name="year" id="year" class="form-control" value="{{ old('year', $car->year) }}" min="1900" max="{{ date('Y') + 1 }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="price">Price (PKR)</label>
                <input type="number" name="price" id="price" class="form-control" value="{{ old('price', $car->price) }}" min="0" step="0.01" required>
            </div>
            <div class="form-group mb-3">
                <label for="kilometers">Kilometers</label>
                <input type="number" name="kilometers" id="kilometers" class="form-control" value="{{ old('kilometers', $car->kilometers) }}" min="0" required>
            </div>
            <div class="form-group mb-3">
                <label for="color">Color</label>
                <input type="text" name="color" id="color" class="form-control" value="{{ old('color', $car->color) }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="body_condition">Body Condition</label>
                <input type="text" name="body_condition" id="body_condition" class="form-control" value="{{ old('body_condition', $car->body_condition) }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="mechanical_condition">Mechanical Condition</label>
                <input type="text" name="mechanical_condition" id="mechanical_condition" class="form-control" value="{{ old('mechanical_condition', $car->mechanical_condition) }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="engine_size">Engine Size (L)</label>
                <input type="number" name="engine_size" id="engine_size" class="form-control" value="{{ old('engine_size', $car->engine_size) }}" min="0" step="0.1" required>
            </div>
            <div class="form-group mb-3">
                <label for="horsepower">Horsepower</label>
                <input type="number" name="horsepower" id="horsepower" class="form-control" value="{{ old('horsepower', $car->horsepower) }}" min="0" required>
            </div>
            <div class="form-group mb-3">
                <label for="top_speed">Top Speed (km/h)</label>
                <input type="number" name="top_speed" id="top_speed" class="form-control" value="{{ old('top_speed', $car->top_speed) }}" min="0" required>
            </div>
            <div class="form-group mb-3">
                <label for="steering_side">Steering Side</label>
                <select name="steering_side" id="steering_side" class="form-control" required>
                    <option value="left" {{ old('steering_side', $car->steering_side) === 'left' ? 'selected' : '' }}>Left</option>
                    <option value="right" {{ old('steering_side', $car->steering_side) === 'right' ? 'selected' : '' }}>Right</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="wheel_size">Wheel Size (inches)</label>
                <input type="text" name="wheel_size" id="wheel_size" class="form-control" value="{{ old('wheel_size', $car->wheel_size) }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="interior_color">Interior Color</label>
                <input type="text" name="interior_color" id="interior_color" class="form-control" value="{{ old('interior_color', $car->interior_color) }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="seats">Seats</label>
                <input type="number" name="seats" id="seats" class="form-control" value="{{ old('seats', $car->seats) }}" min="1" required>
            </div>
            <div class="form-group mb-3">
                <label for="doors">Doors</label>
                <input type="number" name="doors" id="doors" class="form-control" value="{{ old('doors', $car->doors) }}" min="1" required>
            </div>
            <div class="form-group mb-3">
                <label for="showroom_info">Showroom Info</label>
                <textarea name="showroom_info" id="showroom_info" class="form-control">{{ old('showroom_info', $car->showroom_info) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Car</button>
            <a href="{{ route('cars.show', $car->id) }}" class="btn btn-secondary ml-2">Cancel</a>
        </form>
    </div>
@endsection