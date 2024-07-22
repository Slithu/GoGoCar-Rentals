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
            <h1>Payments</h1>
        </div>
    </div>
    <br>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
            <th scope="col">ID</th>
            <th scope="col">Rental ID</th>
            <th scope="col">User ID</th>
            <th scope="col">User</th>
            <th scope="col">Car ID</th>
            <th scope="col">Car</th>
            <th scope="col">Stripe Charge ID</th>
            <th scope="col">Amount</th>
            <th scope="col">Currency</th>
            <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
                <tr>
                    <th scope="row">{{$payment->id}}</th>
                    <td>{{$payment->reservation->id}}</td>
                    <td>{{$payment->reservation->user_id}}</td>
                    <td>{{$payment->reservation->user->name}} {{$payment->reservation->user->surname}}</td>
                    <td>{{$payment->reservation->car_id}}</td>
                    <td>{{$payment->reservation->car->brand}} {{$payment->reservation->car->model}}</td>
                    <td>{{$payment->stripe_charge_id}}</td>
                    <td>{{$payment->amount}}</td>
                    <td>{{$payment->currency}}</td>
                    <td>
                        <a href="{{ route('payment.payload', $payment->stripe_charge_id) }}">
                            <button class="btn btn-info btn-sm">See Details</button>
                        </a>
                        <a href="{{ route('payment.destroy', $payment->id) }}">
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="position-absolute bottom-0 start-50 translate-middle-x">
        {{$payments->links()}}
    </div>
</div>
@endsection
