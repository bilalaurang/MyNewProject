<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Listings</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container { margin-top: 20px; }
        .form-group { margin-bottom: 15px; }
        .btn-primary { background-color: #007bff; border-color: #007bff; }
        .card { box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        button[type="submit"], #submitButton { display: block !important; width: 100px; visibility: visible !important; }
    </style>
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            console.log('Page loaded, ensuring submit button is visible');
            $('button[type="submit"]').show();
        });
    </script>
</body>
</html>