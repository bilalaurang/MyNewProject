@extends('layouts.main')

@section('content')
    <div class="container">
        <h1>Car Listings</h1>
        <a href="{{ route('cars.create') }}" class="btn btn-success mb-3">Add New Car</a>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($cars->isEmpty())
            <p>No cars available.</p>
        @else
            <div class="row">
                @foreach ($cars as $car)
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $car->year }} {{ $car->make }} {{ $car->model }}</h5>
                                <p class="card-text">Price: ${{ $car->price }}</p>
                                <p class="card-text">Kilometers: {{ $car->kilometers }} km</p>
                                <a href="{{ route('cars.show', $car->id) }}" class="btn btn-primary">Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection