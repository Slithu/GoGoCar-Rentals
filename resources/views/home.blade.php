@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-center">
                <div class="card-header bg-primary text-white">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <strong>{{ __('Welcome!') }}</strong>
                    <p><strong>{{ __('You are logged in!') }}</strong></p>
                    <img src="images/home.jpeg" alt="home" class="border" style="margin-bottom: 4%; border-radius: 8px; width: 80%; height: 80%">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
