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
        <div class="col-10">
            <h1>Rentals List</h1>
        </div>
    </div>
    <br>
    <div class="row">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                <th scope="col">ID</th>
                <th scope="col">User ID</th>
                <th scope="col">User Name</th>
                <th scope="col">User Surname</th>
                <th scope="col">User Email</th>
                <th scope="col">Car ID</th>
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
                        <th scope="row">{{$reservation->id}}</th>
                        <td>{{$reservation->user->id ?? 'None' }}</td>
                        <td>{{$reservation->user->name ?? 'None' }}</td>
                        <td>{{$reservation->user->surname ?? 'None' }}</td>
                        <td>{{$reservation->user->email ?? 'None' }}</td>
                        <td>{{$reservation->car->id ?? 'None' }}</td>
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
                                    <button class="btn btn-warning btn-sm">Edit</button>
                                </a>
                                <a href="{{ route('reservations.destroy', $reservation->id) }}">
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="position-fixed bottom-0 start-50 translate-middle-x">
        {{$reservations->links()}}
    </div>
</div>
@endsection
