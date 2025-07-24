<!DOCTYPE html>
<html>
<head>
    <title>Car List</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Available Cars</h1>
    <a href="{{ route('car.form') }}">Add New Car</a>
    <table>
        <tr>
            <th>Make</th>
            <th>Model</th>
            <th>Year</th>
            <th>Action</th>
        </tr>
        @foreach ($cars as $car)
            <tr>
                <td>{{ $car['make'] }}</td>
                <td>{{ $car['model'] }}</td>
                <td>{{ $car['year'] }}</td>
                <td><a href="{{ route('car.details', $car['id']) }}">Detail</a></td>
            </tr>
        @endforeach
    </table>
</body>
</html>