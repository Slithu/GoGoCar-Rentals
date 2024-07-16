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
    @if ($reservations->isEmpty())
    <h1>Your Rentals</h1><br><br>
        <h4 style="text-align: center">You don't have any rentals yet. If you want to rent a car, go to the Main Tab</h4>
    @else
    <h1>Your Rentals</h1>
    <br>
    <h6 style="text-align: center">You can edit your rentals by clicking the edit button to cancel the car rental, click the edit button and change the status to "Cancelled"</h6>
    <br><br>
    <div class="row">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                <th scope="col">Name</th>
                <th scope="col">Surname</th>
                <th scope="col">Email</th>
                <th scope="col">Car</th>
                <th scope="col">Rental Date</th>
                <th scope="col">Date of Return</th>
                <th scope="col">Total price (PLN)</th>
                <th scope="col">Status</th>
                <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservations as $reservation)
                    <tr>
                        <td>{{$reservation->user->name ?? 'None' }}</td>
                        <td>{{$reservation->user->surname ?? 'None' }}</td>
                        <td>{{$reservation->user->email ?? 'None' }}</td>
                        <td>{{$reservation->car->brand ?? 'None' }} {{$reservation->car->model ?? 'None' }}</td>
                        <td>{{$reservation->start_date ?? 'None' }}</td>
                        <td>{{$reservation->end_date ?? 'None' }}</td>
                        <td>{{$reservation->total_price}}</td>
                        <td>{{$reservation->status}}</td>
                        <td>
                            <a href="{{ route('reservations.show', $reservation->id) }}">
                                <button class="btn btn-success btn-sm">Show</button>
                            </a>
                            <a href="{{ route('reservations.edit', $reservation->id) }}">
                                <button class="btn btn-info btn-sm">Edit</button>
                            </a>
                            @if ($reservation->end_date < now()->addHours(1))
                                <a href="{{ route('reservations.rate', $reservation->id) }}">
                                    <button class="btn btn-primary btn-sm">Rate</button>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    <div class="position-absolute bottom-0 start-50 translate-middle-x">
        {{$reservations->links()}}
    </div>
</div>
@endsection
