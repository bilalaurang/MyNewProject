<!DOCTYPE html>
<html>
<head>
    <title>Car Details</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .container { max-width: 800px; margin: 25px auto; }
        .card { border: 1px solid #e0e0e0; border-radius: 10px; background: #fff; }
        .card-header { background: #003087; padding: 15px; border-bottom: 1px solid #e0e0e0; border-radius: 10px 10px 0 0; }
        .card-header h3 { margin: 0; font-size: 1.7rem; color: #fff; font-weight: 700; text-transform: uppercase; }
        .card-body { padding: 25px; background: #fafafa; }
        .car-info { margin-bottom: 20px; }
        .car-info p { margin: 6px 0; font-size: 1.1rem; }
        .car-info p strong { background: #eef1f4ff; color: #000; padding: 4px 8px; border-radius: 4px; }
        .section-title { font-size: 1.2rem; color: #003087; margin: 25px 0 12px; font-weight: 600; }
        .description { white-space: pre-wrap; line-height: 1.6; font-size: 1.1rem; color: #333; background: #f0f0f0; padding: 12px; border-radius: 6px; }
        .btn-primary { background: #007bff; color: #fff; padding: 12px 25px; border: none; border-radius: 6px; transition: background 0.3s; }
        .btn-primary:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>{{ $car->year }} {{ $car->make }} {{ $car->model }}</h3>
            </div>
            <div class="card-body">
                <div class="car-info">
                    <p><strong>Price:</strong> {{ number_format($car->price, 2) }} PKR</p>
                    <p><strong>Kilometers:</strong> {{ number_format($car->kilometers) }} km</p>
                    <p><strong>Color:</strong> {{ $car->color }}</p>
                    <p><strong>Body Condition:</strong> {{ $car->body_condition }}</p>
                    <p><strong>Mechanical Condition:</strong> {{ $car->mechanical_condition }}</p>
                    <p><strong>Engine Size:</strong> {{ $car->engine_size }}L</p>
                    <p><strong>Horsepower:</strong> {{ $car->horsepower }} hp</p>
                    <p><strong>Top Speed:</strong> {{ $car->top_speed }} km/h</p>
                    <p><strong>Steering Side:</strong> {{ ucfirst($car->steering_side) }}</p>
                    <p><strong>Wheel Size:</strong> {{ $car->wheel_size }} inches</p>
                    <p><strong>Interior Color:</strong> {{ $car->interior_color }}</p>
                    <p><strong>Seats:</strong> {{ $car->seats }}</p>
                    <p><strong>Doors:</strong> {{ $car->doors }}</p>
                </div>
                <div class="section-title">Description</div>
                <div class="description">{!! nl2br(e(str_replace('$', '', $car->description))) !!}</div>
                @if (session('success'))
                    <p style="color: green;">{{ session('success') }}</p>
                @endif
                @if (session('error'))
                    <p style="color: red;">{{ session('error') }}</p>
                @endif
                <a href="{{ route('cars.index') }}" class="btn btn-primary mt-3">Back to Listings</a>
                <button class="btn btn-edit mt-3" onclick="toggleEditForm()">Edit</button>
                <form action="{{ route('cars.update', $car->id) }}" method="POST" class="edit-form" id="editForm" style="display: none;">
                    @method('PUT')
                    @csrf
                    <div class="form-group">
                        <label for="make">Make</label>
                        <input type="text" name="make" id="make" value="{{ old('make', $car->make) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="model">Model</label>
                        <input type="text" name="model" id="model" value="{{ old('model', $car->model) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="year">Year</label>
                        <input type="number" name="year" id="year" value="{{ old('year', $car->year) }}" min="1900" max="{{ date('Y') + 1 }}" required>
                    </div>
                    <div class="form-group">
                        <label for="price">Price (PKR)</label>
                        <input type="number" name="price" id="price" value="{{ old('price', $car->price) }}" min="0" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="kilometers">Kilometers</label>
                        <input type="number" name="kilometers" id="kilometers" value="{{ old('kilometers', $car->kilometers) }}" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="color">Color</label>
                        <input type="text" name="color" id="color" value="{{ old('color', $car->color) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="body_condition">Body Condition</label>
                        <input type="text" name="body_condition" id="body_condition" value="{{ old('body_condition', $car->body_condition) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="mechanical_condition">Mechanical Condition</label>
                        <input type="text" name="mechanical_condition" id="mechanical_condition" value="{{ old('mechanical_condition', $car->mechanical_condition) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="engine_size">Engine Size (L)</label>
                        <input type="number" name="engine_size" id="engine_size" value="{{ old('engine_size', $car->engine_size) }}" min="0" step="0.1" required>
                    </div>
                    <div class="form-group">
                        <label for="horsepower">Horsepower</label>
                        <input type="number" name="horsepower" id="horsepower" value="{{ old('horsepower', $car->horsepower) }}" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="top_speed">Top Speed (km/h)</label>
                        <input type="number" name="top_speed" id="top_speed" value="{{ old('top_speed', $car->top_speed) }}" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="steering_side">Steering Side</label>
                        <select name="steering_side" id="steering_side" required>
                            <option value="left" {{ old('steering_side', $car->steering_side) === 'left' ? 'selected' : '' }}>Left</option>
                            <option value="right" {{ old('steering_side', $car->steering_side) === 'right' ? 'selected' : '' }}>Right</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="wheel_size">Wheel Size (inches)</label>
                        <input type="text" name="wheel_size" id="wheel_size" value="{{ old('wheel_size', $car->wheel_size) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="interior_color">Interior Color</label>
                        <input type="text" name="interior_color" id="interior_color" value="{{ old('interior_color', $car->interior_color) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="seats">Seats</label>
                        <input type="number" name="seats" id="seats" value="{{ old('seats', $car->seats) }}" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="doors">Doors</label>
                        <input type="number" name="doors" id="doors" value="{{ old('doors', $car->doors) }}" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description">{{ old('description', str_replace('$', '', $car->description)) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="showroom_info">Showroom Info</label>
                        <textarea name="showroom_info" id="showroom_info">{{ old('showroom_info', $car->showroom_info) }}</textarea>
                    </div>
                    <button type="submit" class="btn-save">Save Changes</button>
                    <button type="button" class="btn-cancel" onclick="toggleEditForm()">Cancel</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleEditForm() {
            const form = document.getElementById('editForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>

    <style>
        .btn-edit { background: #ffc107; color: #fff; padding: 12px 25px; border: none; border-radius: 6px; transition: background 0.3s; margin-left: 10px; }
        .btn-edit:hover { background: #e0a800; }
        .edit-form { margin-top: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { font-weight: 600; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .btn-save { background: #28a745; color: #fff; padding: 10px 20px; border: none; border-radius: 4px; transition: background 0.3s; }
        .btn-save:hover { background: #218838; }
        .btn-cancel { background: #dc3545; color: #fff; padding: 10px 20px; border: none; border-radius: 4px; margin-left: 10px; transition: background 0.3s; }
        .btn-cancel:hover { background: #c82333; }
    </style>
</body>
</html>