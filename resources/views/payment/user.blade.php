@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white text-center"><h5 class="mb-0">Your Payments</h5></div>

                    <div class="card-body">
                        @if ($userPayments->isEmpty())
                            <p class="text-center">No payments found.</p>
                        @else
                        <div class="list-group">
                            @foreach($userPayments as $userPayment)
                                <div class="list-group-item text-center">
                                    <p><h5><strong>{{ $userPayment->user->name }} {{ $userPayment->user->surname }}</strong></h5></p>
                                    <p><strong>Car:</strong> {{ $userPayment->reservation->car->brand }} {{ $userPayment->reservation->car->model }}</p>
                                    <p><strong>Rental Date:</strong> {{ $userPayment->reservation->start_date }} --- {{ $userPayment->reservation->end_date }}</p>
                                    <p><strong>Amount:</strong> {{ $userPayment->amount }}</p>
                                    <p><strong>Currency:</strong> {{$userPayment->currency }} </p>
                                    <p><strong>Type:</strong> {{$userPayment->type }} </p>
                                    <p><strong>Date:</strong> {{$userPayment->created_at }} </p>
                                    @if ($userPayment->type == 'rental')
                                        <p><strong>Status:</strong> {{$userPayment->reservation->status }} </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
                <div class="mt-3">
                    {{ $userPayments->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
