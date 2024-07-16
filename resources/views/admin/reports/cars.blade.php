<!DOCTYPE html>
<html>
<head>
    <title>Cars Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }
        .user-report {
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 10px;
        }
        .user-report h2 {
            margin: 0 0 10px 0;
        }
        .user-report p {
            margin: 0 0 5px 0;
        }
        .report-info {
            float: right;
        }
    </style>
</head>
<body>
    <div class="report-info">
        <p><strong>Date & Time:</strong> {{ \Carbon\Carbon::now()->addHours(2)->format('Y-m-d H:i:s') }}</p>
    </div>
    <h1>Cars Report</h1>
    @foreach ($cars as $car)
        <div class="user-report">
            <h2>Car ID: {{$car->id}}</h2>
            <p><strong>Brand:</strong> {{$car->brand}}</p>
            <p><strong>Model:</strong> {{$car->model}}</p>
            <p><strong>Car Body:</strong> {{$car->car_body}}</p>
            <p><strong>Engine Type:</strong> {{$car->engine_type}}</p>
            <p><strong>Transmission:</strong> {{$car->transmission}}</p>
            <p><strong>Engine Power:</strong> {{$car->engine_power}}</p>
            <p><strong>Seats:</strong> {{$car->seats}}</p>
            <p><strong>Doors:</strong> {{$car->doors}}</p>
            <p><strong>Suitcases:</strong> {{$car->suitcases}}</p>
            <p><strong>Price:</strong> {{$car->price}} PLN</p>
            <p><strong>Description:</strong> {{$car->description}}</p>
        </div>
    @endforeach
</body>
</html>
