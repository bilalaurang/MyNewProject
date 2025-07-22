<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Car Details</h1>
        <div class="card">
            <div class="card-header">
                {{ $car->make }} {{ $car->model }}
            </div>
            <div class="card-body">
                <p><strong>Make:</strong> {{ $car->make }}</p>
                <p><strong>Model:</strong> {{ $car->model }}</p>
                <p><strong>Year:</strong> {{ $car->year }}</p>
                <p><strong>Price:</strong> ${{ number_format($car->price, 2) }}</p>
                <p><strong>Kilometers:</strong> {{ number_format($car->kilometers) }}</p>
                <p><strong>Steering Side:</strong> {{ ucfirst($car->steering_side) }}</p>
                <p><strong>Wheel Size:</strong> {{ $car->wheel_size }}</p>
                <p><strong>Color:</strong> {{ $car->color }}</p>
                <p><strong>Body Condition:</strong> {{ $car->body_condition }}</p>
                <p><strong>Mechanical Condition:</strong> {{ $car->mechanical_condition }}</p>
                <p><strong>Interior Color:</strong> {{ $car->interior_color }}</p>
                <p><strong>Seats:</strong> {{ $car->seats }}</p>
                <p><strong>Doors:</strong> {{ $car->doors }}</p>
                <p><strong>Engine Size:</strong> {{ $car->engine_size }} L</p>
                <p><strong>Horsepower:</strong> {{ $car->horsepower }} hp</p>
                <p><strong>Export Option:</strong> {{ $car->export_option ? 'Yes' : 'No' }}</p>
                <p><strong>Additional Info:</strong> {{ $car->additional_info ?? 'N/A' }}</p>
                <p><strong>Description:</strong> {{ $car->description ?? 'N/A' }}</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('cars.index') }}" class="btn btn-primary">Back to Listings</a>
            </div>
        </div>
    </div>
</body>
</html>