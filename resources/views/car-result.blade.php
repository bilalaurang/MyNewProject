@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="search-filter mb-3">
            <div class="row">
                <div class="col-md-6">
                    <input type="text" id="search" class="form-control mb-2" placeholder="Search by make, model, or year..." onkeyup="filterCars()">
                </div>
                <div class="col-md-6">
                    <select id="filterYear" class="form-control mb-2" onchange="filterCars()">
                        <option value="">All Years</option>
                        @foreach (range(date('Y') + 1, 1900) as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <h1>Car Listings</h1>
        <a href="{{ route('cars.create') }}" class="btn btn-success mb-3">Add New Car</a>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($cars->isEmpty())
            <p>No cars available.</p>
        @else
            <div class="row" id="carGrid">
                @foreach ($cars as $car)
                    <div class="col-md-4 mb-3" data-search="{{ $car->make }} {{ $car->model }} {{ $car->year }}" data-year="{{ $car->year }}">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $car->year }} {{ $car->make }} {{ $car->model }}</h5>
                                <p class="card-text">Price: {{ $car->price }}</p>
                                <p class="card-text">Kilometers: {{ $car->kilometers }} km</p>
                                <a href="{{ route('cars.show', $car->id) }}" class="btn btn-primary">Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        function filterCars() {
            let searchInput = document.getElementById('search').value.toLowerCase();
            let yearFilter = document.getElementById('filterYear').value;
            let cards = document.getElementsByClassName('col-md-4');

            for (let i = 0; i < cards.length; i++) {
                let searchText = cards[i].getAttribute('data-search').toLowerCase();
                let year = cards[i].getAttribute('data-year');
                let show = true;

                if (searchInput && !searchText.includes(searchInput)) show = false;
                if (yearFilter && year !== yearFilter) show = false;

                cards[i].style.display = show ? 'block' : 'none';
            }
        }
    </script>
@endsection