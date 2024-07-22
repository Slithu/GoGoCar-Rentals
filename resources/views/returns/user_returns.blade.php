@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-0">Your Car Returns</h5>
                </div>

                <div class="card-body">
                    @if ($carReturns->isEmpty())
                        <p class="text-center">No car returns found.</p>
                    @else
                        <div class="list-group">
                            @foreach($carReturns as $carReturn)
                                <div class="list-group-item text-center">
                                    <p><h5><strong>{{ $carReturn->user->name }} {{ $carReturn->user->surname }}</strong></h5></p>
                                    <p><strong>Car:</strong> {{ $carReturn->reservation->car->brand }} {{ $carReturn->reservation->car->model }}</p>
                                    <p><strong>Rental Date:</strong> {{ $carReturn->reservation->start_date }} --- {{ $carReturn->reservation->end_date }}</p>
                                    <p><strong>Rental Price:</strong> {{ $carReturn->reservation->total_price }} PLN</p>
                                    <p><strong>Return Date:</strong> {{ $carReturn->return_date }}</p>
                                    <p><strong>Exterior Condition:</strong> {{ $carReturn->exterior_condition }}</p>
                                    <p><strong>Interior Condition:</strong> {{ $carReturn->interior_condition }}</p>
                                    <p><strong>Exterior Damage Description:</strong> {{ $carReturn->exterior_damage_description ?? 'None' }}</p>
                                    <p><strong>Interior Damage Description:</strong> {{ $carReturn->interior_condition_description ?? 'None' }}</p>
                                    <p><strong>Car Parts Condition:</strong> {{ $carReturn->car_parts_condition ?? 'None' }}</p>
                                    <p><strong>Penalty Amount:</strong> {{ $carReturn->penalty_amount ?? 'None' }} PLN</p>
                                    <p><strong>Comments:</strong> {{ $carReturn->comments ?? 'None' }}</p>

                                    @if ($carReturn->penalty_amount > 0)
                                        @if ($carReturn->penalty_paid)
                                            <p class="text-success"><strong>Penalty has already been paid.</strong></p>
                                        @else
                                            <a href="{{ route('payment.penalty', ['carReturn' => $carReturn]) }}" class="btn btn-primary">Pay Penalty</a>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            <div class="mt-3">
                {{ $carReturns->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
