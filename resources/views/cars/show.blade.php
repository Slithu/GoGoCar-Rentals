@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">Show Car</div>

                <div class="card-body">
                    <div class="row mb-3">
                        <label for="brand" class="col-md-4 col-form-label text-md-end">Brand</label>
                        <div class="col-md-6">
                            <input id="brand" type="text" class="form-control" name="brand" value="{{ $car->brand }}" disabled>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="model" class="col-md-4 col-form-label text-md-end">Model</label>
                        <div class="col-md-6">
                            <input id="model" type="text" class="form-control" name="model" value="{{ $car->model }}" disabled>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="car_body" class="col-md-4 col-form-label text-md-end">Car Body</label>
                        <div class="col-md-6">
                            <select class="form-control" id="car_body" name="car_body" disabled>
                                <option value="Small Car" {{ $car->car_body === 'Small Car' ? 'selected' : '' }}>Small Car</option>
                                <option value="Coupe" {{ $car->car_body === 'Coupe' ? 'selected' : '' }}>Coupe</option>
                                <option value="Convertible" {{ $car->car_body === 'Convertible' ? 'selected' : '' }}>Convertible</option>
                                <option value="Hatchback" {{ $car->car_body === 'Hatchback' ? 'selected' : '' }}>Hatchback</option>
                                <option value="Estate Car" {{ $car->car_body === 'Estate Car' ? 'selected' : '' }}>Estate Car</option>
                                <option value="Sedan" {{ $car->car_body === 'Sedan' ? 'selected' : '' }}>Sedan</option>
                                <option value="SUV" {{ $car->car_body === 'SUV' ? 'selected' : '' }}>SUV</option>
                                <option value="Minivan" {{ $car->car_body === 'Minivan' ? 'selected' : '' }}>Minivan</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="engine_type" class="col-md-4 col-form-label text-md-end">Engine Type</label>
                        <div class="col-md-6">
                            <select class="form-control" id="engine_type" name="engine_type" disabled>
                                <option value="Gasoline" {{ $car->engine_type === 'Gasoline' ? 'selected' : '' }}>Gasoline</option>
                                <option value="Diesel" {{ $car->engine_type === 'Diesel' ? 'selected' : '' }}>Diesel</option>
                                <option value="Hybrid" {{ $car->engine_type === 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                                <option value="Electric" {{ $car->engine_type === 'Electric' ? 'selected' : '' }}>Electric</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="transmission" class="col-md-4 col-form-label text-md-end">Transmission</label>
                        <div class="col-md-6">
                            <select class="form-control" id="transmission" name="transmission" disabled>
                                <option value="Automatic" {{ $car->transmission === 'Automatic' ? 'selected' : '' }}>Automatic</option>
                                <option value="Manual" {{ $car->transmission === 'Manual' ? 'selected' : '' }}>Manual</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="engine_power" class="col-md-4 col-form-label text-md-end">Engine Power</label>
                        <div class="col-md-6">
                            <input id="engine_power" type="number" class="form-control" name="engine_power" value="{{ $car->engine_power }}" disabled>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="seats" class="col-md-4 col-form-label text-md-end">Seats</label>
                        <div class="col-md-6">
                            <input id="seats" type="number" class="form-control" name="seats" value="{{ $car->seats }}" disabled>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="doors" class="col-md-4 col-form-label text-md-end">Doors</label>
                        <div class="col-md-6">
                            <input id="doors" type="number" class="form-control" name="doors" value="{{ $car->doors }}" disabled>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="suitcases" class="col-md-4 col-form-label text-md-end">Suitcases</label>
                        <div class="col-md-6">
                            <input id="suitcases" type="number" class="form-control" name="suitcases" value="{{ $car->suitcases }}" disabled>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="price" class="col-md-4 col-form-label text-md-end">Price</label>
                        <div class="col-md-6">
                            <input id="price" type="number" class="form-control" name="price" value="{{ $car->price }}" disabled>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="description" class="col-md-4 col-form-label text-md-end">Description</label>
                        <div class="col-md-6">
                            <textarea id="description" maxlength="1500" class="form-control" name="description" disabled>{{ $car->description }}</textarea>
                        </div>
                    </div>

                    <a class="nav-link" href="{{ route('cars.index') }}" style="text-align:center">
                        <button class="btn btn-success btn-sm">Return to Cars Fleet</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
