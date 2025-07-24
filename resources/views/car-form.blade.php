<!DOCTYPE html>
<html>
<head>
    <title>Add Car</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .container { max-width: 800px; margin: 25px auto; }
        .card { border: 1px solid #e0e0e0; border-radius: 10px; background: #fff; }
        .card-header { background: #003087; padding: 15px; border-bottom: 1px solid #e0e0e0; border-radius: 10px 10px 0 0; }
        .card-header h3 { margin: 0; font-size: 1.7rem; color: #fff; font-weight: 700; text-transform: uppercase; }
        .card-body { padding: 25px; background: #fafafa; }
        .form-group { margin-bottom: 18px; }
        .form-group label { font-weight: 600; color: #000; font-size: 1.1rem; background: #d0e0f5; padding: 8px 12px; border-radius: 4px; display: block; width: fit-content; }
        .form-control { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 1.1rem; background: #f0f0f0; }
        .btn-primary { background: #007bff; color: #fff; padding: 12px 25px; border: none; border-radius: 6px; font-size: 1.1rem; transition: background 0.3s; }
        .btn-primary:hover { background: #0056b3; }
        .alert-success { background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 18px; }
        .section-title { font-size: 1.2rem; color: #003087; margin: 25px 0 12px; font-weight: 600; }
        textarea { resize: vertical; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>Add a New Car</h3>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('cars.store') }}" method="POST">
                    @csrf
                    <div class="section-title">Car Details</div>
                    <div class="form-group">
                        <label for="make">Make:</label>
                        <input type="text" name="make" id="make" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="model">Model:</label>
                        <input type="text" name="model" id="model" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="year">Year:</label>
                        <input type="number" name="year" id="year" class="form-control" required min="1900" max="{{ date('Y') + 1 }}">
                    </div>
                    <div class="form-group">
                        <label for="price">Price (PKR):</label>
                        <input type="number" name="price" id="price" class="form-control" required min="0" step="0.01">
                    </div>
                    <div class="form-group">
                        <label for="kilometers">Kilometers:</label>
                        <input type="number" name="kilometers" id="kilometers" class="form-control" required min="0">
                    </div>
                    <div class="form-group">
                        <label for="color">Color:</label>
                        <input type="text" name="color" id="color" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="body_condition">Body Condition:</label>
                        <select name="body_condition" id="body_condition" class="form-control" required>
                            <option value="Best">Best</option>
                            <option value="Good">Good</option>
                            <option value="Fair">Fair</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="mechanical_condition">Mechanical Condition:</label>
                        <select name="mechanical_condition" id="mechanical_condition" class="form-control" required>
                            <option value="Best">Best</option>
                            <option value="Good">Good</option>
                            <option value="Fair">Fair</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="engine_size">Engine Size (L):</label>
                        <input type="number" step="0.1" name="engine_size" id="engine_size" class="form-control" required min="0">
                    </div>
                    <div class="form-group">
                        <label for="horsepower">Horsepower:</label>
                        <input type="number" name="horsepower" id="horsepower" class="form-control" required min="0">
                    </div>
                    <div class="form-group">
                        <label for="top_speed">Top Speed (km/h):</label>
                        <input type="number" name="top_speed" id="top_speed" class="form-control" required min="0">
                    </div>
                    <div class="form-group">
                        <label for="steering_side">Steering Side:</label>
                        <select name="steering_side" id="steering_side" class="form-control" required>
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="wheel_size">Wheel Size (inches):</label>
                        <input type="text" name="wheel_size" id="wheel_size" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="interior_color">Interior Color:</label>
                        <input type="text" name="interior_color" id="interior_color" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="seats">Seats:</label>
                        <input type="number" name="seats" id="seats" class="form-control" required min="1">
                    </div>
                    <div class="form-group">
                        <label for="doors">Doors:</label>
                        <input type="number" name="doors" id="doors" class="form-control" required min="1">
                    </div>
                    <div class="section-title">Showroom Details (Optional)</div>
                    <div class="form-group">
                        <label for="showroom_info">Showroom/Company Info:</label>
                        <textarea name="showroom_info" id="showroom_info" class="form-control" rows="4" placeholder="e.g., Bilal Cars, 50 years experience, luxury vehicles"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="showroom_office">Office Contact:</label>
                        <input type="text" name="showroom_office" id="showroom_office" class="form-control" placeholder="e.g., +92 123 456 7890">
                    </div>
                    <div class="form-group">
                        <label for="showroom_sales">Sales Contact:</label>
                        <input type="text" name="showroom_sales" id="showroom_sales" class="form-control" placeholder="e.g., +92 987 654 3210">
                    </div>
                    <div class="form-group">
                        <label for="showroom_website">Website:</label>
                        <input type="url" name="showroom_website" id="showroom_website" class="form-control" placeholder="e.g., www.bilalcars.pk">
                    </div>
                    <div class="form-group">
                        <label for="showroom_location">Location (Map Link):</label>
                        <input type="url" name="showroom_location" id="showroom_location" class="form-control" placeholder="e.g., https://goo.gl/maps/IslamabadI10">
                    </div>
                    <div class="form-group">
                        <label for="showroom_social_instagram">Instagram:</label>
                        <input type="text" name="showroom_social_instagram" id="showroom_social_instagram" class="form-control" placeholder="e.g., instagram.com/bilal_cars">
                    </div>
                    <div class="form-group">
                        <label for="showroom_social_facebook">Facebook:</label>
                        <input type="text" name="showroom_social_facebook" id="showroom_social_facebook" class="form-control" placeholder="e.g., facebook.com/bilal_cars">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Car</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>