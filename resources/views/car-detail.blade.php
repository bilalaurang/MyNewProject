@extends('layouts.main')

@section('content')
    <div class="container">
        <h1>{{ $car->year }} {{ $car->make }} {{ $car->model }}</h1>
        <div class="card">
            <div class="card-body">
                <p><strong>Price:</strong> ${{ $car->price }}</p>
                <p><strong>Kilometers:</strong> {{ $car->kilometers }} km</p>
                <p><strong>Color:</strong> {{ $car->color }}</p>
                <p><strong>Body Condition:</strong> {{ $car->body_condition }}</p>
                <p><strong>Mechanical Condition:</strong> {{ $car->mechanical_condition }}</p>
                <p><strong>Engine Size:</strong> {{ $car->engine_size }}L</p>
                <p><strong>Horsepower:</strong> {{ $car->horsepower }} hp</p>
                <p><strong>Top Speed:</strong> {{ $car->top_speed }} km/h</p>
                <p><strong>Steering Side:</strong> {{ $car->steering_side ?? 'Not specified' }}</p>
                <p><strong>Wheel Size:</strong> {{ $car->wheel_size ?? 'Not specified' }} inches</p>
                <p><strong>Interior Color:</strong> {{ $car->interior_color ?? 'Not specified' }}</p>
                <p><strong>Seats:</strong> {{ $car->seats ?? 'Not specified' }}</p>
                <p><strong>Doors:</strong> {{ $car->doors ?? 'Not specified' }}</p>
                <p><strong>Description:</strong> {{ $car->description ?? 'No description available.' }}</p>
                <a href="{{ route('cars.index') }}" class="btn btn-secondary">Back to Listings</a>
            </div>
        </div>
    </div>
@endsection