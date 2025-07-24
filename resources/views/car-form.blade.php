@extends('layouts.main')

@section('content')
    <div class="container">
        <h1>Add New Car</h1>
        <form id="carForm" action="{{ route('cars.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="make">Make:</label>
                <input type="text" class="form-control" name="make" id="make" value="{{ old('make', 'Suzuki') }}" required>
                @error('make') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="model">Model:</label>
                <input type="text" class="form-control" name="model" id="model" value="{{ old('model', 'Swift') }}" required>
                @error('model') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="year">Year:</label>
                <input type="number" class="form-control" name="year" id="year" value="{{ old('year', 2020) }}" required>
                @error('year') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="price">Price (USD):</label>
                <input type="number" class="form-control" name="price" id="price" value="{{ old('price', 1500) }}" required>
                @error('price') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="kilometers">Kilometers:</label>
                <input type="number" class="form-control" name="kilometers" id="kilometers" value="{{ old('kilometers', 40000) }}" required>
                @error('kilometers') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="color">Color:</label>
                <input type="text" class="form-control" name="color" id="color" value="{{ old('color', 'Red') }}" required>
                @error('color') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="body_condition">Body Condition:</label>
                <input type="text" class="form-control" name="body_condition" id="body_condition" value="{{ old('body_condition', 'Good') }}" required>
                @error('body_condition') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="mechanical_condition">Mechanical Condition:</label>
                <input type="text" class="form-control" name="mechanical_condition" id="mechanical_condition" value="{{ old('mechanical_condition', 'Best') }}" required>
                @error('mechanical_condition') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="engine_size">Engine Size (L):</label>
                <input type="number" class="form-control" name="engine_size" id="engine_size" value="{{ old('engine_size', 1.3) }}" step="0.1" required>
                @error('engine_size') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="horsepower">Horsepower (hp):</label>
                <input type="number" class="form-control" name="horsepower" id="horsepower" value="{{ old('horsepower', 300) }}" required>
                @error('horsepower') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="top_speed">Top Speed (km/h):</label>
                <input type="number" class="form-control" name="top_speed" id="top_speed" value="{{ old('top_speed', 160) }}" required>
                @error('top_speed') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="steering_side">Steering Side:</label>
                <select class="form-control" name="steering_side" id="steering_side" required>
                    <option value="left" {{ old('steering_side', 'left') == 'left' ? 'selected' : '' }}>Left</option>
                    <option value="right" {{ old('steering_side', 'left') == 'right' ? 'selected' : '' }}>Right</option>
                </select>
                @error('steering_side') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="wheel_size">Wheel Size (inches):</label>
                <input type="text" class="form-control" name="wheel_size" id="wheel_size" value="{{ old('wheel_size', '15') }}" required>
                @error('wheel_size') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="interior_color">Interior Color:</label>
                <input type="text" class="form-control" name="interior_color" id="interior_color" value="{{ old('interior_color', 'Black') }}" required>
                @error('interior_color') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="seats">Number of Seats:</label>
                <input type="number" class="form-control" name="seats" id="seats" value="{{ old('seats', 5) }}" required>
                @error('seats') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="doors">Number of Doors:</label>
                <input type="number" class="form-control" name="doors" id="doors" value="{{ old('doors', 4) }}" required>
                @error('doors') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn btn-primary mt-3" id="submitButton">Submit</button>
        </form>
    </div>
@endsection