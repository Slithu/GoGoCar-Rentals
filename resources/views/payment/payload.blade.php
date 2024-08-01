@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center bg-primary text-white">Charge Details</div>
                <div class="card-body">
                    @can('isAdmin')
                        <p><strong>Charge ID:</strong> {{ $charge->id }}</p>
                    @endcan
                    <p><strong>Amount:</strong> {{ $charge->amount / 100 }} {{ strtoupper($charge->currency) }}</p>
                    <p><strong>Description:</strong> {{ $charge->description }}</p>
                    <p><strong>Status:</strong> {{ $charge->status }}</p>
                    @php
                        use Carbon\Carbon;
                        $createdAt = Carbon::createFromTimestamp($charge->created);
                        $createdAtPlusTwoHours = $createdAt->addHour(2);
                    @endphp
                    <p><strong>Created:</strong> {{ $createdAtPlusTwoHours->format('Y-m-d H:i:s') }}</p>

                    @can('isAdmin')
                        <p><strong>Payment Method:</strong> {{ $charge->payment_method }}</p>
                    @endcan
                    <p><strong>Receipt URL:</strong> <a href="{{ $charge->receipt_url }}" target="_blank">{{ $charge->receipt_url }}</a></p>

                    <div class="d-flex justify-content-center mt-4">
                        @can('isAdmin')
                            <a href="{{ route('payment.index') }}" class="btn btn-secondary mx-2">Back</a>
                        @endcan
                        @can('isUser')
                            <a href="{{ route('payment.user') }}" class="btn btn-secondary mx-2">Back</a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
