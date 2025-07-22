<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Listings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Car Listings</h1>
        <a href="{{ route('cars.create') }}" class="btn btn-primary mb-3">Add New Car</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Year</th>
                    <th>Price</th>
                    <th>Kilometers</th>
                    <th>Steering Side</th>
                    <th>Wheel Size</th>
                    <th>Color</th>
                    <th>Body Condition</th>
                    <th>Mechanical Condition</th>
                    <th>Interior Color</th>
                    <th>Seats</th>
                    <th>Doors</th>
                    <th>Engine Size</th>
                    <th>Horsepower</th>
                    <th>Export Option</th>
                    <th>Additional Info</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cars as $car)
                    <tr>
                        <td>{{ $car->make }}</td>
                        <td>{{ $car->model }}</td>
                        <td>{{ $car->year }}</td>
                        <td>${{ number_format($car->price, 2) }}</td>
                        <td>{{ number_format($car->kilometers) }}</td>
                        <td>{{ ucfirst($car->steering_side) }}</td>
                        <td>{{ $car->wheel_size }}</td>
                        <td>{{ $car->color }}</td>
                        <td>{{ $car->body_condition }}</td>
                        <td>{{ $car->mechanical_condition }}</td>
                        <td>{{ $car->interior_color }}</td>
                        <td>{{ $car->seats }}</td>
                        <td>{{ $car->doors }}</td>
                        <td>{{ $car->engine_size }} L</td>
                        <td>{{ $car->horsepower }} hp</td>
                        <td>{{ $car->export_option ? 'Yes' : 'No' }}</td>
                        <td>{{ $car->additional_info ?? 'N/A' }}</td>
                        <td>{{ $car->description ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>