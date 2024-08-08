@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center bg-primary text-white">Car Details</div>

                <div class="card-body">
                    <h5 class="card-title text-center"><strong>{{ $car->brand }} {{ $car->model }}</strong></h5>
                    <p class="card-text text-center">{{ $car->description }}</p>
                    <ul class="list-group list-group-flush text-center">
                        <li class="list-group-item"><strong>Car Body:</strong> {{ $car->car_body }}</li>
                        <li class="list-group-item"><strong>Engine Type:</strong> {{ $car->engine_type }}</li>
                        <li class="list-group-item"><strong>Transmission:</strong> {{ $car->transmission }}</li>
                        <li class="list-group-item"><strong>Engine Power:</strong> {{ $car->engine_power }} HP</li>
                        <li class="list-group-item"><strong>Seats:</strong> {{ $car->seats }}</li>
                        <li class="list-group-item"><strong>Doors:</strong> {{ $car->doors }}</li>
                        <li class="list-group-item"><strong>Suitcases:</strong> {{ $car->suitcases }}</li>
                        <li class="list-group-item"><strong>Price:</strong> {{ $car->price }} PLN/24h</li>
                    </ul>
                    <div class="text-center mt-3">
                        <a href="{{ route('reservations.create', ['carId' => $car->id]) }}" class="btn btn-primary">Rent Now</a>
                    </div>
                </div>

                <div class="card-footer text-center">
                    <h5><strong>Reviews</strong></h5><br>
                    @forelse($reviews as $review)
                        <div class="d-flex align-items-center justify-content-center mb-3 offset-md-1">
                            <div class="me-3">
                                @if($review->user->image_path)
                                    <img src="{{ asset('storage/' . $review->user->image_path) }}" alt="Profile Photo" class="img-thumbnail rounded-circle mx-auto d-block" style="width: 64px; height: 64px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('images/default-profile.png') }}" alt="Profile Photo" class="img-thumbnail rounded-circle mx-auto d-block" style="width: 64px; height: 64px; object-fit: cover;">
                                @endif
                                <p class="mb-1"><strong>{{ $review->user->name }}</strong></p>
                            </div>
                            <div class="flex-grow-1 text-start">
                                <p class="mb-1">
                                    <strong>Comfort:</strong>
                                    {!! str_repeat('★', $review->comfort_rating) !!}
                                    {!! str_repeat('☆', 5 - $review->comfort_rating) !!}
                                </p>
                                <p class="mb-1">
                                    <strong>Driving Experience:</strong>
                                    {!! str_repeat('★', $review->driving_experience_rating) !!}
                                    {!! str_repeat('☆', 5 - $review->driving_experience_rating) !!}
                                </p>
                                <p class="mb-1">
                                    <strong>Fuel Efficiency:</strong>
                                    {!! str_repeat('★', $review->fuel_efficiency_rating) !!}
                                    {!! str_repeat('☆', 5 - $review->fuel_efficiency_rating) !!}
                                </p>
                                <p class="mb-1">
                                    <strong>Safety:</strong>
                                    {!! str_repeat('★', $review->safety_rating) !!}
                                    {!! str_repeat('☆', 5 - $review->safety_rating) !!}
                                </p>
                                <p class="mb-1"><strong>Overall Rating:</strong> {{ $review->overall_rating }}</p>
                                <p class="mb-1"><strong>Comment:</strong> {{ $review->comment }}</p>
                            </div>
                        </div>
                        <hr>
                    @empty
                        <p>No reviews available for this car.</p>
                    @endforelse
                    <div class="text-center">
                        <a href="{{ route('welcome') }}" class="btn btn-secondary btn-sm">Back to Main Tab</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
