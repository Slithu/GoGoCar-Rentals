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
                        <p style="text-align: center"><strong>You can cancel your car rental by clicking the cancel button below</strong>
                            <div class="col-7 text-end">
                                <a href="{{ route('reservations.calendar') }}">
                                    <button class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar3-week" viewBox="0 0 16 16" style="margin-bottom: 3px">
                                            <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2M1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857z"/>
                                            <path d="M12 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-5 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m2-3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-5 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                                        </svg>
                                        See Calendar
                                    </button>
                                </a>
                            </div>
                        </p>
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
                                    @if ($reservation->status === 'pending' && $reservation->start_date > now()->addHours(2))
                                        <a href="{{ route('payment.payment', $reservation->id) }}">
                                            <button class="btn btn-warning">Pay {{ $reservation->total_price }} PLN</button>
                                        </a>
                                    @endif
                                    @if ($reservation->end_date > now()->addHours(2) && $reservation->status !== 'cancelled')
                                        <form action="{{ route('reservations.cancel', $reservation->id) }}" method="POST" class="d-inline" id="cancel-form-{{ $reservation->id }}">
                                            @csrf
                                            <button type="button" class="btn btn-danger" onclick="confirmCancel({{ $reservation->id }})">Cancel Rental</button>
                                        </form>
                                    @endif
                                    @if ($reservation->end_date < now()->addHours(2) && !$reservation->review && $reservation->status == 'confirmed')
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
            <div class="position-fixed bottom-0 start-50 translate-middle-x">
                {{ $reservations->links() }}
            </div>
        </div>
    @endif
</div>
</div>
</div>

<div id="customModal" class="custom-modal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 class="custom-modal-title">Confirm Cancellation</h5>
            <span class="custom-modal-close" onclick="closeModal()">&times;</span>
        </div>
        <div class="custom-modal-body">
            <p>Are you sure you want to cancel this rental?</p>
            <span><strong>Car:</strong> {{ $reservation->car->brand ?? 'None' }} {{$reservation->car->model ?? 'None' }} </span><br>
            <span><strong>Start Date:</strong> {{ $reservation->start_date ?? 'None' }} </span><br>
            <span><strong>End Date:</strong> {{ $reservation->end_date ?? 'None' }} </span><br>
            <span><strong>Total Price:</strong> {{ $reservation->total_price ?? 'None' }} PLN</span>
        </div>
        <div class="custom-modal-footer gap-3">
            <button type="button" class="btn btn-danger" id="confirmCancelBtn">Cancel Rental</button>
            <button type="button" class="btn btn-secondary" onclick="closeModal()">Go Back</button>
        </div>
    </div>
</div>

<style>
    .custom-modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.4);
    }

    .custom-modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 40%;
        max-width: 500px;
        border-radius: 8px;
    }

    .custom-modal-header, .custom-modal-footer {
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .custom-modal-title {
        margin: 0;
        font-size: 20px;
        font-weight: bold;
    }

    .custom-modal-close {
        color: #aaa;
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .custom-modal-close:hover,
    .custom-modal-close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .custom-modal-body {
        text-align: center;
        margin: 20px 0;
    }
</style>

<script>
    let cancelFormId = null;

    function confirmCancel(reservationId) {
        cancelFormId = 'cancel-form-' + reservationId;
        document.getElementById('customModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('customModal').style.display = 'none';
    }

    document.getElementById('confirmCancelBtn').addEventListener('click', function() {
        if (cancelFormId) {
            document.getElementById(cancelFormId).submit();
        }
        closeModal();
    });
</script>

@endsection
