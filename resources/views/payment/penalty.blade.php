@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">Penalty Payment</div>
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
                    <form action="{{ route('payment.processPenalty') }}" method="POST" id="payment-form-penalty">
                        @csrf
                        <input type="hidden" name="car_return_id" value="{{ $carReturn->id }}">
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <input type="hidden" name="penalty_amount" value="{{ $penalty_amount * 100 }}">
                        <input type="hidden" name="stripeToken" id="stripeToken-penalty">
                        <div class="form-group text-center">
                            <label>Penalty Amount:</label>
                            <p>{{ number_format($penalty_amount, 2) }} PLN</p>
                        </div>
                        <div class="form-group text-center">
                            <label for="card-element-penalty">Credit or debit card:</label>
                            <div id="card-element-penalty" class="form-control"></div>
                            <div id="card-errors-penalty" role="alert"></div>
                        </div><br>
                        <div class="col-md-6 offset-md-5">
                            <button id="pay-button-penalty" class="btn btn-primary">Pay</button>
                            <a href="{{ route('returns.index') }}" class="btn btn-secondary">Cancel</a>
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
            var cardElementPenalty = elements.create('card');
            cardElementPenalty.mount('#card-element-penalty');

            var formPenalty = document.getElementById('payment-form-penalty');
            var payButtonPenalty = document.getElementById('pay-button-penalty');

            payButtonPenalty.addEventListener('click', function(event) {
                event.preventDefault();
                stripe.createToken(cardElementPenalty).then(function(result) {
                    if (result.error) {
                        console.error(result.error.message);
                        var errorElement = document.getElementById('card-errors-penalty');
                        errorElement.textContent = result.error.message;
                    } else {
                        document.getElementById('stripeToken-penalty').value = result.token.id;
                        formPenalty.submit();
                    }
                });
            });
        } else {
            console.error('Stripe library not loaded.');
        }
    });
</script>
@endsection
