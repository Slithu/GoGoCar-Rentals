<!DOCTYPE html>
<html>
<head>
    <title>Weekly Rentals Report</title>
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
    <h1>Weekly Rentals Report</h1>
    @foreach ($reservations as $reservation)
        <div class="user-report">
            <h2>Rental ID: {{$reservation->id}}</h2>
            <p><strong>User ID:</strong> {{$reservation->user->id}}</p>
            <p><strong>User Name:</strong> {{$reservation->user->name}}</p>
            <p><strong>User Surname:</strong> {{$reservation->user->surname}}</p>
            <p><strong>User Email:</strong> {{$reservation->user->email}}</p>
            <p><strong>Car ID:</strong> {{$reservation->car->id}}</p>
            <p><strong>Car Name:</strong> {{$reservation->car->brand}} {{$reservation->car->model}}</p>
            <p><strong>Rental Date:</strong> {{$reservation->start_date}}</p>
            <p><strong>Date of Return:</strong> {{$reservation->end_date}}</p>
            <p><strong>Total price:</strong> {{$reservation->total_price}} PLN</p>
            <p><strong>Status:</strong> {{$reservation->status}}</p>
        </div>
    @endforeach
</body>
</html>
