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
        .car-info p strong { background: #d0e0f5; color: #000; padding: 4px 8px; border-radius: 4px; }
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
                <a href="{{ route('cars.index') }}" class="btn btn-primary mt-3">Back to Listings</a>
            </div>
        </div>
    </div>
</body>
</html>