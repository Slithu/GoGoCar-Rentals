@extends('layouts.app')

@section('content')
<section class="py-5 text-center container">
    <div class="row py-lg-6">
        <div class="col-lg-6 col-md-8 mx-auto">
            <h1 class="fw-light">GoGoCar Rentals</h1><br>
            <p class="lead text-muted">Welcome to GoGoCar Rentals! We have many available cars for you to choose from. You're sure to find something that suits your needs. Rent a car and enjoy the convenience and comfort we offer!</p>
        </div>
    </div>
</section>

<div class="container mt-4">
    @can('isUser')
        @if(Auth::check() && $recommendedCars->isNotEmpty())
        <div class="accordion" id="recommendedAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingRecommended">
                    <button class="accordion-button collapsed bg-primary text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRecommended" aria-expanded="false" aria-controls="collapseRecommended" style="font-size: 1rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-stars" viewBox="0 0 16 16">
                            <path d="M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.828zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.73 1.73 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0L3.407 5.69A1.73 1.73 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.73 1.73 0 0 0 3.407 2.31zM10.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.16 1.16 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.16 1.16 0 0 0-.732-.732L9.1 2.137a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732z"/>
                        </svg>
                        Recommended For You
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-stars" viewBox="0 0 16 16">
                            <path d="M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.828zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.73 1.73 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0L3.407 5.69A1.73 1.73 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.73 1.73 0 0 0 3.407 2.31zM10.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.16 1.16 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.16 1.16 0 0 0-.732-.732L9.1 2.137a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732z"/>
                        </svg>
                    </button>
                </h2>
                <div id="collapseRecommended" class="accordion-collapse collapse bg-primary-subtle" aria-labelledby="headingRecommended" data-bs-parent="#recommendedAccordion">
                    <div class="accordion-body">
                        <div class="row">
                            @foreach ($recommendedCars as $car)
                                <div class="col-md-4 mb-4">
                                    <div class="card">
                                        <img src="{{ $car->image_path ? Storage::url($car->image_path) : 'default-image.jpg' }}" class="card-img-top" alt="{{ $car->brand }} {{ $car->model }}">
                                        <div class="card-body">
                                            <h5 class="card-title"><strong>{{ $car->brand }} {{ $car->model }}</strong></h5>
                                            <p class="card-text">{{ $car->description }}</p>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item"><strong>Car Body:</strong> {{ $car->car_body }}</li>
                                                <li class="list-group-item"><strong>Engine Type:</strong> {{ $car->engine_type }}</li>
                                                <li class="list-group-item"><strong>Transmission:</strong> {{ $car->transmission }}</li>
                                                <li class="list-group-item"><strong>Engine Power:</strong> {{ $car->engine_power }} HP</li>
                                                <li class="list-group-item"><strong>Seats:</strong> {{ $car->seats }}</li>
                                                <li class="list-group-item"><strong>Doors:</strong> {{ $car->doors }}</li>
                                                <li class="list-group-item"><strong>Suitcases:</strong> {{ $car->suitcases }}</li>
                                                <li class="list-group-item"><strong>Price:</strong> {{ $car->price }} PLN/24h</li>
                                                <li class="list-group-item"><strong>Availability Now:</strong> {{$car->availability->available ? 'Available' : 'Not Available' }}</li>
                                            </ul>
                                            <div class="d-flex justify-content-between mt-3">
                                                <a href="{{ route('reservations.create', ['carId' => $car->id]) }}" class="btn btn-primary">Rent Now</a>
                                                <a href="{{ route('cars.detail', ['car' => $car->id]) }}" class="btn btn-outline-primary">See More</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @endcan
    <br><br>

    <form method="GET" action="{{ route('welcome') }}" class="mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <input type="text" name="brand" class="form-control" placeholder="Brand" value="{{ request('brand') }}">
            </div>
            <div class="col-md-4">
                <input type="text" name="model" class="form-control" placeholder="Model" value="{{ request('model') }}">
            </div>
            <div class="col-md-4">
                <select name="car_body" class="form-control">
                    <option value="">Car Body</option>
                    @foreach(['Small Car', 'Coupe', 'Convertible', 'Hatchback', 'Estate Car', 'Sedan', 'SUV', 'Minivan'] as $body)
                        <option value="{{ $body }}" {{ request('car_body') == $body ? 'selected' : '' }}>{{ $body }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="engine_type" class="form-control">
                    <option value="">Engine Type</option>
                    @foreach(['Gasoline', 'Diesel', 'Hybrid', 'Electric'] as $type)
                        <option value="{{ $type }}" {{ request('engine_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="transmission" class="form-control">
                    <option value="">Transmission</option>
                    @foreach(['Automatic', 'Manual'] as $transmission)
                        <option value="{{ $transmission }}" {{ request('transmission') == $transmission ? 'selected' : '' }}>{{ $transmission }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <input type="number" name="min_power" class="form-control" placeholder="Min Power" value="{{ request('min_power') }}">
            </div>
            <div class="col-md-4">
                <input type="number" name="max_power" class="form-control" placeholder="Max Power" value="{{ request('max_power') }}">
            </div>
            <div class="col-md-4">
                <input type="number" name="min_price" class="form-control" placeholder="Min Price" value="{{ request('min_price') }}">
            </div>
            <div class="col-md-4">
                <input type="number" name="max_price" class="form-control" placeholder="Max Price" value="{{ request('max_price') }}">
            </div>
            <div class="col-12 d-flex justify-content-center mt-3">
                <button type="submit" class="btn btn-primary me-2" style="width: 12rem; --bs-btn-font-size: 1rem">Search</button>
                <a href="{{ route('welcome') }}" class="btn btn-secondary" style="width: 12rem; --bs-btn-font-size: 1rem">Reset</a>
            </div>
        </div>
    </form>

    <div class="row">
        @if($cars->isEmpty())
            <div class="col-12">
                <div class="alert alert-warning" role="alert">
                    No cars found matching the search criteria.
                </div>
            </div>
        @else
            @foreach($cars as $car)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="{{ $car->image_path ? Storage::url($car->image_path) : 'default-image.jpg' }}" class="card-img-top" alt="{{ $car->brand }} {{ $car->model }}">
                    <div class="card-body">
                        <h5 class="card-title"><strong>{{ $car->brand }} {{ $car->model }}</strong></h5>
                        <p class="card-text">{{ $car->description }}</p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Car Body:</strong> {{ $car->car_body }}</li>
                            <li class="list-group-item"><strong>Engine Type:</strong> {{ $car->engine_type }}</li>
                            <li class="list-group-item"><strong>Transmission:</strong> {{ $car->transmission }}</li>
                            <li class="list-group-item"><strong>Engine Power:</strong> {{ $car->engine_power }} HP</li>
                            <li class="list-group-item"><strong>Seats:</strong> {{ $car->seats }}</li>
                            <li class="list-group-item"><strong>Doors:</strong> {{ $car->doors }}</li>
                            <li class="list-group-item"><strong>Suitcases:</strong> {{ $car->suitcases }}</li>
                            <li class="list-group-item"><strong>Price:</strong> {{ $car->price }} PLN/24h</li>
                            <li class="list-group-item"><strong>Availability Now:</strong> {{$car->availability->available ? 'Available' : 'Not Available' }}</li>
                        </ul>
                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('reservations.create', ['carId' => $car->id]) }}" class="btn btn-primary">Rent Now</a>
                            <a href="{{ route('cars.detail', ['car' => $car->id]) }}" class="btn btn-outline-primary">See More</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
