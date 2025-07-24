<!DOCTYPE html>
<html>
<head>
    <title>Add New Car</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Add New Car</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('cars.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="make" class="form-label">Make</label>
                <input type="text" name="make" id="make" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="model" class="form-label">Model</label>
                <input type="text" name="model" id="model" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="additional_info" class="form-label">Additional Info</label>
                <textarea name="additional_info" id="additional_info" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label for="export_option" class="form-label">Export Option</label>
                <select name="export_option" id="export_option" class="form-control" required>
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="year" class="form-label">Year</label>
                <input type="number" name="year" id="year" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="kilometers" class="form-label">Kilometers</label>
                <input type="number" name="kilometers" id="kilometers" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="steering_side" class="form-label">Steering Side</label>
                <input type="text" name="steering_side" id="steering_side" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="wheel_size" class="form-label">Wheel Size</label>
                <input type="text" name="wheel_size" id="wheel_size" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="color" class="form-label">Color</label>
                <input type="text" name="color" id="color" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="body_condition" class="form-label">Body Condition</label>
                <input type="text" name="body_condition" id="body_condition" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="mechanical_condition" class="form-label">Mechanical Condition</label>
                <input type="text" name="mechanical_condition" id="mechanical_condition" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="interior_color" class="form-label">Interior Color</label>
                <input type="text" name="interior_color" id="interior_color" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="seats" class="form-label">Seats</label>
                <input type="number" name="seats" id="seats" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="doors" class="form-label">Doors</label>
                <input type="number" name="doors" id="doors" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="engine_size" class="form-label">Engine Size (L)</label>
                <input type="number" step="0.1" name="engine_size" id="engine_size" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="horsepower" class="form-label">Horsepower</label>
                <input type="number" name="horsepower" id="horsepower" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
</html>