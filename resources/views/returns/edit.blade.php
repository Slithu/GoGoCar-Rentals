@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center bg-primary text-white">Editing Return Car</div>

                <div class="card-body text-center">
                    <form action="{{ route('returns.update', $car_returns->id) }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="reservation_id"><strong>Rental ID:</strong></label>
                            <input name="reservation_id" id="reservation_id" class="form-control text-center" value="{{ $car_returns->reservation->id }}" readonly>
                        </div><br>

                        <div class="form-group">
                            <label for="car_id"><strong>Car ID:</strong></label>
                            <input name="car_id" id="car_id" class="form-control text-center" value="{{ $car_returns->reservation->car->id }}" readonly>
                        </div><br>

                        <div class="form-group">
                            <label for="car"><strong>Car:</strong></label>
                            <input name="car" id="car" class="form-control text-center" value="{{ $car_returns->reservation->car->brand }} {{ $car_returns->reservation->car->model }}" readonly>
                        </div><br>

                        <div class="form-group">
                            <label for="user_id"><strong>User ID:</strong></label>
                            <input name="user_id" id="user_id" class="form-control text-center" value="{{ $car_returns->reservation->user->id }}" readonly>
                        </div><br>

                        <div class="form-group">
                            <label for="user"><strong>User:</strong></label>
                            <input name="user" id="user" class="form-control text-center" value="{{ $car_returns->reservation->user->name }} {{ $car_returns->reservation->user->surname }}" readonly>
                        </div><br>

                        <div class="form-group">
                            <label for="rental_date"><strong>Rental Date:</strong></label>
                            <input name="rental_date" id="rental_date" class="form-control text-center" value="{{ $car_returns->reservation->start_date }} --- {{ $car_returns->reservation->end_date }}" readonly>
                        </div><br>

                        <div class="form-group">
                            <label for="price"><strong>Rental Price:</strong></label>
                            <input name="price" id="price" class="form-control text-center" value="{{ $car_returns->reservation->total_price }} PLN" readonly>
                        </div><br>

                        <div class="form-group">
                            <label for="return_date"><strong>Return Date:</strong></label>
                            <input type="datetime-local" id="return_date" name="return_date" class="form-control text-center" value="{{ $car_returns->return_date }}" required>
                        </div><br>

                        <div class="form-group">
                            <label for="exterior_condition"><strong>Exterior Condition:</strong></label>
                            <select id="exterior_condition" name="exterior_condition" class="form-control text-center" required>
                                <option value="No damage, scratches or dents" {{ $car_returns->exterior_condition == 'No damage, scratches or dents' ? 'selected' : '' }}>No damage, scratches or dents</option>
                                <option value="Minor scratches or dents that are difficult to notice" {{ $car_returns->exterior_condition == 'Minor scratches or dents that are difficult to notice' ? 'selected' : '' }}>Minor scratches or dents that are difficult to notice</option>
                                <option value="Visible scratches or dents, but do not affect the functionality of the vehicle" {{ $car_returns->exterior_condition == 'Visible scratches or dents, but do not affect the functionality of the vehicle' ? 'selected' : '' }}>Visible scratches or dents, but do not affect the functionality of the vehicle</option>
                                <option value="Significant damage that may require repair, but vehicle is still functional" {{ $car_returns->exterior_condition == 'Significant damage that may require repair, but vehicle is still functional' ? 'selected' : '' }}>Significant damage that may require repair, but vehicle is still functional</option>
                                <option value="Serious damage that affects the functionality of the vehicle" {{ $car_returns->exterior_condition == 'Serious damage that affects the functionality of the vehicle' ? 'selected' : '' }}>Serious damage that affects the functionality of the vehicle</option>
                            </select>
                        </div><br>

                        <div class="form-group">
                            <label for="interior_condition"><strong>Interior Condition:</strong></label>
                            <select id="interior_condition" name="interior_condition" class="form-control text-center" required>
                                <option value="The interior is in perfect condition, no signs of use" {{ $car_returns->interior_condition == 'The interior is in perfect condition, no signs of use' ? 'selected' : '' }}>The interior is in perfect condition, no signs of use</option>
                                <option value="Minor signs of use, no damage to the upholstery or equipment" {{ $car_returns->interior_condition == 'Minor signs of use, no damage to the upholstery or equipment' ? 'selected' : '' }}>Minor signs of use, no damage to the upholstery or equipment</option>
                                <option value="Visible signs of use, minor damage to the upholstery or equipment" {{ $car_returns->interior_condition == 'Visible signs of use, minor damage to the upholstery or equipment' ? 'selected' : '' }}>Visible signs of use, minor damage to the upholstery or equipment</option>
                                <option value="Significant signs of use, damage to upholstery or equipment that may require repair" {{ $car_returns->interior_condition == 'Significant signs of use, damage to upholstery or equipment that may require repair' ? 'selected' : '' }}>Significant signs of use, damage to upholstery or equipment that may require repair</option>
                                <option value="Serious damage to the interior that affects the comfort of use of the vehicle" {{ $car_returns->interior_condition == 'Serious damage to the interior that affects the comfort of use of the vehicle' ? 'selected' : '' }}>Serious damage to the interior that affects the comfort of use of the vehicle</option>
                            </select>
                        </div><br>

                        <div class="form-group">
                            <label for="exterior_damage_description"><strong>Exterior Damage Description (optional):</strong></label>
                            <textarea id="exterior_damage_description" name="exterior_damage_description" class="form-control text-center" rows="3">{{ $car_returns->exterior_damage_description }}</textarea>
                        </div><br>

                        <div class="form-group">
                            <label for="interior_damage_description"><strong>Interior Damage Description (optional):</strong></label>
                            <textarea id="interior_damage_description" name="interior_damage_description" class="form-control text-center" rows="3">{{ $car_returns->interior_condition_description }}</textarea>
                        </div><br>

                        <div class="form-group">
                            <label for="car_parts_condition"><strong>Car Parts Condition (optional):</strong></label>
                            <textarea id="car_parts_condition" name="car_parts_condition" class="form-control text-center" rows="3">{{ $car_returns->car_parts_condition }}</textarea>
                        </div><br>

                        <div class="form-group">
                            <label for="penalty_amount"><strong>Penalty Amount (optional):</strong></label>
                            <input type="number" id="penalty_amount" name="penalty_amount" class="form-control text-center" min="0" step="0.01" value="{{ $car_returns->penalty_amount ?? '0.00' }}">
                        </div><br>

                        <div class="form-group">
                            <label for="comments"><strong>Comments (optional):</strong></label>
                            <textarea id="comments" name="comments" class="form-control text-center" rows="3">{{ $car_returns->comments }}</textarea>
                        </div><br>

                        <div class="col-md-6 offset-md-3">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="{{ route('returns.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
