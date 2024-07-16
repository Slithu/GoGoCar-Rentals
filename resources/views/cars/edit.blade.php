@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">Editing A Car</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('cars.update', $car->id) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <label for="brand" class="col-md-4 col-form-label text-md-end">Brand</label>

                            <div class="col-md-6">
                                <input id="brand" maxlength="255" type="text" class="form-control @error('brand') is-invalid @enderror" name="brand" value="{{ $car->brand }}" required autocomplete="brand" autofocus>

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
                                <input id="model" maxlength="255" type="text" class="form-control @error('model') is-invalid @enderror" name="model" value="{{ $car->model }}" required autocomplete="model" autofocus>

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
                                <select class="form-control" id="car_body" name="car_body" required>
                                    <option value="Small Car" <?php if ($lastSelectedCarBody === 'Small Car') echo 'selected'; ?>>Small Car</option>
                                    <option value="Coupe" <?php if ($lastSelectedCarBody === 'Coupe') echo 'selected'; ?>>Coupe</option>
                                    <option value="Convertible" <?php if ($lastSelectedCarBody === 'Convertible') echo 'selected'; ?>>Convertible</option>
                                    <option value="Hatchback" <?php if ($lastSelectedCarBody === 'Hatchback') echo 'selected'; ?>>Hatchback</option>
                                    <option value="Estate Car" <?php if ($lastSelectedCarBody === 'Estate Car') echo 'selected'; ?>>Estate Car</option>
                                    <option value="Sedan" <?php if ($lastSelectedCarBody === 'Sedan') echo 'selected'; ?>>Sedan</option>
                                    <option value="SUV" <?php if ($lastSelectedCarBody === 'SUV') echo 'selected'; ?>>SUV</option>
                                    <option value="Minivan" <?php if ($lastSelectedCarBody === 'Minivan') echo 'selected'; ?>>Minivan</option>
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
                                <select class="form-control" id="engine_type" name="engine_type" required>
                                    <option value="Gasoline" <?php if ($lastSelectedEngineType === 'Gasoline') echo 'selected'; ?>>Gasoline</option>
                                    <option value="Diesel" <?php if ($lastSelectedEngineType === 'Diesel') echo 'selected'; ?>>Diesel</option>
                                    <option value="Hybrid" <?php if ($lastSelectedEngineType === 'Hybrid') echo 'selected'; ?>>Hybrid</option>
                                    <option value="Electric" <?php if ($lastSelectedEngineType === 'Electric') echo 'selected'; ?>>Electric</option>
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
                                <select class="form-control" id="transmission" name="transmission" required>
                                    <option value="Automatic" <?php if ($lastSelectedTransmission === 'Automatic') echo 'selected'; ?>>Automatic</option>
                                    <option value="Manual" <?php if ($lastSelectedTransmission === 'Manual') echo 'selected'; ?>>Manual</option>
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
                                <input id="engine_power" type="number" maxlength="3" min="0" class="form-control @error('engine_power') is-invalid @enderror" name="engine_power" value="{{ $car->engine_power }}" required autocomplete="engine_power" autofocus>

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
                                <input id="seats" type="number" maxlength="2" min="2" class="form-control @error('seats') is-invalid @enderror" name="seats" value="{{ $car->seats }}" required autocomplete="seats" autofocus>

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
                                <input id="doors" type="number" maxlength="2" min="2" class="form-control @error('doors') is-invalid @enderror" name="doors" value="{{ $car->doors }}" required autocomplete="doors" autofocus>

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
                                <input id="suitcases" type="number" maxlength="2" min="1" class="form-control @error('suitcases') is-invalid @enderror" name="suitcases" value="{{ $car->suitcases }}" required autocomplete="suitcases" autofocus>

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
                                <input id="price" type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ $car->price }}" required autocomplete="price" autofocus>

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
                                <textarea id="description" maxlength="1500" class="form-control @error('description') is-invalid @enderror" name="description" required autofocus>{{ $car->description }}</textarea>

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
                            <div class="col-md-6 offset-md-5">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="{{ route('cars.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
