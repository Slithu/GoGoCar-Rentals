@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center bg-primary text-white">Editing Review</div>

                <div class="card-body">
                    <form action="{{ route('reviews.update', $review->id) }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <label for="reservation_id" class="col-md-4 col-form-label text-md-end">Rental</label>
                            <div class="col-md-6">
                                <select id="reservation_id" class="form-control" name="reservation_id">
                                    @foreach($reservations as $reservation)
                                        <option value="{{ $review->reservation->id }}" {{ $review->reservation->id == $reservation->id ? 'selected' : '' }}>
                                            {{ $reservation->id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="user_id" class="col-md-4 col-form-label text-md-end">User</label>
                            <div class="col-md-6">
                                <select id="user_id" class="form-control" name="user_id">
                                    @foreach($users as $user)
                                        <option value="{{ $review->user->id }}" {{ $review->user->id == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} {{ $user->surname }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="car_id" class="col-md-4 col-form-label text-md-end">Car</label>
                            <div class="col-md-6">
                                <select id="car_id" class="form-control" name="car_id">
                                    @foreach($cars as $car)
                                        <option value="{{ $review->car->id }}" {{ $review->car->id == $car->id ? 'selected' : '' }}>
                                            {{ $car->brand }} {{ $car->model }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="comfort_rating" class="col-md-4 col-form-label text-md-end">Comfort</label>
                            <div class="col-md-6">
                                <input id="comfort_rating" type="number" min="1" max="5" class="form-control" name="comfort_rating" value="{{ $review->comfort_rating }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="driving_experience_rating" class="col-md-4 col-form-label text-md-end">Driving Experience</label>
                            <div class="col-md-6">
                                <input id="driving_experience_rating" type="number" min="1" max="5" class="form-control" name="driving_experience_rating" value="{{ $review->driving_experience_rating }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="fuel_efficiency_rating" class="col-md-4 col-form-label text-md-end">Fuel Efficiency</label>
                            <div class="col-md-6">
                                <input id="fuel_efficiency_rating" type="number" min="1" max="5" class="form-control" name="fuel_efficiency_rating" value="{{ $review->fuel_efficiency_rating }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="safety_rating" class="col-md-4 col-form-label text-md-end">Safety</label>
                            <div class="col-md-6">
                                <input id="safety_rating" type="number" min="1" max="5" class="form-control" name="safety_rating" value="{{ $review->safety_rating }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="comment" class="col-md-4 col-form-label text-md-end">Comment</label>
                            <div class="col-md-6">
                                <textarea id="comment" class="form-control" name="comment" rows="3">{{ $review->comment }}</textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-5">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="{{ route('reviews.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
