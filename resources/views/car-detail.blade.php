<!DOCTYPE html>
<html>
<head>
    <title>Car Details - {{ $car->year }} {{ $car->make }} {{ $car->model }}</title>
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
        .section-title { font-size: 1.2rem; color: #003087; margin: 25px 0 0; font-weight: 600; }
        .description { 
            font-size: 1.1rem; 
            color: #333; 
            background: #f0f0f0; 
            border-radius: 6px; 
            text-align: left; 
            line-height: 1.2; 
            margin: 0; 
            padding: 0; 
            overflow: auto; /* Changed from hidden to allow scrolling */
            max-height: 300px; /* Increased height to fit content */
        }
        .description-content { 
            padding: 12px; /* Internal padding for content */
            margin: 0; /* Remove any margin */
        }
        .additional-details { 
            margin-top: 20px; 
            padding: 12px; 
            background: #e9ecef; 
            border-radius: 6px; 
            font-size: 1.1rem; 
            color: #333; 
        }
        .additional-details ul { list-style-type: none; padding: 0; }
        .additional-details li { margin: 5px 0; }
        .btn-primary { background: #007bff; color: #fff; padding: 12px 25px; border: none; border-radius: 6px; transition: background 0.3s; }
        .btn-primary:hover { background: #0056b3; }
        .alert-success { background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 18px; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 6px; margin-bottom: 18px; }
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
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>{{ $car->year }} {{ $car->make }} {{ $car->model }}</h3>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
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
                @if(!empty($descriptions['description']) || !empty($descriptions['showroom']))
                    <div class="section-title">Description 1</div>
                    <div class="description">
                        <div class="description-content">
                            @if(!empty($descriptions['description']))
                                {!! str_replace('$', '', $descriptions['description']) !!}<br>
                            @endif
                            @if(!empty($descriptions['showroom']))
                                {!! str_replace('$', '', $descriptions['showroom']) !!}<br>
                            @endif
                        </div>
                    </div>
                @endif
                @if(!empty($descriptions['main_features']))
                    <div class="section-title">Description 2</div>
                    <div class="description">
                        <div class="description-content">
                            <ul>
                                @foreach (explode("\n", $descriptions['main_features']) as $feature)
                                    @if (trim($feature) !== '' && strpos(trim($feature), '•') === 0)
                                        <li>{{ trim(str_replace('•', '', $feature)) }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                @if(!empty($descriptions['mixed_overview']))
                    <div class="section-title">Description 3</div>
                    <div class="description">
                        <div class="description-content">
                            {{ str_replace('$', '', $descriptions['mixed_overview']) }}
                        </div>
                    </div>
                @endif
                @if(!empty($additionalDetails))
                    <div class="section-title">Additional Features</div>
                    <div class="additional-details">
                        <ul>
                            @foreach (explode("\n", trim($additionalDetails)) as $detail)
                                @if (trim($detail) !== '')
                                    <li>{{ trim($detail) }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif
                <a href="{{ route('cars.index') }}" class="btn btn-primary mt-3">Back to Listings</a>
                <button class="btn btn-edit mt-3" onclick="toggleEditForm()">Edit</button>
                <form action="{{ route('cars.update', $car->id) }}" method="POST" class="edit-form" id="editForm" style="display: none;">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="description_modified" id="description_modified" value="0">
                    <div class="form-group">
                        <label for="make">Make</label>
                        <input type="text" name="make" id="make" class="form-control" value="{{ old('make', $car->make) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="model">Model</label>
                        <input type="text" name="model" id="model" class="form-control" value="{{ old('model', $car->model) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="year">Year</label>
                        <input type="text" name="year" id="year" class="form-control" value="{{ old('year', $car->year) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="price">Price (PKR)</label>
                        <input type="text" name="price" id="price" class="form-control" value="{{ old('price', $car->price) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="kilometers">Kilometers</label>
                        <input type="text" name="kilometers" id="kilometers" class="form-control" value="{{ old('kilometers', $car->kilometers) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="color">Color</label>
                        <input type="text" name="color" id="color" class="form-control" value="{{ old('color', $car->color) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="body_condition">Body Condition</label>
                        <select name="body_condition" id="body_condition" class="form-control" required>
                            <option value="Best" {{ old('body_condition', $car->body_condition) === 'Best' ? 'selected' : '' }}>Best</option>
                            <option value="Good" {{ old('body_condition', $car->body_condition) === 'Good' ? 'selected' : '' }}>Good</option>
                            <option value="Fair" {{ old('body_condition', $car->body_condition) === 'Fair' ? 'selected' : '' }}>Fair</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="mechanical_condition">Mechanical Condition</label>
                        <select name="mechanical_condition" id="mechanical_condition" class="form-control" required>
                            <option value="Best" {{ old('mechanical_condition', $car->mechanical_condition) === 'Best' ? 'selected' : '' }}>Best</option>
                            <option value="Good" {{ old('mechanical_condition', $car->mechanical_condition) === 'Good' ? 'selected' : '' }}>Good</option>
                            <option value="Fair" {{ old('mechanical_condition', $car->mechanical_condition) === 'Fair' ? 'selected' : '' }}>Fair</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="engine_size">Engine Size (L)</label>
                        <input type="text" name="engine_size" id="engine_size" class="form-control" value="{{ old('engine_size', $car->engine_size) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="horsepower">Horsepower</label>
                        <input type="text" name="horsepower" id="horsepower" class="form-control" value="{{ old('horsepower', $car->horsepower) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="top_speed">Top Speed (km/h)</label>
                        <input type="text" name="top_speed" id="top_speed" class="form-control" value="{{ old('top_speed', $car->top_speed) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="steering_side">Steering Side</label>
                        <select name="steering_side" id="steering_side" class="form-control" required>
                            <option value="left" {{ old('steering_side', $car->steering_side) === 'left' ? 'selected' : '' }}>Left</option>
                            <option value="right" {{ old('steering_side', $car->steering_side) === 'right' ? 'selected' : '' }}>Right</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="wheel_size">Wheel Size (inches)</label>
                        <input type="text" name="wheel_size" id="wheel_size" class="form-control" value="{{ old('wheel_size', $car->wheel_size) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="interior_color">Interior Color</label>
                        <input type="text" name="interior_color" id="interior_color" class="form-control" value="{{ old('interior_color', $car->interior_color) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="seats">Seats</label>
                        <input type="text" name="seats" id="seats" class="form-control" value="{{ old('seats', $car->seats) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="doors">Doors</label>
                        <input type="text" name="doors" id="doors" class="form-control" value="{{ old('doors', $car->doors) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" oninput="document.getElementById('description_modified').value = '1';">{{ old('description', $car->description) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="showroom_info">Showroom Info</label>
                        <textarea name="showroom_info" id="showroom_info" class="form-control">{{ old('showroom_info', $car->showroom_info) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="showroom_office">Office Contact</label>
                        <input type="text" name="showroom_office" id="showroom_office" class="form-control" value="{{ old('showroom_office', $car->showroom_office) }}">
                    </div>
                    <div class="form-group">
                        <label for="showroom_sales">Sales Contact</label>
                        <input type="text" name="showroom_sales" id="showroom_sales" class="form-control" value="{{ old('showroom_sales', $car->showroom_sales) }}">
                    </div>
                    <div class="form-group">
                        <label for="showroom_website">Website</label>
                        <input type="text" name="showroom_website" id="showroom_website" class="form-control" value="{{ old('showroom_website', $car->showroom_website) }}">
                    </div>
                    <div class="form-group">
                        <label for="showroom_location">Location (Map Link)</label>
                        <input type="text" name="showroom_location" id="showroom_location" class="form-control" value="{{ old('showroom_location', $car->showroom_location) }}">
                    </div>
                    <div class="form-group">
                        <label for="showroom_social_instagram">Instagram</label>
                        <input type="text" name="showroom_social_instagram" id="showroom_social_instagram" class="form-control" value="{{ old('showroom_social_instagram', $car->showroom_social_instagram) }}">
                    </div>
                    <div class="form-group">
                        <label for="showroom_social_facebook">Facebook</label>
                        <input type="text" name="showroom_social_facebook" id="showroom_social_facebook" class="form-control" value="{{ old('showroom_social_facebook', $car->showroom_social_facebook) }}">
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
</body>
</html>