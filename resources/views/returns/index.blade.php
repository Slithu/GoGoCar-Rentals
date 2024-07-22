@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h1>Returns List</h1>
            @if($car_returns->isEmpty())
                <br>
                <p>No returns found.</p>
            @endif
        </div>
    </div>
    <br>
    <div class="row">
        @foreach ($car_returns as $car_return)
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <strong>Return ID: {{$car_return->id}}</strong>
                    </div>
                    <div class="card-body">
                        <p><strong>Rental ID:</strong> {{$car_return->reservation->id ?? 'None' }}</p>
                        <p><strong>Car ID:</strong> {{$car_return->reservation->car->id ?? 'None' }}</p>
                        <p><strong>Car:</strong> {{$car_return->reservation->car->brand ?? 'None' }} {{$car_return->reservation->car->model ?? 'None' }}</p>
                        <p><strong>User ID:</strong> {{$car_return->reservation->user->id ?? 'None' }}</p>
                        <p><strong>User:</strong> {{$car_return->reservation->user->name ?? 'None' }} {{$car_return->reservation->user->surname ?? 'None' }}</p>
                        <p><strong>Rental Date:</strong> {{$car_return->reservation->start_date ?? 'None' }} --- {{$car_return->reservation->end_date ?? 'None' }}</p>
                        <p><strong>Rental Price:</strong> {{$car_return->reservation->total_price ?? 'None' }} PLN</p>
                        <p><strong>Return Date:</strong> {{$car_return->return_date ?? 'None' }}</p>
                        <p><strong>Exterior Condition:</strong> {{$car_return->exterior_condition ?? 'None'}}</p>
                        <p><strong>Interior Condition:</strong> {{$car_return->interior_condition ?? 'None'}}</p>
                        <p><strong>Exterior Damage Description:</strong> {{$car_return->exterior_damage_description ?? 'None'}}</p>
                        <p><strong>Interior Damage Description:</strong> {{$car_return->interior_condition_description ?? 'None'}}</p>
                        <p><strong>Car Parts Condition:</strong> {{$car_return->car_parts_condition ?? 'None'}}</p>
                        <p><strong>Penalty Amount:</strong> {{$car_return->penalty_amount ?? 'None'}} PLN</p>
                        <p><strong>Comments:</strong> {{$car_return->comments ?? 'None'}}</p>
                        @if ($car_return->penalty_amount > 0)
                            @if ($car_return->penalty_paid)
                                <p class="text-success"><strong>Penalty has already been paid.</strong></p>
                            @endif
                        @endif
                    </div>
                    <div class="col-md-6 offset-md-5">
                        <a href="{{ route('returns.edit', $car_return->id) }}">
                            <button class="btn btn-warning btn-sm">Edit</button>
                        </a>
                        <a href="{{ route('returns.destroy', $car_return->id) }}">
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </a>
                    </div><br>
                </div>
            </div>
        @endforeach
    </div>
    <div class="position-fixed bottom-0 start-50 translate-middle-x">
        {{$car_returns->links()}}
    </div>
</div>
@endsection
