@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center bg-primary text-white">Payment Details</div>
                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('payment.process') }}" method="POST" id="payment-form">
                        @csrf
                        <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <input type="hidden" name="total_price" value="{{ $total_price * 100 }}">
                        <input type="hidden" name="stripeToken" id="stripeToken">
                        <div class="form-group text-center">
                            <label>Total Price:</label>
                            <p>{{ number_format($total_price, 2) }} PLN</p>
                        </div>
                        <div class="form-group text-center">
                            <label for="card-element">Credit or debit card:</label>
                            <div id="card-element" class="form-control"></div>
                        </div><br>
                        <div class="col-md-6 offset-md-5">
                            <button id="pay-button" class="btn btn-primary">Pay</button>
                            <a href="{{ route('reservations.session') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.Stripe) {
            var stripe = Stripe('{{ config('stripe.STRIPE_KEY') }}');
            var elements = stripe.elements();
            var card = elements.create('card');
            card.mount('#card-element');

            var form = document.getElementById('payment-form');
            var payButton = document.getElementById('pay-button');

            payButton.addEventListener('click', function(event) {
                event.preventDefault();

                stripe.createToken(card).then(function(result) {
                    if (result.error) {
                        console.error(result.error.message);
                    } else {
                        document.getElementById('stripeToken').value = result.token.id;
                        form.submit();
                    }
                });
            });
        } else {
            console.error('Stripe library not loaded.');
        }
    });
</script>
@endsection
