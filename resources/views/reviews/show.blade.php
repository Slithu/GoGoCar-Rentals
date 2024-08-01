@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center bg-primary text-white">Show Review</div>

                <div class="card-body">
                        <div class="row mb-3">
                            <label for="reservation_id" class="col-md-4 col-form-label text-md-end">Rental</label>
                            <div class="col-md-6">
                                <select id="reservation_id" class="form-control" name="reservation_id" disabled>
                                        <option value="{{ $review->reservation_id }}"> {{ $review->reservation_id }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="user_id" class="col-md-4 col-form-label text-md-end">User</label>
                            <div class="col-md-6">
                                <select id="user_id" class="form-control" name="user_id" disabled>
                                        <option value="{{ $review->user->id }}">{{ $review->user->name }} {{ $review->user->surname }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="car_id" class="col-md-4 col-form-label text-md-end">Car</label>
                            <div class="col-md-6">
                                <select id="car_id" class="form-control" name="car_id" disabled>
                                    @foreach($cars as $car)
                                            <option value="{{ $review->car->id }}">{{ $review->car->brand }} {{ $review->car->model }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="comfort_rating" class="col-md-4 col-form-label text-md-end">Comfort</label>
                            <div class="col-md-6">
                                <input id="comfort_rating" type="number" class="form-control" name="comfort_rating" value="{{ $review->comfort_rating }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="driving_experience_rating" class="col-md-4 col-form-label text-md-end">Driving Experience</label>
                            <div class="col-md-6">
                                <input id="driving_experience_rating" type="number" class="form-control" name="driving_experience_rating" value="{{ $review->driving_experience_rating }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="fuel_efficiency_rating" class="col-md-4 col-form-label text-md-end">Fuel Efficiency</label>
                            <div class="col-md-6">
                                <input id="fuel_efficiency_rating" type="number" class="form-control" name="fuel_efficiency_rating" value="{{ $review->fuel_efficiency_rating }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="safety_rating" class="col-md-4 col-form-label text-md-end">Safety</label>
                            <div class="col-md-6">
                                <input id="safety_rating" type="number" class="form-control" name="safety_rating" value="{{ $review->safety_rating }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="overall_rating" class="col-md-4 col-form-label text-md-end">Overall</label>
                            <div class="col-md-6">
                                <input id="overall_rating" type="number" class="form-control" name="overall_rating" value="{{ $review->overall_rating }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="comment" class="col-md-4 col-form-label text-md-end">Comment</label>
                            <div class="col-md-6">
                                <textarea id="comment" class="form-control" name="comment" rows="3" disabled>{{ $review->comment }}</textarea>
                            </div>
                        </div>

                        <a class="nav-link" href="{{ route('reviews.index') }}" style="text-align:center">
                            <button class="btn btn-secondary btn-sm">Return to Reviews</button>
                        </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
