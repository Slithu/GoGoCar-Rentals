<!DOCTYPE html>
<html>
<head>
    <title>Cars Reviews Report</title>
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
    <h1>Cars Reviews Report</h1>
    @foreach ($reviews as $review)
        <div class="user-report">
            <h2>Review ID: {{$review->id}}</h2>
            <p><strong>User ID:</strong> {{$review->user->id }}</p>
            <p><strong>User Name:</strong> {{$review->user->name}}</p>
            <p><strong>User Surname:</strong> {{$review->user->surname}}</p>
            <p><strong>User Email:</strong> {{$review->user->email}}</p>
            <p><strong>Car ID:</strong> {{$review->car->id}}</p>
            <p><strong>Car Name:</strong> {{$review->car->brand}} {{$review->car->model}}</p>
            <p><strong>Comfort:</strong> {{$review->comfort_rating}}</p>
            <p><strong>Driving Experience:</strong> {{$review->driving_experience_rating}}</p>
            <p><strong>Fuel Efficiency:</strong> {{$review->fuel_efficiency_rating}}</p>
            <p><strong>Safety:</strong> {{$review->safety_rating}}</p>
            <p><strong>Overall:</strong> {{$review->overall_rating}}</p>
            <p><strong>Comment:</strong> {{$review->comment}}</p>
        </div>
    @endforeach
</body>
</html>
