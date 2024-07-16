<!DOCTYPE html>
<html>
<head>
    <title>Users Report</title>
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
    <h1>Users Report</h1>
    @foreach ($users as $user)
        <div class="user-report">
            <h2>User ID: {{$user->id}}</h2>
            <p><strong>Name:</strong> {{$user->name}}</p>
            <p><strong>Surname:</strong> {{$user->surname}}</p>
            <p><strong>Sex:</strong> {{$user->sex}}</p>
            <p><strong>Email:</strong> {{$user->email}}</p>
            <p><strong>Phone Number:</strong> {{$user->phone}}</p>
            <p><strong>Driver's License Number:</strong> {{$user->license}}</p>
            <p><strong>Date of Birth:</strong> {{$user->birth}}</p>
            <p><strong>Town:</strong> {{$user->town}}</p>
            <p><strong>Zip Code:</strong> {{$user->zip_code}}</p>
            <p><strong>Country:</strong> {{$user->country}}</p>
        </div>
    @endforeach
</body>
</html>
