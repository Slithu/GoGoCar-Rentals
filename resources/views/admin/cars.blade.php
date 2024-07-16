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
        <div class="col-10">
            <h1>Cars Availability</h1>
        </div>
    </div><br><br>
    <div class="row">
        @foreach ($cars as $car)
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title">Car ID: {{ $car->id }}</h5>
                        <h5 class="card-title"><strong>{{ $car->brand }} {{ $car->model }}</strong></h5>
                        <p class="card-text">
                            <strong>Availability:</strong>
                            @if ($car->availability->available)
                                <span class="text-success">Available</span>
                            @else
                                <span class="text-danger">Not Available</span>
                                <br>
                                <strong>Rentals:</strong>
                                <br>
                                @if ($car->reservations)
                                    @foreach ($car->reservations as $reservation)
                                        {{ $reservation->start_date }} - {{ $reservation->end_date }}<br>
                                    @endforeach
                                @else
                                    No reservations found.
                                @endif
                            @endif
                        </p>
                        <div class="row">
                            <div class="col">
                                <p class="card-text"><strong>Car body:</strong> {{ $car->car_body }}</p>
                            </div>
                            <div class="col">
                                <p class="card-text"><strong>Engine type:</strong> {{ $car->engine_type }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <p class="card-text"><strong>Transmission:</strong> {{ $car->transmission }}</p>
                            </div>
                            <div class="col">
                                <p class="card-text"><strong>Engine power:</strong> {{ $car->engine_power }} HP</p>
                            </div>
                        </div>
                        <p class="card-text text-center"><strong>Price:</strong> {{ $car->price }} PLN</p>
                    </div>
                    @can('isAdmin')
                        <div class="card-footer">
                            <a href="{{ route('cars.show', $car->id) }}" class="btn btn-success btn-sm">Show</a>
                            <a href="{{ route('cars.edit', $car->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <a href="{{ route('cars.destroy', $car->id) }}" class="btn btn-danger btn-sm">Delete</a>
                        </div>
                    @endcan
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
