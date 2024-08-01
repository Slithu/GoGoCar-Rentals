@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white text-center"><p class="mb-0">Your Rentals</p></div>

                    <div class="card-body">
                        @if ($reservations->isEmpty())
                            <p class="text-center">You don't have any rentals yet. If you want to rent a car, go to the Main Tab</p>
                        @else
                        <div class="list-group">
                            <h6 style="text-align: center"><strong>You can edit your rentals by clicking the edit button to cancel the car rental, click the edit button and change the status to "Cancelled"</strong></h6>
                            @foreach($reservations as $reservation)
                                <div class="list-group-item text-center">
                                    <p><h5><strong> {{ $reservation->user->name ?? 'None' }} {{ $reservation->user->surname ?? 'None' }}</strong></h5></p>
                                    <p><strong>Car:</strong> {{ $reservation->car->brand ?? 'None' }} {{$reservation->car->model ?? 'None' }} </p>
                                    <p><strong>Start Date:</strong> {{ $reservation->start_date ?? 'None' }} </p>
                                    <p><strong>End Date:</strong> {{ $reservation->end_date ?? 'None' }} </p>
                                    <p><strong>Total Price:</strong> {{ $reservation->total_price }} PLN</p>
                                    <p><strong>Status:</strong> {{ $reservation->status }} </p>
                                    <div class="text-center">
                                        <a href="{{ route('reservations.show', $reservation->id) }}">
                                            <button class="btn btn-success">Show</button>
                                        </a>
                                        <a href="{{ route('reservations.edit', $reservation->id) }}">
                                            <button class="btn btn-info">Edit</button>
                                        </a>
                                        @if ($reservation->end_date < now()->addHours(1) && !$reservation->review)
                                            <a href="{{ route('reservations.rate', $reservation->id) }}">
                                                <button class="btn btn-primary">Rate</button>
                                            </a>
                                        @else
                                            @if ($reservation->review)
                                                <p class="text-success"><br><strong>Car already rated</strong></p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
                <div class="position-fixed bottom-0 start-50 translate-middle-x">
                    {{ $reservations->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
