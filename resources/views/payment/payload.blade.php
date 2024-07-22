@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">Charge Details</div>
                <div class="card-body">
                    <p><strong>Charge ID:</strong> {{ $charge->id }}</p>
                    <p><strong>Amount:</strong> {{ $charge->amount / 100 }} {{ strtoupper($charge->currency) }}</p>
                    <p><strong>Description:</strong> {{ $charge->description }}</p>
                    <p><strong>Status:</strong> {{ $charge->status }}</p>
                    <p><strong>Created:</strong> {{ date('Y-m-d H:i:s', $charge->created) }}</p>
                    <p><strong>Payment Method:</strong> {{ $charge->payment_method }}</p>
                    <p><strong>Receipt URL:</strong> <a href="{{ $charge->receipt_url }}" target="_blank">{{ $charge->receipt_url }}</a></p>
                    <div class="col-md-6 offset-md-5">
                        <a href="{{ route('payment.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
