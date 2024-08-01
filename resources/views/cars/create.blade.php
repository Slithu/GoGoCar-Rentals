@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center bg-primary text-white">Creating A New Car</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('cars.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <label for="brand" class="col-md-4 col-form-label text-md-end">Brand</label>

                            <div class="col-md-6">
                                <input id="brand" maxlength="255" type="text" class="form-control @error('brand') is-invalid @enderror" name="brand" value="{{ old('brand') }}" required autocomplete="brand" autofocus>

                                @error('brand')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="model" class="col-md-4 col-form-label text-md-end">Model</label>

                            <div class="col-md-6">
                                <input id="model" type="text" maxlength="255" class="form-control @error('model') is-invalid @enderror" name="model" value="{{ old('model') }}" required autocomplete="model" autofocus>

                                @error('model')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="car_body" class="col-md-4 col-form-label text-md-end">Car Body</label>

                            <div class="col-md-6">
                                <select id="car_body" class="form-control @error('car_body') is-invalid @enderror" name="car_body" required autofocus>
                                    <option value="">Choose a car body</option>
                                    <option value="Small Car" {{ old('car_body') == 'Small Car' ? 'selected' : '' }}>Small Car</option>
                                    <option value="Coupe" {{ old('car_body') == 'Coupe' ? 'selected' : '' }}>Coupe</option>
                                    <option value="Convertible" {{ old('car_body') == 'Convertible' ? 'selected' : '' }}>Convertible</option>
                                    <option value="Hatchback" {{ old('car_body') == 'Hatchback' ? 'selected' : '' }}>Hatchback</option>
                                    <option value="Estate Car" {{ old('car_body') == 'Estate Car' ? 'selected' : '' }}>Estate Car</option>
                                    <option value="Sedan" {{ old('car_body') == 'Sedan' ? 'selected' : '' }}>Sedan</option>
                                    <option value="SUV" {{ old('car_body') == 'SUV' ? 'selected' : '' }}>SUV</option>
                                    <option value="Minivan" {{ old('car_body') == 'Minivan' ? 'selected' : '' }}>Minivan</option>
                                </select>

                                @error('car_body')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="engine_type" class="col-md-4 col-form-label text-md-end">Engine Type</label>

                            <div class="col-md-6">
                                <select id="engine_type" class="form-control @error('engine_type') is-invalid @enderror" name="engine_type" required autofocus>
                                    <option value="">Choose an engine type</option>
                                    <option value="Gasoline" {{ old('engine_type') == 'Gasoline' ? 'selected' : '' }}>Gasoline</option>
                                    <option value="Diesel" {{ old('engine_type') == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                                    <option value="Hybrid" {{ old('engine_type') == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                                    <option value="Electric" {{ old('engine_type') == 'Electric' ? 'selected' : '' }}>Electric</option>
                                </select>

                                @error('engine_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="transmission" class="col-md-4 col-form-label text-md-end">Transmission</label>

                            <div class="col-md-6">
                                <select id="transmission" class="form-control @error('transmission') is-invalid @enderror" name="transmission" required autofocus>
                                    <option value="">Choose a transmission type</option>
                                    <option value="Automatic" {{ old('transmission') == 'Automatic' ? 'selected' : '' }}>Automatic</option>
                                    <option value="Manual" {{ old('transmission') == 'Manual' ? 'selected' : '' }}>Manual</option>
                                </select>

                                @error('transmission')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="engine_power" class="col-md-4 col-form-label text-md-end">Engine Power</label>

                            <div class="col-md-6">
                                <input id="engine_power" type="number" maxlength="3" min="0" class="form-control @error('engine_power') is-invalid @enderror" name="engine_power" value="{{ old('engine_power') }}" required autocomplete="engine_power" autofocus>

                                @error('engine_power')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="seats" class="col-md-4 col-form-label text-md-end">Seats</label>

                            <div class="col-md-6">
                                <input id="seats" type="number" maxlength="2" min="2" class="form-control @error('seats') is-invalid @enderror" name="seats" value="{{ old('seats') }}" required autocomplete="seats" autofocus>

                                @error('seats')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="doors" class="col-md-4 col-form-label text-md-end">Doors</label>

                            <div class="col-md-6">
                                <input id="doors" type="number" maxlength="2" min="2" class="form-control @error('doors') is-invalid @enderror" name="doors" value="{{ old('doors') }}" required autocomplete="doors" autofocus>

                                @error('doors')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="suitcases" class="col-md-4 col-form-label text-md-end">Suitcases</label>

                            <div class="col-md-6">
                                <input id="suitcases" type="number" maxlength="2" min="1" class="form-control @error('suitcases') is-invalid @enderror" name="suitcases" value="{{ old('suitcases') }}" required autocomplete="suitcases" autofocus>

                                @error('suitcases')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="price" class="col-md-4 col-form-label text-md-end">Price</label>

                            <div class="col-md-6">
                                <input id="price" type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}" required autocomplete="price" autofocus>

                                @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="description" class="col-md-4 col-form-label text-md-end">Description</label>

                            <div class="col-md-6">
                                <textarea id="description" maxlength="1500" class="form-control @error('description') is-invalid @enderror" name="description" required autofocus>{{ old('description') }}</textarea>

                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="image" class="col-md-4 col-form-label text-md-end">Image</label>

                            <div class="col-md-6">
                                <input id="image" type="file" class="form-control @error('image') is-invalid @enderror" name="image">

                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-6">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
