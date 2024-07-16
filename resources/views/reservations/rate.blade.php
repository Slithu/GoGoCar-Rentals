@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="card text-center">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Rate This Car</h5>
                </div>
                <div class="card-body text-center">
                    @if(isset($reservation) && isset($car))
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <p><strong>Car:</strong> {{ $car->brand }} {{ $car->model }}</p>
                                <p><strong>Start Date:</strong> {{ $reservation->start_date }}</p>
                                <p><strong>End Date:</strong> {{ $reservation->end_date }}</p>
                                <p><strong>Total Price:</strong> {{ $reservation->total_price }} PLN</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('reviews.storeReview') }}">
                            @csrf

                            <input type="hidden" name="car_id" value="{{ $car->id }}">
                            <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
                            <input type="hidden" name="user_id" value="{{ Auth::id() }}">

                            <div class="form-group">
                                <label for="comfort_rating">Comfort Rating</label>
                                <select name="comfort_rating" id="comfort_rating" class="form-control form-control-sm text-center" required>
                                    <option value="1">★</option>
                                    <option value="2">★★</option>
                                    <option value="3">★★★</option>
                                    <option value="4">★★★★</option>
                                    <option value="5">★★★★★</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="driving_experience_rating">Driving Experience Rating</label>
                                <select name="driving_experience_rating" id="driving_experience_rating" class="form-control form-control-sm text-center" required>
                                    <option value="1">★</option>
                                    <option value="2">★★</option>
                                    <option value="3">★★★</option>
                                    <option value="4">★★★★</option>
                                    <option value="5">★★★★★</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="fuel_efficiency_rating">Fuel Efficiency Rating</label>
                                <select name="fuel_efficiency_rating" id="fuel_efficiency_rating" class="form-control form-control-sm text-center" required>
                                    <option value="1">★</option>
                                    <option value="2">★★</option>
                                    <option value="3">★★★</option>
                                    <option value="4">★★★★</option>
                                    <option value="5">★★★★★</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="safety_rating">Safety Rating</label>
                                <select name="safety_rating" id="safety_rating" class="form-control form-control-sm text-center" required>
                                    <option value="1">★</option>
                                    <option value="2">★★</option>
                                    <option value="3">★★★</option>
                                    <option value="4">★★★★</option>
                                    <option value="5">★★★★★</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="comment">Comment</label>
                                <textarea name="comment" id="comment" class="form-control form-control-sm text-center" rows="4"></textarea>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary mt-3">Submit Review</button>
                            </div>
                        </form>
                    @else
                        <p>No reservation details available.</p>
                        @if(isset($message))
                            <p>{{ $message }}</p>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .form-group, .card-body {
        font-size: 1.1rem;
    }
    #comment {
        font-size: 1rem;
    }
</style>
