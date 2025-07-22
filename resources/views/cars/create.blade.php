<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Car Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Add Car Details</h1>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <form action="{{ route('cars.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="make" class="form-label">Make</label>
                    <input type="text" class="form-control @error('make') is-invalid @enderror" id="make" name="make" value="{{ old('make') }}">
                    @error('make')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="model" class="form-label">Model</label>
                    <input type="text" class="form-control @error('model') is-invalid @enderror" id="model" name="model" value="{{ old('model') }}">
                    @error('model')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="year" class="form-label">Year</label>
                    <input type="number" class="form-control @error('year') is-invalid @enderror" id="year" name="year" value="{{ old('year') }}">
                    @error('year')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">Price ($)</label>
                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}">
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="kilometers" class="form-label">Kilometers</label>
                    <input type="number" class="form-control @error('kilometers') is-invalid @enderror" id="kilometers" name="kilometers" value="{{ old('kilometers') }}">
                    @error('kilometers')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="steering_side" class="form-label">Steering Side</label>
                    <select class="form-control @error('steering_side') is-invalid @enderror" id="steering_side" name="steering_side">
                        <option value="left" {{ old('steering_side') == 'left' ? 'selected' : '' }}>Left</option>
                        <option value="right" {{ old('steering_side') == 'right' ? 'selected' : '' }}>Right</option>
                    </select>
                    @error('steering_side')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="wheel_size" class="form-label">Wheel Size (e.g., 16 inch)</label>
                    <input type="text" class="form-control @error('wheel_size') is-invalid @enderror" id="wheel_size" name="wheel_size" value="{{ old('wheel_size') }}">
                    @error('wheel_size')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="color" class="form-label">Color</label>
                    <input type="text" class="form-control @error('color') is-invalid @enderror" id="color" name="color" value="{{ old('color') }}">
                    @error('color')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="body_condition" class="form-label">Body Condition</label>
                    <input type="text" class="form-control @error('body_condition') is-invalid @enderror" id="body_condition" name="body_condition" value="{{ old('body_condition') }}">
                    @error('body_condition')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="mechanical_condition" class="form-label">Mechanical Condition</label>
                    <input type="text" class="form-control @error('mechanical_condition') is-invalid @enderror" id="mechanical_condition" name="mechanical_condition" value="{{ old('mechanical_condition') }}">
                    @error('mechanical_condition')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="interior_color" class="form-label">Interior Color</label>
                    <input type="text" class="form-control @error('interior_color') is-invalid @enderror" id="interior_color" name="interior_color" value="{{ old('interior_color') }}">
                    @error('interior_color')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="seats" class="form-label">Seats</label>
                    <input type="number" class="form-control @error('seats') is-invalid @enderror" id="seats" name="seats" value="{{ old('seats') }}">
                    @error('seats')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="doors" class="form-label">Doors</label>
                    <input type="number" class="form-control @error('doors') is-invalid @enderror" id="doors" name="doors" value="{{ old('doors') }}">
                    @error('doors')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="engine_size" class="form-label">Engine Size (e.g., 2.0 liters)</label>
                    <input type="number" step="0.1" class="form-control @error('engine_size') is-invalid @enderror" id="engine_size" name="engine_size" value="{{ old('engine_size') }}">
                    @error('engine_size')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="horsepower" class="form-label">Horsepower</label>
                    <input type="number" class="form-control @error('horsepower') is-invalid @enderror" id="horsepower" name="horsepower" value="{{ old('horsepower') }}">
                    @error('horsepower')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="export_option" class="form-label">Export Option</label>
                    <select class="form-control @error('export_option') is-invalid @enderror" id="export_option" name="export_option">
                        <option value="0" {{ old('export_option') == '0' ? 'selected' : '' }}>No</option>
                        <option value="1" {{ old('export_option') == '1' ? 'selected' : '' }}>Yes</option>
                    </select>
                    @error('export_option')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="additional_info" class="form-label">Additional Info (Optional)</label>
                <textarea class="form-control @error('additional_info') is-invalid @enderror" id="additional_info" name="additional_info">{{ old('additional_info') }}</textarea>
                @error('additional_info')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description (Optional)</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
</html>